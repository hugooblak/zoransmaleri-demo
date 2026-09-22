<?php
/**
 * The "Leads" inbox: a private post type, list columns, a details box and a status field.
 *
 * @package Zorans MåleriLeads
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		register_post_type(
			'npl_lead',
			array(
				'labels'          => array(
					'name'          => __( 'Leads', 'zoransmaleri-leads' ),
					'singular_name' => __( 'Lead', 'zoransmaleri-leads' ),
					'edit_item'     => __( 'Förfrågan', 'zoransmaleri-leads' ),
					'search_items'  => __( 'Search leads', 'zoransmaleri-leads' ),
					'not_found'     => __( 'Inga förfrågningar än. Skicka formuläret så dyker den upp här.', 'zoransmaleri-leads' ),
					'all_items'     => __( 'Alla förfrågningar', 'zoransmaleri-leads' ),
				),
				'public'          => false, // Never visible on the front end or in search.
				'show_ui'         => true,
				'show_in_rest'    => false, // Keeps lead data out of the public REST API.
				'menu_icon'       => 'dashicons-email-alt',
				'menu_position'   => 3,
				'supports'        => array( 'title' ),
				// Own permissions, so only Administrators and Editors see leads (not Authors or Contributors).
				'capability_type' => array( 'npl_lead', 'npl_leads' ),
				'capabilities'    => array( 'create_posts' => 'do_not_allow' ), // Leads only come from the form.
				'map_meta_cap'    => true,
			)
		);
	}
);

/**
 * Give Administrators and Editors the lead permissions. Runs once, then remembers it.
 */
add_action(
	'init',
	function () {
		if ( get_option( 'npl_caps_version' ) === NPL_VERSION ) {
			return;
		}
		$caps = array( 'edit_npl_leads', 'edit_others_npl_leads', 'edit_published_npl_leads', 'read_private_npl_leads', 'delete_npl_leads', 'delete_others_npl_leads', 'delete_published_npl_leads', 'publish_npl_leads', 'export_npl_leads' );
		foreach ( array( 'administrator', 'editor' ) as $role_name ) {
			$role = get_role( $role_name );
			if ( $role ) {
				foreach ( $caps as $cap ) {
					$role->add_cap( $cap );
				}
			}
		}
		update_option( 'npl_caps_version', NPL_VERSION );
	},
	5
);

/**
 * Columns on the Leads list.
 */
add_filter(
	'manage_npl_lead_posts_columns',
	function () {
		return array(
			'cb'           => '<input type="checkbox" />',
			'title'        => __( 'Name', 'zoransmaleri-leads' ),
			'npl_phone'    => __( 'Phone', 'zoransmaleri-leads' ),
			'npl_service'  => __( 'Needs', 'zoransmaleri-leads' ),
			'npl_timeline' => __( 'When', 'zoransmaleri-leads' ),
			'npl_zip'      => __( 'ZIP', 'zoransmaleri-leads' ),
			'npl_source'   => __( 'Source', 'zoransmaleri-leads' ),
			'npl_status'   => __( 'Status', 'zoransmaleri-leads' ),
			'date'         => __( 'Received', 'zoransmaleri-leads' ),
		);
	}
);

add_action(
	'manage_npl_lead_posts_custom_column',
	function ( $column, $post_id ) {
		switch ( $column ) {
			case 'npl_phone':
				$phone = get_post_meta( $post_id, 'phone', true );
				printf( '<a href="tel:%s">%s</a>', esc_attr( preg_replace( '/\D/', '', $phone ) ), esc_html( $phone ) );
				break;
			case 'npl_service':
				echo esc_html( npl_label( 'service', get_post_meta( $post_id, 'service', true ) ) );
				break;
			case 'npl_timeline':
				echo esc_html( npl_label( 'timeline', get_post_meta( $post_id, 'timeline', true ) ) );
				break;
			case 'npl_zip':
				echo esc_html( get_post_meta( $post_id, 'zip', true ) );
				break;
			case 'npl_source':
				echo esc_html( npl_source_summary( $post_id ) );
				break;
			case 'npl_status':
				$statuses = npl_statuses();
				$status   = get_post_meta( $post_id, 'status', true ) ?: 'new';
				echo esc_html( $statuses[ $status ] ?? $status );
				break;
		}
	},
	10,
	2
);

/**
 * Short "where did this lead come from" label, e.g. "google / cpc" or "Direct".
 *
 * @param int $post_id Lead ID.
 * @return string
 */
