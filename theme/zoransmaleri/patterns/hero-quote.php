<?php
/**
 * Title: Hero
 * Slug: zoransmaleri/hero-quote
 * Categories: zoransmaleri-sections
 * Keywords: hero, form, offert, lead
 * Description: Fotoledd hero med orter, namngiven kontakt, Google-betyg, siffror och offertformulär.
 */
$img = get_theme_file_uri( 'assets/img/' );
?>
<!-- wp:cover {"url":"<?php echo esc_url( zo_lead_img( zo_lead( 'bild_hero' ) ) ); ?>","dimRatio":40,"overlayColor":"ink","isUserOverlayColor":true,"minHeight":88,"minHeightUnit":"vh","align":"full","className":"zo-sec-hero","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-cover alignfull zo-sec-hero" style="min-height:88vh"><span aria-hidden="true" class="wp-block-cover__background has-ink-background-color has-background-dim-40 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Platshållare: byt mot en bild på ett tak företaget lagt" src="<?php echo esc_url( zo_lead_img( zo_lead( 'bild_hero' ) ) ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:columns {"verticalAlignment":"bottom","align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-bottom"><!-- wp:column {"verticalAlignment":"bottom","width":"58%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:58%"><!-- wp:paragraph {"className":"is-style-eyebrow","textColor":"sand"} -->
<p class="is-style-eyebrow has-sand-color has-text-color">Takläggare i <?php echo esc_html( implode( ', ', array_slice( (array) zo_lead( 'orter', array() ), 0, 4 ) ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"textColor":"base","fontSize":"display"} -->
<h1 class="wp-block-heading has-base-color has-text-color has-display-font-size">Taket håller huset torrt.<br><em>Vi håller taket.</em></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"sand","fontSize":"large"} -->
<p class="has-sand-color has-text-color has-large-font-size">Kostnadsfri takbesiktning och fast pris skriftligt. Du betalar ingenting förrän materialet står på tomten.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"accent","textColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-base-color has-accent-background-color has-text-color has-background wp-element-button" href="tel:<?php echo esc_attr( zo_lead( 'telefon_tel' ) ); ?>"><?php echo esc_html( zo_ring_label() ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline","textColor":"base"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-base-color has-text-color wp-element-button" href="<?php echo esc_url( home_url( '/offert' ) ); ?>">Boka takbesiktning</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:group {"className":"zo-hero-stats","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left"}} -->
<div class="wp-block-group zo-hero-stats"><!-- wp:paragraph {"textColor":"base"} -->
<p class="has-base-color has-text-color"><strong><?php echo esc_html( zo_lead( 'ar_i_branschen' ) ); ?> år</strong><br><span>i branschen</span></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"textColor":"base"} -->
<p class="has-base-color has-text-color"><strong><?php echo esc_html( zo_lead( 'antal_tak' ) ); ?></strong><br><span>lagda tak</span></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"textColor":"base"} -->
<p class="has-base-color has-text-color"><strong><?php echo esc_html( zo_lead( 'betyg' ) ); ?></strong><br><span>på Google</span></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"bottom","width":"42%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:42%"><!-- wp:group {"className":"is-style-card zo-lift","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card zo-lift" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"className":"zo-proof","layout":{"type":"default"}} -->
<div class="wp-block-group zo-proof"><!-- wp:paragraph {"className":"zo-proof-score"} -->
<p class="zo-proof-score"><strong><?php echo esc_html( zo_lead( 'betyg' ) ); ?></strong> <span class="zo-stars"></span></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"zo-proof-count","fontSize":"small"} -->
<p class="zo-proof-count"><?php echo esc_html( zo_lead( 'omdomen' ) ); ?> omdömen på Google</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:heading {"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size">Få pris på ditt tak</h2>
<!-- /wp:heading -->

<!-- wp:zoransmaleri/lead-form {"variant":"start"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div></div>
<!-- /wp:cover -->
