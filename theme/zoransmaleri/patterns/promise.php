<?php
/**
 * Title: Promise and financing
 * Slug: zoransmaleri/promise
 * Categories: zoransmaleri-sections
 * Description: Dark section with the written guarantee and the financing offer, to remove the two biggest worries.
 */
$img = get_theme_file_uri( 'assets/img/' );
$url = function ( $path ) { return esc_url( home_url( $path ) ); };
?>
<!-- wp:group {"align":"full","className":"zo-sec-promise is-style-section-dark","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group alignfull zo-sec-promise is-style-section-dark" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">Our promise</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Ändras priset står vi för det.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Offerten du skriver under är den du betalar. Hittar vi rötskadad råspont visar vi foton och priset per kvadratmeter, som redan står i offerten. Inga överraskningar på slutfakturan.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-checklist"} -->
<ul class="wp-block-list is-style-checklist"><!-- wp:list-item -->
<li>Ingen handpenning förrän materialet står på tomten</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>10 års garanti på arbetet, skriftligt</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Vi går igenom det färdiga jobbet tillsammans</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">Financing</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">ROT-avdraget dras direkt.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Vi drar av 30 % av arbetskostnaden på fakturan, upp till 50 000 kr per person och år, och sköter ansökan mot Skatteverket åt dig.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><span class="zo-sample">Reglerna kommer från Skatteverket.</span></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"zo-track-cta"} -->
<div class="wp-block-button zo-track-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo $url( '/free-quote/' ); ?>">Räkna på mitt tak</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
