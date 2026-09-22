<?php
/**
 * Analytics events, ready for Google Tag Manager (GTM) and GA4.
 *
 * The plugin never loads Google scripts itself. It only pushes events to window.dataLayer.
 * Add your GTM container (for example with Site Kit or a consent tool) and map these events:
 *
 *   npl_form_start   First time someone touches the quote form
 *   npl_form_step    Each step completed          { step: 1-4, step_name }
 *   npl_form_error   A step had missing answers   { step, fields }
 *   generate_lead    Thank-you page after a real, saved lead, once (GA4 recommended event name) { service }
 *   npl_click_call   Click on any phone link      { location }
 *   npl_click_cta    Click on a "quote" button    { location, text }
 *
 * @package Zorans MåleriLeads
 */

defined( 'ABSPATH' ) || exit;

/**
 * Small script on every page (about 1 KB): remembers where the visitor came from, tracks clicks on phone links and quote buttons.
 */
add_action(
	'init',
	function () {
		// Registered early: the quote form script depends on it (see blocks/lead-form/view.asset.php).
		wp_register_script( 'npl-track', NPL_URL . 'assets/track.js', array(), NPL_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	}
);
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_script( 'npl-track' );
	}
);

/**
 * Conversion event on the thank-you page. Only for a real, saved lead: the one-time key
 * made in submit.php must exist, and it is deleted here, so a refresh or a copied URL never counts twice.
 */
add_action(
	'wp_footer',
	function () {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$ref = isset( $_GET['ref'] ) ? sanitize_key( $_GET['ref'] ) : '';
		if ( ! $ref ) {
			return;
		}
		$service = get_transient( 'npl_ref_' . $ref );
		if ( false === $service ) {
			return;
		}
		delete_transient( 'npl_ref_' . $ref );
		printf(
			'<script>window.dataLayer=window.dataLayer||[];window.dataLayer.push(%s);</script>' . "\n",
			wp_json_encode( array( 'event' => 'generate_lead', 'service' => $service ) )
		);
	}
);

/**
 * Keep the thank-you page out of Google. Otherwise people land on it from search,
 * and every visit looks like a conversion in analytics.
 */
add_filter(
	'wp_robots',
	function ( $robots ) {
		$thanks = (int) get_option( 'npl_thank_you_page' );
		if ( $thanks && is_page( $thanks ) ) {
			$robots['noindex'] = true;
			$robots['follow']  = true;
		}
		return $robots;
	}
);

/**
 * On pages with the full quote form, mark the page as "JavaScript on" before anything is drawn,
 * so the form appears as steps straight away instead of jumping after load (no layout shift).
 */
add_action(
	'wp_head',
	function () {
		if ( is_singular() && has_block( 'zoransmaleri/lead-form', get_queried_object() ) ) {
			echo "<script>document.documentElement.classList.add('npl-js')</script>\n";
		}
	},
	1
);
