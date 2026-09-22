<?php
/**
 * Handles a submitted quote form.
 *
 * Flow: check for bots → validate → save the lead → email the office → send the visitor to the thank-you page.
 * If something is missing, the visitor goes back to the form with their answers kept and clear error messages.
 *
 * Spam protection without a nonce, on purpose: nonces expire and break on cached pages,
 * which silently loses real leads. Instead we use a hidden "honeypot" field, a signed
 * timestamp (bots submit instantly), and a per-visitor limit.
 *
 * Nothing is ever thrown away: anything that looks like a bot is saved with the status
 * "Misstänkt spam", with no email alert and no conversion event. If a real person trips
 * a check, the lead is still in the inbox.
 *
 * @package Zorans MåleriLeads
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_post_nopriv_npl_submit', 'npl_handle_submit' );
add_action( 'admin_post_npl_submit', 'npl_handle_submit' );

/**
 * Signed timestamp placed in the form when it is shown.
 *
 * @return string
 */
function npl_form_token() {
	$time = time();
	return $time . '.' . hash_hmac( 'sha256', (string) $time, wp_salt( 'nonce' ) );
}

/**
 * True if the token is genuine and the form was open for at least 3 seconds.
 * No maximum age: a cached page can be days old and still carry a real person's lead.
 *
 * @param string $token Token from the form.
 * @return bool
 */
function npl_token_ok( $token ) {
	$parts = explode( '.', (string) $token );
	if ( 2 !== count( $parts ) ) {
		return false;
	}
	list( $time, $sig ) = $parts;
	if ( ! hash_equals( hash_hmac( 'sha256', $time, wp_salt( 'nonce' ) ), $sig ) ) {
		return false;
	}
	return time() - (int) $time >= 3;
}

/**
 * Validate the posted answers.
 *
 * @param array $in Raw input.
 * @return array{0: array, 1: array} Clean values, errors keyed by field.
 */
function npl_validate( $in ) {
	$choices = npl_choices();
	$v       = array(
		'zip'      => substr( preg_replace( '/\D/', '', $in['zip'] ?? '' ), 0, 5 ),
		'service'  => sanitize_key( $in['service'] ?? '' ),
		'timeline' => sanitize_key( $in['timeline'] ?? '' ),
		'name'     => sanitize_text_field( wp_unslash( $in['name'] ?? '' ) ),
		'phone'    => sanitize_text_field( wp_unslash( $in['phone'] ?? '' ) ),
		'email'    => sanitize_email( wp_unslash( $in['email'] ?? '' ) ),
	);
	$errors  = array();

	if ( 5 !== strlen( $v['zip'] ) ) {
		$errors['zip'] = __( 'Skriv ditt postnummer, till exempel 123 45.', 'zoransmaleri-leads' );
	}
	if ( ! isset( $choices['service'][ $v['service'] ] ) ) {
		$errors['service'] = __( 'Välj vad du behöver hjälp med.', 'zoransmaleri-leads' );
	}
	if ( ! isset( $choices['timeline'][ $v['timeline'] ] ) ) {
		$errors['timeline'] = __( 'Välj när du vill ha jobbet gjort.', 'zoransmaleri-leads' );
	}
	if ( strlen( $v['name'] ) < 2 ) {
		$errors['name'] = __( 'Skriv ditt förnamn.', 'zoransmaleri-leads' );
	}
	$digits = preg_replace( '/\D/', '', $v['phone'] );
	if ( 11 === strlen( $digits ) && '1' === $digits[0] ) {
		$digits = substr( $digits, 1 );
	}
	if ( 10 !== strlen( $digits ) ) {
		$errors['phone'] = __( 'Skriv ditt mobilnummer, till exempel 070-123 45 67.', 'zoransmaleri-leads' );
	} else {
		$v['phone'] = sprintf( '(%s) %s-%s', substr( $digits, 0, 3 ), substr( $digits, 3, 3 ), substr( $digits, 6 ) );
	}
	if ( ! is_email( $v['email'] ) ) {
		$errors['email'] = __( 'Skriv en e-postadress, till exempel namn@exempel.se.', 'zoransmaleri-leads' );
	}

	foreach ( npl_tracking_fields() as $key ) {
		$raw     = wp_unslash( $in[ $key ] ?? '' );
		$v[ $key ] = in_array( $key, array( 'landing_page', 'referrer' ), true ) ? esc_url_raw( $raw ) : sanitize_text_field( $raw );
		$v[ $key ] = substr( $v[ $key ], 0, 500 );
	}

	return array( $v, $errors );
}

