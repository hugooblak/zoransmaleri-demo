<?php
/**
 * Zorans Måleri theme functions.
 *
 * Kept short on purpose. Design lives in theme.json, markup in templates and patterns.
 * Lead capture lives in the "Zorans Måleri Leads" plugin, so leads survive a theme change.
 *
 * @package Zorans Måleri
 */

defined( 'ABSPATH' ) || exit;

/**
 * The subject company's details (name, phone, town, rating, reviews, photos).
 * Patterns read these through zo_lead() so one demo can be rebuilt for any firm.
 */
require_once __DIR__ . '/inc/lead.php';

/**
 * Load style.css (the few rules theme.json can't express) on the front end and in the editor.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'zoransmaleri', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
	}
);
add_action(
	'after_setup_theme',
	function () {
		add_editor_style( 'style.css' );
	}
);

/**
 * Preload the one font file so headings don't jump when it arrives.
 */
add_action(
	'wp_head',
	function () {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_theme_file_uri( 'assets/fonts/figtree-var.woff2' ) )
		);
	},
	1
);

/**
 * Remove WordPress's emoji script and styles. Browsers draw emoji on their own.
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Block styles the editor can pick from the Styles panel.
 */
add_action(
	'init',
	function () {
		register_block_style( 'core/paragraph', array( 'name' => 'eyebrow', 'label' => __( 'Eyebrow label', 'zoransmaleri' ) ) );
		register_block_style( 'core/list', array( 'name' => 'checklist', 'label' => __( 'Tick list', 'zoransmaleri' ) ) );

		register_block_pattern_category( 'zoransmaleri-sections', array( 'label' => __( 'Zorans Måleri sections', 'zoransmaleri' ) ) );
		register_block_pattern_category( 'zoransmaleri-pages', array( 'label' => __( 'Zorans Måleri page layouts', 'zoransmaleri' ) ) );
	}
);

/**
 * Basic meta description from the page excerpt.
 * Switches itself off when an SEO plugin is active, so there are never two.
 */
add_action(
	'wp_head',
	function () {
		if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' ) || defined( 'AIOSEO_VERSION' ) ) {
			return;
		}
		$description = '';
		if ( is_singular() ) {
			$description = get_the_excerpt();
		} elseif ( is_front_page() || is_home() ) {
			$description = get_bloginfo( 'description' );
		}
		if ( $description ) {
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( wp_strip_all_tags( $description ) ) );
		}
	},
	2
);

/**
 * Local business structured data on the home page (helps Google show address, hours and phone).
 * Values come from Settings → General → Site title, plus the constants below.
 * On a real project these would be a settings screen or the SEO plugin's local business module.
 */
add_action(
	'wp_head',
	function () {
		if ( ! is_front_page() ) {
			return;
		}
		$data = array(
			'@context'     => 'https://schema.org',
			'@type'        => 'RoofingContractor',
			'name'         => get_bloginfo( 'name' ),
			'url'          => home_url( '/' ),
			'telephone'    => '+1-555-010-0142',
			'priceRange'   => '$$',
			'address'      => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => '120 Example Street',
				'addressLocality' => 'Millbrook',
				'addressRegion'   => 'OR',
				'postalCode'      => '97000',
				'addressCountry'  => 'US',
			),
			'openingHours' => array( 'Mo-Fr 07:00-19:00', 'Sa 08:00-14:00' ),
			'areaServed'   => array( 'Millbrook', 'Ashford', 'Linden Hills', 'Cedar Bend' ),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}
);
