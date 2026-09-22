<?php
/**
 * Title: Tjänster
 * Slug: zoransmaleri/services
 * Categories: zoransmaleri-sections
 * Description: Tre tjänster som bildkort. Bilden är platshållare och byts mot företagets egna jobbfoton.
 */
$img = get_theme_file_uri( 'assets/img/' );
$url = function ( $path ) { return esc_url( home_url( $path ) ); };
$services = array(
	array( zo_lead_bild( 0 ), 'Takomläggning', 'Rivning av gammalt tak, ny underlagspapp, läkt och pannor. Klart på 2–5 dagar på ett normalt villatak.', '/takomlaggning', 'Ett nytt tak' ),
	array( zo_lead_bild( 1 ), 'Taktvätt & impregnering', 'Vi tvättar bort mossa och alger och behandlar ytan. Förlänger livslängden med 10–15 år.', '/taktvatt', 'Innan du byter' ),
	array( zo_lead_bild( 2 ), 'Takreparation', 'Läckor, trasiga pannor, plåt och hängrännor. Akuta jobb samma vecka.', '/takreparation', 'Något läcker' ),
);
?>
<!-- wp:group {"align":"full","className":"zo-sec-services","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group alignfull zo-sec-services" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:heading {"align":"wide","fontSize":"xx-large"} -->
<h2 class="wp-block-heading alignwide has-xx-large-font-size">Tre sätt vi hjälper dig</h2>
<!-- /wp:heading -->

<!-- wp:group {"align":"wide","className":"zo-cards","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","minimumColumnWidth":"18rem"}} -->
<div class="wp-block-group alignwide zo-cards" style="margin-top:var(--wp--preset--spacing--50)"><?php
foreach ( $services as $s ) :
	?><!-- wp:group {"className":"zo-card","layout":{"type":"default"}} -->
<div class="wp-block-group zo-card"><!-- wp:image {"sizeSlug":"full","linkDestination":"custom","className":"zo-card-img"} -->
<figure class="wp-block-image size-full zo-card-img"><a href="<?php echo $url( $s[3] ); ?>"><img src="<?php echo esc_url( zo_lead_img( $s[0] ) ); ?>" alt="Platshållare: <?php echo esc_attr( $s[1] ); ?> — byt mot ett eget jobbfoto"/></a></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"zo-card-kicker","fontSize":"small"} -->
<p class="zo-card-kicker has-small-font-size"><?php echo esc_html( $s[4] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-x-large-font-size"><a href="<?php echo $url( $s[3] ); ?>"><?php echo esc_html( $s[1] ); ?></a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"ink-soft","fontSize":"small"} -->
<p class="has-ink-soft-color has-text-color has-small-font-size"><?php echo esc_html( $s[2] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php endforeach; ?></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
