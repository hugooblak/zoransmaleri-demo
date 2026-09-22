<?php
/**
 * Title: Orter vi jobbar i
 * Slug: zoransmaleri/orter
 * Categories: zoransmaleri-sections
 * Description: Länkar till en sida per kommun. Det här är den lokala sökmotordelen — en sida per ort, inte en lista på en sida.
 */
$url = function ( $path ) { return esc_url( home_url( $path ) ); };
$orter = (array) zo_lead( 'orter', array() );
// One or two towns make a thin, sad-looking row - skip the section entirely.
if ( count( $orter ) < 3 ) { return; }
?>
<!-- wp:group {"align":"full","className":"zo-sec-orter","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"backgroundColor":"ink","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group alignfull zo-sec-orter has-ink-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"align":"wide","verticalAlignment":"top"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-top"><!-- wp:column {"verticalAlignment":"top","width":"34%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:34%"><!-- wp:heading {"textColor":"base","fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-base-color has-text-color has-xx-large-font-size">Vi kör ut hit</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"sand"} -->
<p class="has-sand-color has-text-color">Vi har lagt tak i hela norra Stockholm i trettio år. Klicka på din kommun så ser du jobb vi gjort där.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","width":"66%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:66%"><!-- wp:group {"className":"zo-orter","layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group zo-orter"><?php
foreach ( $orter as $o ) :
	$slug = strtolower( str_replace( array( ' ', 'å', 'ä', 'ö' ), array( '-', 'a', 'a', 'o' ), $o ) );
	?><!-- wp:paragraph {"className":"zo-ort"} -->
<p class="zo-ort"><a href="<?php echo $url( '/taklaggare-i-' . $slug ); ?>">Takläggare i <?php echo esc_html( $o ); ?></a></p>
<!-- /wp:paragraph -->

<?php endforeach; ?></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