function npl_source_summary( $post_id ) {
	$source = get_post_meta( $post_id, 'utm_source', true );
	$medium = get_post_meta( $post_id, 'utm_medium', true );
	if ( $source ) {
		return $medium ? "$source / $medium" : $source;
	}
	if ( get_post_meta( $post_id, 'gclid', true ) ) {
		return 'google / cpc (gclid)';
	}
	$ref = get_post_meta( $post_id, 'referrer', true );
	if ( $ref ) {
		$host = wp_parse_url( $ref, PHP_URL_HOST );
		if ( $host && wp_parse_url( home_url(), PHP_URL_HOST ) !== $host ) {
			return $host;
		}
	}
	return __( 'Direct', 'zoransmaleri-leads' );
}

/**
 * Details box on a single lead, plus the status dropdown.
 */
add_action(
	'add_meta_boxes_npl_lead',
	function () {
		add_meta_box( 'npl_details', __( 'What they told us', 'zoransmaleri-leads' ), 'npl_details_box', 'npl_lead', 'normal', 'high' );
		add_meta_box( 'npl_status', __( 'Status', 'zoransmaleri-leads' ), 'npl_status_box', 'npl_lead', 'side', 'high' );
	}
);

/**
 * Read-only table of everything the form captured.
 *
 * @param WP_Post $post Lead.
 */
function npl_details_box( $post ) {
	$rows = array(
		__( 'Name', 'zoransmaleri-leads' )     => get_post_meta( $post->ID, 'name', true ),
		__( 'Phone', 'zoransmaleri-leads' )    => get_post_meta( $post->ID, 'phone', true ),
		__( 'E-post', 'zoransmaleri-leads' )    => get_post_meta( $post->ID, 'email', true ),
		__( 'ZIP', 'zoransmaleri-leads' )      => get_post_meta( $post->ID, 'zip', true ),
		__( 'Needs', 'zoransmaleri-leads' )    => npl_label( 'service', get_post_meta( $post->ID, 'service', true ) ),
		__( 'When', 'zoransmaleri-leads' )     => npl_label( 'timeline', get_post_meta( $post->ID, 'timeline', true ) ),
		__( 'Source', 'zoransmaleri-leads' )   => npl_source_summary( $post->ID ),
	);
	foreach ( npl_tracking_fields() as $key ) {
		$value = get_post_meta( $post->ID, $key, true );
		if ( $value ) {
			$rows[ $key ] = $value;
		}
	}
	$rows[ __( 'Samtycke lämnat', 'zoransmaleri-leads' ) ] = get_post_meta( $post->ID, 'consent_at', true ) . ' — "' . get_post_meta( $post->ID, 'consent_text', true ) . '"';

	echo '<table class="widefat striped"><tbody>';
	foreach ( $rows as $label => $value ) {
		printf( '<tr><th style="width:160px">%s</th><td>%s</td></tr>', esc_html( $label ), esc_html( $value ) );
	}
	echo '</tbody></table>';
}

/**
 * Status dropdown.
 *
 * @param WP_Post $post Lead.
 */
function npl_status_box( $post ) {
	wp_nonce_field( 'npl_status', 'npl_status_nonce' );
	$current = get_post_meta( $post->ID, 'status', true ) ?: 'new';
	echo '<label class="screen-reader-text" for="npl-status">' . esc_html__( 'Status', 'zoransmaleri-leads' ) . '</label><select id="npl-status" name="npl_status" style="width:100%">';
	foreach ( npl_statuses() as $key => $label ) {
		printf( '<option value="%s"%s>%s</option>', esc_attr( $key ), selected( $current, $key, false ), esc_html( $label ) );
	}
	echo '</select>';
}

add_action(
	'save_post_npl_lead',
	function ( $post_id ) {
		if ( ! isset( $_POST['npl_status_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['npl_status_nonce'] ), 'npl_status' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$status = isset( $_POST['npl_status'] ) ? sanitize_key( $_POST['npl_status'] ) : 'new';
		if ( array_key_exists( $status, npl_statuses() ) ) {
			update_post_meta( $post_id, 'status', $status );
		}
	}
);

/**
 * Count of new leads next to the menu item.
 */
add_action(
	'admin_menu',
	function () {
		global $menu;
		$new = get_posts(
			array(
				'post_type'   => 'npl_lead',
				'numberposts' => 99,
				'fields'      => 'ids',
				'meta_query'  => array( array( 'key' => 'status', 'value' => 'new' ) ), // phpcs:ignore WordPress.DB.SlowDBQuery
			)
		);
		if ( ! $new ) {
			return;
		}
		foreach ( $menu as $i => $item ) {
			if ( 'edit.php?post_type=npl_lead' === $item[2] ) {
				$menu[ $i ][0] .= ' <span class="awaiting-mod"><span class="count">' . count( $new ) . '</span></span>'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
			}
		}
	},
	99
);