/**
 * Main handler.
 */
function npl_handle_submit() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- see file comment.
	$in   = $_POST;
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/free-quote/' );

	// 1. Validate. On errors, go back with answers kept.
	list( $values, $errors ) = npl_validate( $in );
	if ( $errors ) {
		$key = bin2hex( random_bytes( 8 ) );
		set_transient( 'npl_err_' . $key, array( 'errors' => $errors, 'values' => $values ), 10 * MINUTE_IN_SECONDS );
		wp_safe_redirect( add_query_arg( 'npl', $key, remove_query_arg( 'npl', $back ) ) . '#npl-form' );
		exit;
	}

	// 2. Bot checks: hidden field filled, submitted too fast or with a forged token, or more than
	//    10 leads from one visitor in 10 minutes. The IP is hashed for the count and never stored.
	$ip_key  = 'npl_rate_' . substr( hash_hmac( 'sha256', $_SERVER['REMOTE_ADDR'] ?? '', wp_salt() ), 0, 20 ); // phpcs:ignore
	$count   = (int) get_transient( $ip_key );
	$suspect = ! empty( $in['company_website'] ) || ! npl_token_ok( $in['npl_token'] ?? '' ) || $count >= 10;
	set_transient( $ip_key, $count + 1, 10 * MINUTE_IN_SECONDS );

	// 3. Save.
	$lead_id = wp_insert_post(
		array(
			'post_type'   => 'npl_lead',
			'post_status' => 'publish',
			'post_title'  => sprintf( '%s · %s', $values['name'], npl_label( 'service', $values['service'] ) ),
			'meta_input'  => array_merge(
				$values,
				array(
					'status'       => $suspect ? 'spam' : 'new',
					'consent_at'   => current_time( 'mysql' ),
					'consent_text' => npl_consent_text(),
				)
			),
		),
		true
	);
	if ( is_wp_error( $lead_id ) ) {
		wp_die( esc_html__( 'Något gick fel. Ring oss på 070-123 45 67 så tar vi det direkt.', 'zoransmaleri-leads' ), 500 );
	}

	// Misstänkt spam: kept for review, but no alert and no conversion event.
	if ( $suspect ) {
		wp_safe_redirect( add_query_arg( 'lead', 'ok', npl_thank_you_url() ) );
		exit;
	}

	// 4. Tell the office. Uses WordPress's normal email (add an SMTP plugin on a live site so it isn't marked as spam).
	$to   = apply_filters( 'npl_notify_email', get_option( 'admin_email' ) );
	$body = sprintf(
		"New quote request\n\nName: %s\nPhone: %s\nE-post: %s\nZIP: %s\nNeeds: %s\nWhen: %s\nSource: %s\n\nOpen: %s",
		$values['name'],
		$values['phone'],
		$values['email'],
		$values['zip'],
		npl_label( 'service', $values['service'] ),
		npl_label( 'timeline', $values['timeline'] ),
		npl_source_summary( $lead_id ),
		admin_url( 'post.php?post=' . $lead_id . '&action=edit' )
	);
	wp_mail( $to, sprintf( 'Ny förfrågan: %s (%s)', $values['name'], $values['zip'] ), $body, array( 'Reply-To: ' . $values['email'] ) );

	do_action( 'npl_lead_saved', $lead_id, $values ); // Hook for a CRM, Zapier or Slack integration.

	// 5. Thank-you page. A one-time key lets analytics count this lead exactly once (see tracking.php).
	$ref = substr( wp_hash( 'lead' . $lead_id . wp_rand() ), 0, 12 );
	set_transient( 'npl_ref_' . $ref, $values['service'], DAY_IN_SECONDS );
	wp_safe_redirect(
		add_query_arg(
			array(
				'lead'    => 'ok',
				'service' => $values['service'],
				'ref'     => $ref,
			),
			npl_thank_you_url()
		)
	);
	exit;
	// phpcs:enable
}

/**
 * Errors and kept answers for the form currently being shown, if the visitor was sent back.
 *
 * @return array{errors: array, values: array}
 */
function npl_current_state() {
	static $state = null;
	if ( null !== $state ) {
		return $state;
	}
	$state = array( 'errors' => array(), 'values' => array() );
	$key   = isset( $_GET['npl'] ) ? sanitize_key( $_GET['npl'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( $key ) {
		$saved = get_transient( 'npl_err_' . $key );
		if ( is_array( $saved ) ) {
			$state = $saved;
		}
	}
	return $state;
}
