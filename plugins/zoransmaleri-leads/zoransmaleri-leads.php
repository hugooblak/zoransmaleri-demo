<?php
/**
 * Plugin Name: Zorans Måleri Leads
 * Description: Multi-step quote form block, lead inbox in the admin, CSV export, spam protection and analytics events. Built for the Zorans Måleri lead generation demo.
 * Version: 1.0.0
 * Requires at least: 6.6
 * Requires PHP: 7.4
 * Author: Hugo Oblak
 * License: GPL-2.0-or-later
 * Text Domain: zoransmaleri-leads
 *
 * Why a plugin and not theme code: leads are business data. They must survive a redesign.
 *
 * @package Zorans MåleriLeads
 */

defined( 'ABSPATH' ) || exit;

define( 'NPL_VERSION', '1.0.0' );
define( 'NPL_DIR', __DIR__ );
define( 'NPL_URL', plugin_dir_url( __FILE__ ) );

require NPL_DIR . '/includes/fields.php';       // One place that lists the form's questions and answers.
require NPL_DIR . '/includes/lead-post-type.php'; // The "Leads" inbox in the admin.
require NPL_DIR . '/includes/submit.php';       // Validates and saves a submitted form.
require NPL_DIR . '/includes/export.php';       // "Export CSV" button on the Leads screen.
require NPL_DIR . '/includes/tracking.php';     // Analytics events (dataLayer) for Google Tag Manager / GA4.
require NPL_DIR . '/includes/privacy.php';      // Export or erase a person's leads with WordPress's privacy tools.

add_action(
	'init',
	function () {
		register_block_type( NPL_DIR . '/blocks/lead-form' );
	}
);
