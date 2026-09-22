<?php
/**
 * Title: Service hero: Takomläggning
 * Slug: zoransmaleri/service-hero-replacement
 * Categories: zoransmaleri-sections
 * Description: Service page opening: headline, promise, two actions and an illustration.
 */
$img = get_theme_file_uri( 'assets/img/' );
$url = function ( $path ) { return esc_url( home_url( $path ) ); };
?>
<!-- wp:group {"align":"full","className":"zo-sec-hero","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"sand","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group alignfull zo-sec-hero has-sand-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"56%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:56%"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">Takomläggning</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Nytt tak till ett fast pris, med 10 års garanti</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"ink-soft","fontSize":"large"} -->
<p class="has-ink-soft-color has-text-color has-large-font-size">Vi river det gamla taket ner till råsponten, åtgärdar det som ligger under och lägger ett nytt på 2–5 dagar. Priset i offerten är priset du betalar.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"zo-track-cta"} -->
<div class="wp-block-button zo-track-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo $url( '/free-quote/?service=replacement' ); ?>">Boka kostnadsfri besiktning</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline zo-track-call"} -->
<div class="wp-block-button is-style-outline zo-track-call"><a class="wp-block-button__link wp-element-button" href="tel:+15550100142">Ring Anders · 070-123 45 67</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"zo-stars","fontSize":"small"} -->
<p class="zo-stars has-small-font-size"><strong>4,9 av 5</strong> från 158 omdömen <span class="zo-sample">(platshållare)</span></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"44%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:44%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( $img . 'house.svg' ); ?>" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
