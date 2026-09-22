<?php
/**
 * Title: Not found
 * Slug: zoransmaleri/not-found
 * Categories: zoransmaleri-sections
 * Description: 404 message with the two most useful next steps.
 */
$img = get_theme_file_uri( 'assets/img/' );
$url = function ( $path ) { return esc_url( home_url( $path ) ); };
?>
<!-- wp:heading {"level":1,"fontSize":"xx-large"} -->
<h1 class="wp-block-heading has-xx-large-font-size">Sidan finns inte</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Länken kan vara gammal. Ville du ha ett pris på taket är du ett klick bort.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"zo-track-cta"} -->
<div class="wp-block-button zo-track-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo $url( '/free-quote/' ); ?>">Boka besiktning</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo $url( '/' ); ?>">Till startsidan</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
