<?php
/**
 * Title: Thank you
 * Slug: zoransmaleri/thank-you
 * Categories: zoransmaleri-pages
 * Description: Confirmation after the form: what happens next, the phone number for urgent leaks, and useful reading while they wait.
 */
$img = get_theme_file_uri( 'assets/img/' );
$url = function ( $path ) { return esc_url( home_url( $path ) ); };
?>
<!-- wp:group {"align":"full","className":"zo-sec-thanks","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"700px"}} -->
<div class="wp-block-group alignfull zo-sec-thanks" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:group {"className":"is-style-card zo-lift","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card zo-lift" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:image {"width":"48px","height":"48px","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( $img . 'icon-shield.svg' ); ?>" alt="" style="width:48px;height:48px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":1,"fontSize":"xx-large"} -->
<h1 class="wp-block-heading has-xx-large-font-size">Tack, vi har fått din förfrågan.</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Vi ringer eller sms:ar dig inom <strong>en timme</strong> och bokar din kostnadsfria besiktning.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"ordered":true} -->
<ol class="wp-block-list"><!-- wp:list-item -->
<li><strong>Today:</strong> bekräftar vi en tid som passar dig.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Inspection:</strong> tar ungefär 45 minuter, med foton.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Inom två arbetsdagar:</strong> din skriftliga offert med fast pris på mejl.</li>
<!-- /wp:list-item --></ol>
<!-- /wp:list -->

<!-- wp:paragraph {"backgroundColor":"pine-light","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}}} -->
<p class="has-pine-light-background-color has-background" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><strong>Läcker det just nu?</strong> Call <a href="tel:+15550100142">070-123 45 67</a> så prioriterar vi dig.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:heading {"level":2,"fontSize":"large","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
<h2 class="wp-block-heading has-large-font-size" style="margin-top:var(--wp--preset--spacing--50)">Medan du väntar</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><a href="<?php echo $url( '/roofing-guides/' ); ?>">Läs våra takguider</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo $url( '/#pricing' ); ?>">Se hur ROT-avdraget räknas</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->
