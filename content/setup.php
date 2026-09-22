<?php
/**
 * Demo site setup for Zorans Måleri.
 *
 * Creates the pages from the theme's patterns, imports the old site's articles through
 * migrate.php, builds the menu, sets the front page, and adds three sample leads so the
 * Leads inbox isn't empty. Safe to run again: it skips anything that already exists.
 *
 * Playground runs this automatically (see blueprint.json).
 * Local run:  wp eval-file content/setup.php
 *
 * @package Zorans Måleri
 */

defined( 'ABSPATH' ) || exit;

wp_set_current_user( 1 ); // Admin, so block markup is saved exactly as written.

/**
 * Pattern markup with nested pattern references expanded, so saved pages hold plain editable blocks.
 */
function nps_pattern( $slug ) {
	$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( $slug );
	if ( ! $pattern ) {
		nps_log( "Missing pattern: $slug" );
		return '';
	}
	return preg_replace_callback(
		'#<!-- wp:pattern \{"slug":"([^"]+)"\} /-->#',
		function ( $m ) {
			return nps_pattern( $m[1] );
		},
		$pattern['content']
	);
}

function nps_log( $msg ) {
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		WP_CLI::log( $msg );
	}
}

function nps_page( $slug, $title, $content, $excerpt, $template = '' ) {
	$existing = get_page_by_path( $slug );
	if ( $existing ) {
		return $existing->ID;
	}
	$id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_content' => $content,
			'post_excerpt' => $excerpt,
			'meta_input'   => $template ? array( '_wp_page_template' => $template ) : array(),
		)
	);
	nps_log( "Page: $title" );
	return $id;
}

/* --------------------------------------------------------------------------
 * Site settings
 * ----------------------------------------------------------------------- */
update_option( 'blogname', 'Zorans Måleri' );
update_option( 'blogdescription', 'Takomläggning, taktvätt och takreparation i norra Stockholm. Fast pris skriftligt inom två arbetsdagar.' );
update_option( 'timezone_string', 'America/Los_Angeles' );
update_option( 'date_format', 'F j, Y' );
update_option( 'posts_per_page', 9 );

// Remove WordPress's sample content.
foreach ( array( 'hello-world' => 'post', 'sample-page' => 'page' ) as $slug => $type ) {
	$sample = get_page_by_path( $slug, OBJECT, $type );
	if ( $sample ) {
		wp_delete_post( $sample->ID, true );
	}
}
$privacy_draft = get_page_by_path( 'privacy-policy' );
if ( $privacy_draft && 'draft' === $privacy_draft->post_status ) {
	wp_delete_post( $privacy_draft->ID, true );
}

/* --------------------------------------------------------------------------
 * Pages
 * ----------------------------------------------------------------------- */
$home = nps_page(
	'home',
	'Takläggare i Stockholm',
	nps_pattern( 'zoransmaleri/page-home' ),
	'Takomläggning och takvård i Stockholm, Nacka, Täby och Huddinge. Fast pris skriftligt och 10 års garanti.',
	'page-landing'
);

nps_page(
	'takomlaggning',
	'Takomläggning',
	nps_pattern( 'zoransmaleri/page-replacement' ),
	'Komplett takomläggning till ett fast pris skriftligt, klart på 2–5 dagar, med 10 års garanti på arbetet.',
	'page-landing'
);

nps_page(
	'taktvatt',
	'Taktvätt och impregnering',
	nps_pattern( 'zoransmaleri/page-storm' ),
	'Vi tvättar bort mossa och alger och impregnerar taket. Förlänger livslängden med 10–15 år.',
	'page-landing'
);

$quote = nps_page(
	'offert',
	'Boka besiktning',
	nps_pattern( 'zoransmaleri/quote-funnel' ),
	'Få ett fast pris på ditt tak skriftligt inom två arbetsdagar. Fyra snabba frågor.',
	'page-focus'
);

$thanks = nps_page(
	'tack',
	'Tack',
	nps_pattern( 'zoransmaleri/thank-you' ),
	'Vi har tagit emot din förfrågan.',
	'page-focus'
);

$about_content = <<<'HTML'
<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Zorans Måleri är ett familjeföretag från Sollentuna. Vi startade 1996 med en bil och en regel vi fortfarande håller oss till: priset vi skriver ner är priset du betalar.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>I dag är vi tolv montörer och varje tak läggs av våra egna killar, aldrig av inhyrda underentreprenörer. Anders går igenom varje färdigt jobb tillsammans med kunden.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">What you can count on</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-checklist"} -->
<ul class="wp-block-list is-style-checklist"><!-- wp:list-item -->
<li>F-skatt, ansvarsförsäkring och kollektivavtal (org.nr 556000-0000, platshållare)</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Fast pris skriftligt, ingen handpenning förrän materialet står på tomten</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>10 års garanti på arbetet</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Kontor och lager på Exempelgatan 12 i Sollentuna, öppet vardagar 07–18</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->
HTML;
nps_page( 'om-oss', 'Om Zorans Måleri', $about_content . "\n\n" . nps_pattern( 'zoransmaleri/cta-inline' ), 'Familjeägt takföretag i norra Stockholm sedan 1996. Egna montörer, fasta priser skriftligt och 10 års garanti.' );

