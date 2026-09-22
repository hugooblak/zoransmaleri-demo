<?php
/**
 * Title: ROT-avdrag
 * Slug: zoransmaleri/price-guide
 * Categories: zoransmaleri-sections
 * Description: Förklarar ROT-avdraget med ett räkneexempel. Reglerna är Skatteverkets, siffrorna i exemplet är påhittade och märkta som exempel.
 */
$url = function ( $path ) { return esc_url( home_url( $path ) ); };
?>
<!-- wp:group {"align":"full","className":"zo-sec-rot","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"backgroundColor":"sand","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group alignfull zo-sec-rot has-sand-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"align":"wide","verticalAlignment":"top","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-top"><!-- wp:column {"verticalAlignment":"top","width":"46%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:46%"><!-- wp:heading {"fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-xx-large-font-size">Du betalar 30&nbsp;% mindre för arbetet än du tror</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"ink-soft","fontSize":"large"} -->
<p class="has-ink-soft-color has-text-color has-large-font-size">ROT-avdraget drar av 30&nbsp;% av arbetskostnaden, upp till 50&nbsp;000 kr per person och år. Vi drar av det direkt på fakturan — du behöver inte ansöka om något och du ligger aldrig ute med pengarna.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-checklist"} -->
<ul class="wp-block-list is-style-checklist"><!-- wp:list-item -->
<li>Äger ni huset tillsammans får <strong>ni 50 000 kr var</strong></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Gäller arbetet, <strong>inte materialet</strong> — det står uppdelat i vår offert</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Vi sköter ansökan mot Skatteverket åt dig</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph {"fontSize":"small","textColor":"ink-soft"} -->
<p class="has-ink-soft-color has-text-color has-small-font-size">Reglerna kommer från <a href="https://www.skatteverket.se/privat/fastigheterochbostad/rotarbeteochrutarbete/safungerarrotavdraget.4.5947400c11f47f7f9dd80004014.html" rel="nofollow">Skatteverket</a>. ROT och RUT ryms tillsammans i 75&nbsp;000 kr per år, varav högst 50&nbsp;000 kr får vara ROT.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","width":"54%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:54%"><!-- wp:group {"className":"is-style-card zo-rot-calc","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card zo-rot-calc" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">Räkneexempel — villatak, 140 m²</p>
<!-- /wp:paragraph --><?php
$rows = array(
	array( 'Material (pannor, papp, läkt, plåt)', '92 000 kr', '' ),
	array( 'Arbete', '120 000 kr', '' ),
	array( 'ROT-avdrag, 30 % av arbetet', '−36 000 kr', 'zo-rot-minus' ),
	array( 'Du betalar', '176 000 kr', 'zo-rot-total' ),
);
foreach ( $rows as $r ) :
	?><!-- wp:group {"className":"zo-rot-row <?php echo esc_attr( $r[2] ); ?>","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"}} -->
<div class="wp-block-group zo-rot-row <?php echo esc_attr( $r[2] ); ?>"><!-- wp:paragraph -->
<p><?php echo esc_html( $r[0] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong><?php echo esc_html( $r[1] ); ?></strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php endforeach; ?><!-- wp:paragraph {"fontSize":"small","textColor":"ink-soft"} -->
<p class="has-ink-soft-color has-text-color has-small-font-size"><span class="zo-sample">Exempelsiffror</span> — ditt tak kostar det ditt tak kostar. Vi kommer ut, mäter och skickar ett fast pris innan något bokas.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"accent","textColor":"base","width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-base-color has-accent-background-color has-text-color has-background wp-element-button" href="<?php echo $url( '/offert' ); ?>">Få ett fast pris på ditt tak</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
