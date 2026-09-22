<?php
/**
 * Title: Quote funnel
 * Slug: zoransmaleri/quote-funnel
 * Categories: zoransmaleri-pages
 * Description: The full multi-step quote form with a short 'what happens next' panel beside it.
 */
$img = get_theme_file_uri( 'assets/img/' );
$url = function ( $path ) { return esc_url( home_url( $path ) ); };
?>
<!-- wp:group {"align":"full","className":"zo-sec-funnel","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"1080px"}} -->
<div class="wp-block-group alignfull zo-sec-funnel" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"62%"} -->
<div class="wp-block-column" style="flex-basis:62%"><!-- wp:group {"className":"is-style-card zo-lift","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card zo-lift" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:heading {"level":1,"fontSize":"xx-large"} -->
<h1 class="wp-block-heading has-xx-large-font-size">Få ett fast pris på ditt tak</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"ink-soft"} -->
<p class="has-ink-soft-color has-text-color">Fyra frågor, ungefär 30 sekunder. Skriftlig offert inom två arbetsdagar.</p>
<!-- /wp:paragraph -->

<!-- wp:zoransmaleri/lead-form {"variant":"full"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"38%"} -->
<div class="wp-block-column" style="flex-basis:38%"><!-- wp:heading {"level":2,"fontSize":"large"} -->
<h2 class="wp-block-heading has-large-font-size">Så här går det till</h2>
<!-- /wp:heading -->

<!-- wp:list {"ordered":true,"fontSize":"small"} -->
<ol class="wp-block-list has-small-font-size"><!-- wp:list-item -->
<li>Vi ringer eller sms:ar inom en timme och bokar besiktningen.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Besiktningen tar ungefär 45 minuter. Du får foton på allt vi hittar.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Din skriftliga offert med fast pris kommer på mejl inom två arbetsdagar.</li>
<!-- /wp:list-item --></ol>
<!-- /wp:list -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:list {"className":"is-style-checklist","fontSize":"small"} -->
<ul class="wp-block-list is-style-checklist has-small-font-size"><!-- wp:list-item -->
<li>Ingen bindning och inga påtryckningar</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>10 års garanti på arbetet</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Dina uppgifter lämnas aldrig vidare</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:group {"className":"is-style-card","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card"><!-- wp:paragraph {"className":"zo-stars","fontSize":"small"} -->
<p class="zo-stars has-small-font-size"><span class="screen-reader-text">Rated </span>5 out of 5</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size">"Offerten stämde på kronan med slutfakturan."</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"textColor":"ink-soft","fontSize":"small"} -->
<p class="has-ink-soft-color has-text-color has-small-font-size">Platshållare · omdöme hämtas från Google</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
