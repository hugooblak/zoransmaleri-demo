<?php
/**
 * One place for everything that differs between one prospect's demo and the next.
 *
 * The patterns never hard-code a company name, phone number, town, rating, review
 * or photo. They call zo_lead() instead. To build a demo for a real firm, the
 * generator writes inc/lead-data.php with that firm's own details scraped from
 * their Google listing, and every page picks them up.
 *
 * With no lead-data.php present the defaults below render the fictional Zorans Måleri.
 *
 * @package Zorans Måleri
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read one field of the demo's subject company.
 *
 * @param string $key     Field name.
 * @param mixed  $default Returned when the field is missing.
 * @return mixed
 */
function zo_lead( $key, $default = '' ) {
	static $data = null;

	if ( null === $data ) {
		$defaults = array(
			'foretag'    => 'Zorans Måleri',
			'kontakt'    => '',   // only set when the owner's name is actually known
			'telefon'    => '070-123 45 67',
			'telefon_tel'=> '+46701234567',
			'ort'        => 'Sollentuna',
			'orter'      => array( 'Sollentuna', 'Järfälla', 'Täby', 'Sigtuna', 'Upplands-Bro', 'Solna', 'Danderyd', 'Sundbyberg', 'Lidingö', 'Vallentuna', 'Österåker' ),
			'betyg'      => '4,9',
			'omdomen'    => 158,
			'google_url' => '',
			'adress'     => 'Exempelgatan 12, 191 62 Sollentuna',
			'ar_i_branschen' => 30,
			'antal_tak'  => '4 000+',
			// Each review: text, author, when. Filled from the firm's Google profile.
			'omdomen_lista' => array(
				array( 'De la om hela taket på två dagar, städade efter sig och slutnotan var precis den som stod i offerten. Svårt att klaga.', 'Julia A.', 'för 2 veckor sedan' ),
				array( 'Vi hade läckage vid skorstenen. De kom ut dagen efter jag ringde, hittade felet och lagade det direkt. Väldigt trevliga killar.', 'Stefan A.', 'för 1 månad sedan' ),
				array( 'Noggranna, effektiva och proffsiga. Fick ett fast pris innan de började och det höll hela vägen.', 'Maria L.', 'för 2 månader sedan' ),
			),
			// Hero and service-card images, relative to assets/img/.
			'bild_hero'  => 'hero-placeholder.svg',
			'bilder'     => array( 'svc-omlaggning.svg', 'svc-taktvatt.svg', 'svc-reparation.svg' ),
			// True once real scraped content replaced the defaults; hides "platshållare" labels.
			'ar_riktig'  => false,
		);

		$file = __DIR__ . '/lead-data.php';
		$data = is_readable( $file ) ? array_merge( $defaults, (array) require $file ) : $defaults;
	}

	return array_key_exists( $key, $data ) && '' !== $data[ $key ] ? $data[ $key ] : $default;
}

/**
 * Prints "(platshållare)" only while the demo is still running on invented content.
 *
 * @return string
 */
function zo_sample_note() {
	return zo_lead( 'ar_riktig' ) ? '' : ' <span class="zo-sample">(platshållare)</span>';
}

/**
 * URL for one of the demo's images, whether shipped with the theme or downloaded
 * into assets/img/lead/ by the generator.
 *
 * @param string $name File name.
 * @return string
 */
function zo_lead_img( $name ) {
	$lead = get_theme_file_path( 'assets/img/lead/' . $name );
	return is_readable( $lead )
		? get_theme_file_uri( 'assets/img/lead/' . $name )
		: get_theme_file_uri( 'assets/img/' . $name );
}

/**
 * Nth image for the service cards: the lead's own photo when the generator
 * downloaded one, otherwise the drawn placeholder that ships with the theme.
 *
 * @param int $i Zero-based index.
 * @return string File name for zo_lead_img().
 */
function zo_lead_bild( $i ) {
	$bilder = (array) zo_lead( 'bilder', array() );
	return isset( $bilder[ $i ] ) ? $bilder[ $i ] : '';
}

/**
 * Call-to-action label. "Ring Anders · 070…" only when we genuinely know who
 * answers; otherwise just the number, which is what a stranger expects anyway.
 *
 * @return string
 */
function zo_ring_label() {
	$namn = zo_lead( 'kontakt' );
	return $namn ? sprintf( 'Ring %s · %s', $namn, zo_lead( 'telefon' ) ) : 'Ring ' . zo_lead( 'telefon' );
}
