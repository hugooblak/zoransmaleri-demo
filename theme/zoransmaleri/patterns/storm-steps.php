<?php
/**
 * Title: Storm: what to do after a storm
 * Slug: zoransmaleri/storm-steps
 * Categories: zoransmaleri-sections
 * Description: Four numbered steps for homeowners after hail or wind damage.
 */
$img = get_theme_file_uri( 'assets/img/' );
$url = function ( $path ) { return esc_url( home_url( $path ) ); };
?>
<!-- wp:group {"align":"full","className":"zo-sec-steps","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group alignfull zo-sec-steps" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:heading {"align":"wide"} -->
<h2 class="wp-block-heading alignwide">Så går en taktvätt till</h2>
<!-- /wp:heading -->

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","minimumColumnWidth":"14rem"}} -->
<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--50)"><?php
$steps = array(
	array( 'Vi tittar på taket', 'Don\'t climb on the roof. Photograph damage you can see from the ground, and any leaks inside.' ),
	array( 'Du får ett fast pris', 'Vi går upp, fotar och bedömer om taket går att tvätta eller om det har gått för långt. Kostnadsfritt.' ),
	array( 'Vi tvättar och lagar', 'Lågtryckstvätt så att pannorna inte skadas, och vi byter de pannor som är trasiga.' ),
	array( 'Vi impregnerar', 'Behandlingen gör att mossan inte kommer tillbaka lika fort. Vi lämnar 10 års garanti på behandlingen.' ),
);
foreach ( $steps as $i => $step ) :
	?><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"zo-step-num"} -->
<p class="zo-step-num"><?php echo (int) $i + 1; ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $step[0] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"ink-soft"} -->
<p class="has-ink-soft-color has-text-color"><?php echo esc_html( $step[1] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php endforeach; ?></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
