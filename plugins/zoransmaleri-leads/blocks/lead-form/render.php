<?php
/**
 * Offertformulär markup (server-rendered, so it works without JavaScript).
 *
 * Without JS: all four questions show as one normal form.
 * With JS (view.js): one question at a time, progress bar, back button, instant error messages.
 *
 * @var array $attributes Block attributes.
 * @package Zorans MåleriLeads
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- reading prefill values from the URL only.
$npl_variant = ( $attributes['variant'] ?? 'full' ) === 'start' ? 'start' : 'full';
$npl_state   = npl_current_state();
$npl_errors  = $npl_state['errors'];
$npl_values  = $npl_state['values'];
$npl_choices = npl_choices();

// Prefill from the URL: ?zip=123 45 (from the short form) and ?service=... (from service pages).
if ( empty( $npl_values['zip'] ) && isset( $_GET['zip'] ) ) {
	$npl_values['zip'] = substr( preg_replace( '/\D/', '', wp_unslash( $_GET['zip'] ) ), 0, 5 );
}
if ( empty( $npl_values['service'] ) && isset( $_GET['service'] ) ) {
	$npl_values['service'] = sanitize_key( $_GET['service'] );
}
// phpcs:enable

$npl_val = function ( $key ) use ( $npl_values ) {
	return isset( $npl_values[ $key ] ) ? (string) $npl_values[ $key ] : '';
};

/**
 * Error message element for a field, linked to it with aria-describedby.
 */
$npl_error = function ( $key, $uid ) use ( $npl_errors ) {
	$msg = $npl_errors[ $key ] ?? '';
	printf(
		'<p class="npl-error" id="%1$s-%2$s-err"%3$s>%4$s</p>',
		esc_attr( $uid ),
		esc_attr( $key ),
		$msg ? '' : ' hidden',
		esc_html( $msg )
	);
};

// Unique IDs, so two forms on one page never share label targets.
$GLOBALS['npl_form_count'] = ( $GLOBALS['npl_form_count'] ?? 0 ) + 1;
$npl_uid                   = 'npl' . $GLOBALS['npl_form_count'];

$npl_wrapper = get_block_wrapper_attributes(
	array(
		'class' => 'npl-form npl-variant-' . $npl_variant,
	)
);

/* ----------------------------------------------------------------------------
 * Variant "start": ZIP only. Sends people to the full form with the ZIP filled in.
 * One easy first question gets people moving; finishing what you started is a strong pull.
 * ------------------------------------------------------------------------- */
if ( 'start' === $npl_variant ) :
	$npl_quote_page = (int) get_option( 'npl_quote_page' );
	$npl_action     = $npl_quote_page ? get_permalink( $npl_quote_page ) : home_url( '/free-quote/' );
	$npl_button     = ! empty( $attributes['buttonText'] ) ? $attributes['buttonText'] : __( 'Räkna på mitt tak', 'zoransmaleri-leads' );
	?>
	<div <?php echo $npl_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
		<form class="npl-start" method="get" action="<?php echo esc_url( $npl_action ); ?>" novalidate data-npl-start>
			<label class="npl-label" for="<?php echo esc_attr( $npl_uid ); ?>-zip"><?php esc_html_e( 'Ditt postnummer', 'zoransmaleri-leads' ); ?></label>
			<div class="npl-inline">
				<input class="npl-input" id="<?php echo esc_attr( $npl_uid ); ?>-zip" name="zip" type="text" inputmode="numeric" autocomplete="postal-code" pattern="[0-9]{3} ?[0-9]{2}" maxlength="6" required placeholder="t.ex. 123 45" aria-describedby="<?php echo esc_attr( $npl_uid ); ?>-zip-err" value="<?php echo esc_attr( $npl_val( 'zip' ) ); ?>">
				<button class="npl-btn wp-element-button" type="submit"><?php echo esc_html( $npl_button ); ?> <span aria-hidden="true">→</span></button>
			</div>
			<?php $npl_error( 'zip', $npl_uid ); ?>
			<p class="npl-fineprint"><?php esc_html_e( 'Steg 1 av 4 · dina uppgifter lämnas aldrig vidare', 'zoransmaleri-leads' ); ?></p>
		</form>
	</div>
	<?php
	return;
endif;

/* ----------------------------------------------------------------------------
 * Variant "full": the 4-step form.
 * ------------------------------------------------------------------------- */
