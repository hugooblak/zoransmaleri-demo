<?php
/**
 * Title: Avslutande uppmaning
 * Slug: zoransmaleri/final-cta
 * Categories: zoransmaleri-sections
 * Description: Sista blocket: ring en namngiven person, eller boka besiktning. Namnet är det som gör skillnad.
 */
$url = function ( $path ) { return esc_url( home_url( $path ) ); };
?>
<!-- wp:group {"align":"full","className":"zo-sec-final","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"backgroundColor":"ink","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group alignfull zo-sec-final has-ink-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"58%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%"><!-- wp:heading {"textColor":"base","fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-base-color has-text-color has-xx-large-font-size">Undrar du vad ditt tak kostar?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"sand","fontSize":"large"} -->
<p class="has-sand-color has-text-color has-large-font-size">Vi svarar oftast direkt, även på kvällar. Det kostar ingenting att fråga.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"42%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:42%"><!-- wp:buttons {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"accent","textColor":"base","width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-base-color has-accent-background-color has-text-color has-background wp-element-button" href="tel:<?php echo esc_attr( zo_lead( 'telefon_tel' ) ); ?>"><?php echo esc_html( zo_ring_label() ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline","textColor":"base","width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link has-base-color has-text-color wp-element-button" href="<?php echo $url( '/offert' ); ?>">Boka kostnadsfri besiktning</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"textColor":"sand","fontSize":"small","align":"center"} -->
<p class="has-text-align-center has-sand-color has-text-color has-small-font-size">Vardagar 07–18 · helger efter överenskommelse</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
