<?php
/**
 * Title: Vanliga frågor
 * Slug: zoransmaleri/faq
 * Categories: zoransmaleri-sections
 * Description: Sex frågor kunder faktiskt ställer. Öppnas och stängs utan JavaScript.
 */
$faqs = array(
	array( 'Hur lång tid tar en takomläggning?', 'Ett normalt villatak tar 2–5 dagar beroende på storlek, taklutning och vad vi hittar under de gamla pannorna. Vi säger alltid till innan vi börjar om något ser ut att dra ut på tiden.' ),
	array( 'Vad kostar det?', 'Det beror på takets yta, lutning, material och hur mycket som behöver bytas under pannorna. Vi kommer ut och tittar kostnadsfritt och skickar ett fast pris — inte en ungefärlig siffra.' ),
	array( 'Måste jag flytta ut under tiden?', 'Nej. Du kan bo kvar hela tiden. Det låter en del på dagarna, men taket är aldrig öppet över natten.' ),
	array( 'Hur fungerar ROT-avdraget?', 'Vi drar av 30 % av arbetskostnaden direkt på fakturan, upp till 50 000 kr per person och år, och sköter ansökan mot Skatteverket. Du behöver inte göra något.' ),
	array( 'Vad händer om det regnar?', 'Vi täcker taket varje kväll och river aldrig mer än vi hinner lägga igen samma dag. Vid ihållande regn pausar vi hellre än att lägga papp på blött underlag.' ),
	array( 'Vad ingår i garantin?', '10 års garanti på vårt arbete, utöver materialtillverkarens egen garanti på pannor och papp. Du får den skriftligt när jobbet är klart.' ),
);
?>
<!-- wp:group {"align":"full","className":"zo-sec-faq","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group alignfull zo-sec-faq" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"align":"wide","verticalAlignment":"top","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-top"><!-- wp:column {"verticalAlignment":"top","width":"32%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:32%"><!-- wp:heading {"fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-xx-large-font-size">Vanliga frågor</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"ink-soft"} -->
<p class="has-ink-soft-color has-text-color">Hittar du inte svaret? Ring oss på <a href="tel:<?php echo esc_attr( zo_lead( 'telefon_tel' ) ); ?>"><?php echo esc_html( zo_lead( 'telefon' ) ); ?></a>.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","width":"68%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:68%"><?php
foreach ( $faqs as $f ) :
	?><!-- wp:details {"className":"zo-faq-item"} -->
<details class="wp-block-details zo-faq-item"><summary><?php echo esc_html( $f[0] ); ?></summary><!-- wp:paragraph {"textColor":"ink-soft"} -->
<p class="has-ink-soft-color has-text-color"><?php echo esc_html( $f[1] ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<?php endforeach; ?></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