// FAQ page: the same objection-handling questions, grouped by topic.
$faq_groups = array(
	'Offert och pris' => array(
		array( 'Är besiktningen verkligen gratis?', 'Ja. Besiktningen och den skriftliga offerten kostar ingenting, och du binder dig inte vid något.' ),
		array( 'Varför tar ni betalt först när materialet kommer?', 'För att du aldrig ska betala för ett jobb som inte har börjat. Materialet beställs till just ditt tak, och då ber vi om den första delbetalningen.' ),
		array( 'Hur fungerar ROT-avdraget?', 'Vi drar av 30 % av arbetskostnaden direkt på fakturan, upp till 50 000 kr per person och år, och sköter ansökan mot Skatteverket åt dig.' ),
	),
	'Själva jobbet'           => array(
		array( 'Hur lång tid tar en takomläggning?', 'Ett normalt villatak tar 2–5 dagar. Stora eller branta tak kan ta längre.' ),
		array( 'Vad händer om det regnar?', 'Vi täcker taket varje kväll och river aldrig mer än vi hinner lägga igen samma dag. Vid ihållande regn pausar vi hellre.' ),
		array( 'Städar ni efter er?', 'Ja. Vi skyddar rabatter, forslar bort allt gammalt material och går igenom tomten efteråt så att inga spikar ligger kvar.' ),
	),
	'Garanti' => array(
		array( 'Täcker försäkringen ett nytt tak?', 'Bara om skadan kommit plötsligt, till exempel av en storm. Slitage täcks aldrig. Vi dokumenterar med foton, men kan aldrig lova hur bolaget bedömer.' ),
		array( 'Vad täcker garantin?', 'Läckage eller fel som beror på hur vi lagt taket, i 10 år. Materialet täcks av tillverkarens egen garanti.' ),
	),
);
$faq_content = "<!-- wp:paragraph {\"fontSize\":\"large\"} -->\n<p class=\"has-large-font-size\">Raka svar på det villaägare brukar fråga. Hittar du inte ditt? Ring <a href=\"tel:+46701234567\">070-123 45 67</a>.</p>\n<!-- /wp:paragraph -->";
foreach ( $faq_groups as $group => $faqs ) {
	$faq_content .= "\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( $group ) . "</h2>\n<!-- /wp:heading -->";
	foreach ( $faqs as $f ) {
		$faq_content .= "\n\n<!-- wp:details -->\n<details class=\"wp-block-details\"><summary>" . esc_html( $f[0] ) . "</summary><!-- wp:paragraph -->\n<p>" . esc_html( $f[1] ) . "</p>\n<!-- /wp:paragraph --></details>\n<!-- /wp:details -->";
	}
}
nps_page( 'vanliga-fragor', 'Vanliga frågor', $faq_content . "\n\n" . nps_pattern( 'zoransmaleri/cta-inline' ), 'Svar om offert, pris, ROT-avdrag, hur lång tid en takomläggning tar och vad garantin täcker.' );

$privacy_content = <<<'HTML'
<!-- wp:paragraph {"backgroundColor":"sand"} -->
<p class="has-sand-background-color has-background"><strong>Sample policy for a demo site.</strong> A real policy must be written for the business and checked by a lawyer.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">What we collect</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>När du begär en offert sparar vi ditt postnummer, vad du behöver hjälp med, när du vill ha det gjort, ditt förnamn, telefonnummer och e-post. Vi noterar också vilken sida eller annons du kom ifrån, så att vi vet vilken marknadsföring som fungerar.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">How we use it</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Only to arrange your inspection and send your quote. We keep a record of the consent you gave when you sent the form. We never sell or share your details.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Your choices</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Hör av dig när som helst så raderar vi dina uppgifter. Du har rätt att få ut, rätta eller radera det vi sparat om dig.</p>
<!-- /wp:paragraph -->
HTML;
$privacy = nps_page( 'integritetspolicy', 'Integritetspolicy', $privacy_content, 'Så använder Zorans Måleri uppgifterna du skickar med offertformuläret.' );
update_option( 'wp_page_for_privacy_policy', $privacy );

