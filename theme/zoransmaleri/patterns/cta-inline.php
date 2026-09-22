<?php
/**
 * Title: Inline call to action
 * Slug: zoransmaleri/cta-inline
 * Categories: zoransmaleri-sections
 * Description: Small box at the end of guides, linking to the quote form.
 */
$img = get_theme_file_uri( 'assets/img/' );
$url = function ( $path ) { return esc_url( home_url( $path ) ); };
?>
<!-- wp:group {"className":"is-style-card zo-sec-inline","style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"backgroundColor":"pine-light","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card zo-sec-inline has-pine-light-background-color has-background" style="margin-top:var(--wp--preset--spacing--60)"><!-- wp:heading {"level":2,"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size">Osäker på vad ditt tak behöver?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Kostnadsfri besiktning och ett fast pris skriftligt inom två arbetsdagar.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"zo-track-cta"} -->
<div class="wp-block-button zo-track-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo $url( '/free-quote/' ); ?>">Boka kostnadsfri besiktning</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
