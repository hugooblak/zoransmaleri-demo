<?php
/**
 * Title: Comparison table
 * Slug: zoransmaleri/compare
 * Categories: zoransmaleri-sections
 * Description: Side-by-side comparison against the common alternative, answering why choose us.
 */
$img = get_theme_file_uri( 'assets/img/' );
$url = function ( $path ) { return esc_url( home_url( $path ) ); };
?>
<!-- wp:group {"align":"full","className":"zo-sec-compare","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"860px"}} -->
<div class="wp-block-group alignfull zo-sec-compare" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">Why homeowners pick us</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">A local roofer vs. a door-to-door storm crew</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"ink-soft"} -->
<p class="has-ink-soft-color has-text-color">After every big storm, out-of-town crews knock on doors. Here is what to compare before you sign with anyone.</p>
<!-- /wp:paragraph -->

<!-- wp:table {"className":"zo-price-table zo-compare-table","style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
<figure class="wp-block-table zo-price-table zo-compare-table" style="margin-top:var(--wp--preset--spacing--40)"><table class="has-fixed-layout"><thead><tr><th>What to check</th><th>Zorans Måleri</th><th>Typical storm crew</th></tr></thead><tbody><tr><td>Local office you can visit</td><td>Yes, since 2004</td><td>Often gone in weeks</td></tr><tr><td>Price</td><td>Fixed, in writing</td><td>"Försäkringen täcker det"</td></tr><tr><td>Deposit</td><td>None until materials arrive</td><td>Often up front</td></tr><tr><td>Workmanship warranty</td><td>25 years</td><td>1–2 years, if any</td></tr><tr><td>Cleanup</td><td>Magnet sweep and walk-through</td><td>Varies</td></tr></tbody></table></figure>
<!-- /wp:table --></div>
<!-- /wp:group -->