$guides = nps_page( 'takguider', 'Takguider', '', 'Raka svar på det villaägare brukar undra över taket.' );

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home );
update_option( 'page_for_posts', $guides );
update_option( 'npl_quote_page', $quote );
update_option( 'npl_thank_you_page', $thanks );

/* --------------------------------------------------------------------------
 * Old site articles → posts (see migrate.php)
 * ----------------------------------------------------------------------- */
require __DIR__ . '/migrate.php';
$legacy_dir = __DIR__ . '/legacy-export';
if ( is_dir( $legacy_dir ) ) {
	npm_import_all( $legacy_dir, __DIR__ );
	nps_log( 'Imported legacy articles.' );
}

/* --------------------------------------------------------------------------
 * Main menu (Appearance → Editor → Navigation)
 * ----------------------------------------------------------------------- */
// Looked up by title: WordPress sometimes auto-creates a fallback "Navigation" menu first.
if ( ! get_posts( array( 'post_type' => 'wp_navigation', 'title' => 'Main menu', 'numberposts' => 1, 'post_status' => 'publish' ) ) ) {
	$links = array(
		array( 'Takomläggning', '/takomlaggning/', 'page' ),
		array( 'Taktvätt', '/taktvatt/', 'page' ),
		array( 'ROT-avdrag', '/#rot', 'custom' ),
		array( 'Takguider', '/takguider/', 'page' ),
		array( 'Vanliga frågor', '/vanliga-fragor/', 'page' ),
	);
	$nav = '';
	foreach ( $links as $l ) {
		$attrs = array(
			'label' => $l[0],
			'url'   => home_url( $l[1] ),
			'kind'  => 'page' === $l[2] ? 'post-type' : 'custom',
		);
		if ( 'page' === $l[2] ) {
			$page         = get_page_by_path( trim( $l[1], '/' ) );
			$attrs['id']  = $page ? $page->ID : 0;
			$attrs['type'] = 'page';
		}
		$nav .= '<!-- wp:navigation-link ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . ' /-->';
	}
	wp_insert_post(
		array(
			'post_type'    => 'wp_navigation',
			'post_status'  => 'publish',
			'post_title'   => 'Main menu',
			'post_content' => $nav,
		)
	);
	nps_log( 'Menu created.' );
}

/* --------------------------------------------------------------------------
 * Three sample leads, so the inbox shows what real ones look like.
 * ----------------------------------------------------------------------- */
if ( post_type_exists( 'npl_lead' ) && ! get_posts( array( 'post_type' => 'npl_lead', 'numberposts' => 1 ) ) ) {
	$samples = array(
		array( 'Exempel A', '070-000 00 01', 'exempel-a@exempel.se', '191 62', 'storm', 'asap', 'contacted', array( 'utm_source' => 'google', 'utm_medium' => 'cpc', 'utm_campaign' => 'hail-spring', 'gclid' => 'EXAMPLE-GCLID' ), '-2 days' ),
		array( 'Sample lead B', '(555) 010-0183', 'sample-b@example.com', '97012', 'replacement', '1-3', 'new', array( 'utm_source' => 'facebook', 'utm_medium' => 'paid_social', 'utm_campaign' => 'financing' ), '-5 hours' ),
		array( 'Sample lead C', '(555) 010-0195', 'sample-c@example.com', '97003', 'inspection', 'research', 'new', array( 'referrer' => 'https://www.google.com/' ), '-40 minutes' ),
	);
	foreach ( $samples as $s ) {
		wp_insert_post(
			array(
				'post_type'   => 'npl_lead',
				'post_status' => 'publish',
				'post_title'  => $s[0] . ' · ' . npl_label( 'service', $s[4] ),
				'post_date'   => wp_date( 'Y-m-d H:i:s', strtotime( $s[8] ) ),
				'meta_input'  => array_merge(
					array(
						'name'         => $s[0],
						'phone'        => $s[1],
						'email'        => $s[2],
						'zip'          => $s[3],
						'service'      => $s[4],
						'timeline'     => $s[5],
						'status'       => $s[6],
						'landing_page' => home_url( '/' ),
						'consent_at'   => wp_date( 'Y-m-d H:i:s', strtotime( $s[8] ) ),
						'consent_text' => npl_consent_text(),
					),
					$s[7]
				),
			)
		);
	}
	nps_log( 'Sample leads added.' );
}

// Pretty links (/free-quote/ instead of ?page_id=5). Flushed last, after every page exists.
// set_permalink_structure() also updates the rules WordPress already loaded for this request.
// Only saving the option would rebuild the old rules, and blog posts would return "not found".
global $wp_rewrite;
$wp_rewrite->set_permalink_structure( '/%postname%/' );
flush_rewrite_rules( false );

nps_log( 'Zorans Måleri demo ready.' );