// Which step to open first: the first one with an error, or step 2 if a valid ZIP came from the short form.
$npl_field_step = array( 'zip' => 1, 'service' => 2, 'timeline' => 3, 'name' => 4, 'phone' => 4, 'email' => 4 );
$npl_start      = 1;
if ( $npl_errors ) {
	$npl_start = min( array_map( fn( $k ) => $npl_field_step[ $k ] ?? 1, array_keys( $npl_errors ) ) );
} elseif ( preg_match( '/^\d{3} ?\d{2}$/', $npl_val( 'zip' ) ) ) {
	$npl_start = 2;
}

$npl_steps = array(
	1 => __( 'Var ligger huset?', 'zoransmaleri-leads' ),
	2 => __( 'Vad behöver du hjälp med?', 'zoransmaleri-leads' ),
	3 => __( 'När vill du ha det gjort?', 'zoransmaleri-leads' ),
	4 => __( 'Vart skickar vi offerten?', 'zoransmaleri-leads' ),
);
?>
<div <?php echo $npl_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?> id="npl-form">
	<form class="npl-steps" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate data-npl-steps data-total="4" data-start="<?php echo (int) $npl_start; ?>">
		<input type="hidden" name="action" value="npl_submit">
		<input type="hidden" name="npl_token" value="<?php echo esc_attr( npl_form_token() ); ?>">
		<?php foreach ( npl_tracking_fields() as $npl_field ) : ?>
			<input type="hidden" name="<?php echo esc_attr( $npl_field ); ?>" value="" data-npl-track="<?php echo esc_attr( $npl_field ); ?>">
		<?php endforeach; ?>

		<?php // Honeypot: hidden from people and screen readers. Bots fill every field they find. ?>
		<div class="npl-hp" aria-hidden="true">
			<label for="<?php echo esc_attr( $npl_uid ); ?>-hp">Company website</label>
			<input id="<?php echo esc_attr( $npl_uid ); ?>-hp" type="text" name="company_website" tabindex="-1" autocomplete="off">
		</div>

		<?php if ( $npl_errors ) : ?>
			<div class="npl-summary" role="alert" tabindex="-1" data-npl-summary>
				<p><strong><?php esc_html_e( 'Kolla de här svaren:', 'zoransmaleri-leads' ); ?></strong></p>
				<ul>
					<?php foreach ( $npl_errors as $npl_key => $npl_msg ) : ?>
						<li><a href="#<?php echo esc_attr( $npl_uid . '-' . $npl_key ); ?>"><?php echo esc_html( $npl_msg ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<div class="npl-progress" data-npl-progress hidden>
			<p class="npl-progress-text" aria-live="polite" data-npl-progress-text><?php echo esc_html( sprintf( /* translators: %d: step number */ __( 'Steg %d av 4', 'zoransmaleri-leads' ), $npl_start ) ); ?></p>
			<div class="npl-bar" aria-hidden="true"><span data-npl-bar style="width:<?php echo (int) $npl_start * 25; ?>%"></span></div>
		</div>

		<?php /* Step 1: ZIP */ ?>
		<fieldset class="npl-step" data-step="1" data-name="zip" tabindex="-1">
			<legend class="npl-legend"><?php echo esc_html( $npl_steps[1] ); ?></legend>
			<label class="npl-label" for="<?php echo esc_attr( $npl_uid ); ?>-zip"><?php esc_html_e( 'Postnummer', 'zoransmaleri-leads' ); ?></label>
			<input class="npl-input npl-input-short" id="<?php echo esc_attr( $npl_uid ); ?>-zip" name="zip" type="text" inputmode="numeric" autocomplete="postal-code" pattern="[0-9]{3} ?[0-9]{2}" maxlength="6" required aria-describedby="<?php echo esc_attr( $npl_uid ); ?>-zip-err"<?php echo isset( $npl_errors['zip'] ) ? ' aria-invalid="true"' : ''; ?> value="<?php echo esc_attr( $npl_val( 'zip' ) ); ?>">
			<?php $npl_error( 'zip', $npl_uid ); ?>
		</fieldset>

		<?php
		/* Steps 2 and 3: big tap-friendly choice cards (real radio buttons underneath). */
		foreach ( array( 2 => 'service', 3 => 'timeline' ) as $npl_n => $npl_field ) :
			?>
			<fieldset class="npl-step" data-step="<?php echo (int) $npl_n; ?>" data-name="<?php echo esc_attr( $npl_field ); ?>" tabindex="-1" id="<?php echo esc_attr( $npl_uid . '-' . $npl_field ); ?>" aria-describedby="<?php echo esc_attr( $npl_uid . '-' . $npl_field ); ?>-err"<?php echo isset( $npl_errors[ $npl_field ] ) ? ' aria-invalid="true"' : ''; ?>>
				<legend class="npl-legend"><?php echo esc_html( $npl_steps[ $npl_n ] ); ?></legend>
				<p class="npl-hint npl-auto-hint"><?php esc_html_e( 'När du väljer går du vidare till nästa fråga.', 'zoransmaleri-leads' ); ?></p>
				<div class="npl-choices">
					<?php foreach ( $npl_choices[ $npl_field ] as $npl_value => $npl_label ) : ?>
						<label class="npl-choice">
							<input type="radio" name="<?php echo esc_attr( $npl_field ); ?>" value="<?php echo esc_attr( $npl_value ); ?>" required<?php checked( $npl_val( $npl_field ), $npl_value ); ?>>
							<span><?php echo esc_html( $npl_label ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
				<?php $npl_error( $npl_field, $npl_uid ); ?>
			</fieldset>
		<?php endforeach; ?>

		<?php /* Step 4: contact details. Only three fields, each with a reason. */ ?>
		<fieldset class="npl-step" data-step="4" data-name="contact" tabindex="-1">
			<legend class="npl-legend"><?php echo esc_html( $npl_steps[4] ); ?></legend>
			<?php
			$npl_contact = array(
				'name'  => array( __( 'Förnamn', 'zoransmaleri-leads' ), 'text', 'given-name', '' ),
				'phone' => array( __( 'Mobilnummer', 'zoransmaleri-leads' ), 'tel', 'tel-national', __( 'För att boka tid för besiktningen.', 'zoransmaleri-leads' ) ),
				'email' => array( __( 'E-post', 'zoransmaleri-leads' ), 'email', 'email', __( 'Hit skickar vi den skriftliga offerten.', 'zoransmaleri-leads' ) ),
			);
			foreach ( $npl_contact as $npl_key => $npl_f ) :
				$npl_describe = $npl_uid . '-' . $npl_key . '-err' . ( $npl_f[3] ? ' ' . $npl_uid . '-' . $npl_key . '-hint' : '' );
				?>
				<div class="npl-field">
					<label class="npl-label" for="<?php echo esc_attr( $npl_uid . '-' . $npl_key ); ?>"><?php echo esc_html( $npl_f[0] ); ?></label>
					<?php if ( $npl_f[3] ) : ?>
						<p class="npl-hint" id="<?php echo esc_attr( $npl_uid . '-' . $npl_key ); ?>-hint"><?php echo esc_html( $npl_f[3] ); ?></p>
					<?php endif; ?>
					<input class="npl-input" id="<?php echo esc_attr( $npl_uid . '-' . $npl_key ); ?>" name="<?php echo esc_attr( $npl_key ); ?>" type="<?php echo esc_attr( $npl_f[1] ); ?>" autocomplete="<?php echo esc_attr( $npl_f[2] ); ?>" required aria-describedby="<?php echo esc_attr( $npl_describe ); ?>"<?php echo isset( $npl_errors[ $npl_key ] ) ? ' aria-invalid="true"' : ''; ?> value="<?php echo esc_attr( $npl_val( $npl_key ) ); ?>"<?php echo 'phone' === $npl_key ? ' inputmode="tel"' : ''; ?>>
					<?php $npl_error( $npl_key, $npl_uid ); ?>
				</div>
			<?php endforeach; ?>
		</fieldset>

		<div class="npl-nav">
			<button type="button" class="npl-back" data-npl-back hidden><span aria-hidden="true">←</span> <?php esc_html_e( 'Back', 'zoransmaleri-leads' ); ?></button>
			<button type="button" class="npl-btn wp-element-button" data-npl-next hidden><?php esc_html_e( 'Next', 'zoransmaleri-leads' ); ?> <span aria-hidden="true">→</span></button>
			<button type="submit" class="npl-btn wp-element-button" data-npl-submit><?php esc_html_e( 'Skicka min förfrågan', 'zoransmaleri-leads' ); ?></button>
		</div>
		<p class="npl-fineprint" data-npl-consent><?php echo esc_html( npl_consent_text() ); ?></p>
	</form>
</div>
