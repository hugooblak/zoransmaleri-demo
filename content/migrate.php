<?php
/**
 * Old-site content → clean WordPress blocks.
 *
 * The job: "all existing content will need to be formatted and added to the new site".
 * Copy-pasting from an old page builder brings its mess along (inline styles, font tags,
 * shortcodes, empty paragraphs, ALL CAPS headings). This script shows the approach:
 *
 *  1. Read each exported page from content/legacy-export/.
 *  2. Keep the words and structure (headings, paragraphs, lists, quotes, tables, links).
 *  3. Drop the styling. The theme decides how things look, so the site stays consistent.
 *  4. Fix heading order, title case and shouting, and point old links at new pages.
 *  5. Save as normal, editable blocks, and write a report and a redirect list (old URL → new URL)
 *     so no search ranking or bookmark is lost.
 *
 * On a real project the export comes from the live site (WP export, crawl or database)
 * and every page gets a human read-through after the script. The report lists what to check.
 *
 * @package Zorans Måleri
 */

defined( 'ABSPATH' ) || exit;

/**
 * Old URLs we know the new address for. Imported articles are added to redirects.csv separately.
 */
function npm_link_map() {
	return array(
		'/contact-us.html' => '/free-quote/',
		'/contact.html'    => '/free-quote/',
		'/services.html'   => '/',
		'/about-us.html'   => '/about/',
	);
}

/**
 * Convert one exported HTML file.
 *
 * @param string $file Path.
 * @return array{title:string, date:string, old_url:string, content:string, notes:string[]}
 */
function npm_convert_file( $file ) {
	$raw   = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	$notes = array();

	preg_match( '#URL:\s*(\S+)#', $raw, $m );
	$old_url = $m[1] ?? '';
	preg_match( '#<meta name="date" content="([^"]+)"#', $raw, $m );
	$date = $m[1] ?? '';
	preg_match( '#<title>(.*?)</title>#s', $raw, $m );
	$old_title = trim( html_entity_decode( $m[1] ?? '' ) );

	// Page builder shortcodes like [vc_row] are layout, not content.
	$count = 0;
	$raw   = preg_replace( '#\[/?(vc_|et_pb_)[^\]]*\]#', '', $raw, -1, $count );
	if ( $count ) {
		$notes[] = "Removed $count page builder shortcodes.";
	}

	$doc = new DOMDocument();
	libxml_use_internal_errors( true );
	$doc->loadHTML( '<?xml encoding="utf-8"?><body>' . $raw . '</body>' );
	libxml_clear_errors();
	$body = $doc->getElementsByTagName( 'body' )->item( 0 );

	// Hidden text (often old keyword stuffing) can get a site penalised. Remove it and say so.
	$xpath = new DOMXPath( $doc );
	foreach ( iterator_to_array( $xpath->query( '//*[contains(translate(@style," ",""),"display:none")]' ) ) as $hidden ) {
		$notes[] = 'Removed hidden text: "' . trim( $hidden->textContent ) . '" (hidden keywords can hurt SEO).';
		$hidden->parentNode->removeChild( $hidden );
	}
	foreach ( iterator_to_array( $xpath->query( '//title|//meta' ) ) as $n ) {
		$n->parentNode->removeChild( $n );
	}

	// Collect block-level elements in reading order, looking through wrapper divs.
	$items = array();
	$walk  = function ( $node ) use ( &$walk, &$items ) {
		foreach ( $node->childNodes as $child ) {
			if ( XML_ELEMENT_NODE !== $child->nodeType ) {
				continue;
			}
			$tag = strtolower( $child->nodeName );
			if ( in_array( $tag, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'ul', 'ol', 'blockquote', 'table' ), true ) ) {
				$items[] = $child;
			} else {
				$walk( $child );
			}
		}
	};
	$walk( $body );

	// The first heading becomes the post title (the template shows it), unless it's missing.
	$title = '';
	foreach ( $items as $i => $el ) {
		if ( preg_match( '/^h[1-6]$/', $el->nodeName ) ) {
			$title = npm_clean_heading( $el->textContent );
			unset( $items[ $i ] );
			break;
		}
	}
	if ( ! $title ) {
		$title = npm_clean_heading( preg_replace( '/\s*[|\-–]\s*Zorans Måleri.*$/i', '', $old_title ) );
	}
	if ( $title !== $old_title ) {
		$notes[] = "Title: \"$old_title\" → \"$title\".";
	}

	// Heading levels: the page title is the H1, so body headings start at H2 with no skipped levels.
	$levels = array();
	foreach ( $items as $el ) {
		if ( preg_match( '/^h([1-6])$/', $el->nodeName, $m ) ) {
			$levels[ (int) $m[1] ] = true;
		}
	}
	ksort( $levels );
	$level_map = array();
	$next      = 2;
	foreach ( array_keys( $levels ) as $old ) {
		$level_map[ $old ] = min( $next++, 4 );
	}
	if ( $levels && array_keys( $levels ) !== array_values( array_slice( range( 2, 6 ), 0, count( $levels ) ) ) ) {
		$notes[] = 'Fixed heading order (was ' . implode( ', ', array_map( fn( $l ) => "H$l", array_keys( $levels ) ) ) . ').';
	}

	$blocks      = array();
	$dropped_cta = false;
	foreach ( $items as $el ) {
		$tag = strtolower( $el->nodeName );

		if ( preg_match( '/^h([1-6])$/', $tag, $m ) ) {
			$level    = $level_map[ (int) $m[1] ];
			$text     = esc_html( npm_clean_heading( $el->textContent ) );
			$attrs    = 2 === $level ? '' : ' {"level":' . $level . '}';
			$blocks[] = "<!-- wp:heading$attrs -->\n<h$level class=\"wp-block-heading\">$text</h$level>\n<!-- /wp:heading -->";
			continue;
		}

		if ( 'p' === $tag ) {
			// A lone image: the old file isn't in the export, so flag it instead of leaving a broken image.
			if ( $el->getElementsByTagName( 'img' )->length && '' === trim( str_replace( "\xc2\xa0", '', $el->textContent ) ) ) {
				$img     = $el->getElementsByTagName( 'img' )->item( 0 );
				$no_alt  = '' === trim( $img->getAttribute( 'alt' ) );
				$notes[] = 'Image "' . basename( $img->getAttribute( 'src' ) ) . '" not carried over: the file is not in the export' . ( $no_alt ? ' and it had no alt text' : '' ) . '. Upload a current photo' . ( $no_alt ? ' and add alt text' : '' ) . '.';
				continue;
			}
			// Old shouty "CALL US" buttons: the new template ends every guide with a proper quote box.
			$links = $el->getElementsByTagName( 'a' );
			if ( 1 === $links->length && trim( $links->item( 0 )->textContent ) === trim( $el->textContent ) && preg_match( '/btn|button/i', $links->item( 0 )->getAttribute( 'class' ) ) ) {
				$notes[]     = 'Replaced old button "' . trim( $links->item( 0 )->textContent ) . '" with the theme\'s quote box.';
				$dropped_cta = true;
				continue;
			}
			$html = npm_inline( $el );
			if ( '' === trim( wp_strip_all_tags( $html ) ) ) {
				continue; // Empty spacer paragraphs (&nbsp;, <br>) — spacing is the theme's job.
			}
			$blocks[] = "<!-- wp:paragraph -->\n<p>$html</p>\n<!-- /wp:paragraph -->";
			continue;
		}

		if ( 'ul' === $tag || 'ol' === $tag ) {
			$li = array();
			foreach ( $el->getElementsByTagName( 'li' ) as $item ) {
				$li[] = "<!-- wp:list-item -->\n<li>" . npm_inline( $item ) . "</li>\n<!-- /wp:list-item -->";
			}
			$attr     = 'ol' === $tag ? ' {"ordered":true}' : '';
			$blocks[] = "<!-- wp:list$attr -->\n<$tag class=\"wp-block-list\">" . implode( "\n\n", $li ) . "</$tag>\n<!-- /wp:list -->";
			continue;
		}

		if ( 'blockquote' === $tag ) {
			$text     = npm_inline( $el );
			$text     = trim( preg_replace( '#^<em>(.*)</em>$#s', '$1', trim( $text ) ) );
			$blocks[] = "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>$text</p>\n<!-- /wp:paragraph --></blockquote>\n<!-- /wp:quote -->";
			continue;
		}

		if ( 'table' === $tag ) {
			$rows = array();
			foreach ( $el->getElementsByTagName( 'tr' ) as $tr ) {
				$cells = array();
				foreach ( $tr->childNodes as $td ) {
					if ( XML_ELEMENT_NODE === $td->nodeType ) {
						$cells[] = trim( wp_strip_all_tags( npm_inline( $td ) ) );
					}
				}
				$rows[] = $cells;
			}
			$first_tr   = $el->getElementsByTagName( 'tr' )->item( 0 );
			$had_thead  = $el->getElementsByTagName( 'thead' )->length > 0;
			$bold_head  = $first_tr && $first_tr->getElementsByTagName( 'b' )->length + $first_tr->getElementsByTagName( 'strong' )->length > 0;
			$had_border = $el->hasAttribute( 'border' ) || $el->hasAttribute( 'cellpadding' );
			$head       = array_shift( $rows );
			$th   = '<thead><tr>' . implode( '', array_map( fn( $c ) => '<th>' . esc_html( $c ) . '</th>', $head ) ) . '</tr></thead>';
			$tb   = '<tbody>' . implode( '', array_map( fn( $r ) => '<tr>' . implode( '', array_map( fn( $c ) => '<td>' . esc_html( $c ) . '</td>', $r ) ) . '</tr>', $rows ) ) . '</tbody>';
			$blocks[] = "<!-- wp:table -->\n<figure class=\"wp-block-table\"><table class=\"has-fixed-layout\">$th$tb</table></figure>\n<!-- /wp:table -->";
			$why      = array();
			if ( ! $had_thead ) {
				$why[] = $bold_head ? 'header was bold text in normal cells' : 'no header row';
			}
			if ( $had_border ) {
				$why[] = 'old border/cellpadding attributes removed';
			}
			$notes[] = 'Tabellen ombyggd med riktig rubrikrad' . ( $why ? ' (' . implode( '; ', $why ) . ')' : '' ) . '. Check the first row really is a header.';
		}
	}

	$notes[] = 'Tog bort inline-stilar, font-taggar och wrapper-divar. Temat sköter formgivningen.';

	return array(
		'title'   => $title,
		'date'    => $date,
		'old_url' => $old_url,
		'content' => implode( "\n\n", $blocks ),
		'notes'   => $notes,
	);
}

/**
 * Heading text: trim, collapse "!!"/"??", and turn ALL CAPS or Title Case Everywhere into sentence case.
 */
function npm_clean_heading( $text ) {
	$text = trim( preg_replace( '/\s+/', ' ', html_entity_decode( $text ) ) );
	$text = preg_replace( '/([!?])\1+/', '$1', $text );
	$words = explode( ' ', $text );
	$caps  = count( array_filter( $words, fn( $w ) => preg_match( '/^[A-Z][A-Za-z\']*[?!:]?$/', $w ) ) );
	if ( strtoupper( $text ) === $text || ( count( $words ) > 3 && $caps >= count( $words ) - 1 ) ) {
		$text = ucfirst( strtolower( $text ) );
	}
	return $text;
}

/**
 * Inline HTML with only the tags a writer needs: bold, italic, links, line breaks.
 */
function npm_inline( DOMNode $node ) {
	$out = '';
	foreach ( $node->childNodes as $child ) {
		if ( XML_TEXT_NODE === $child->nodeType ) {
			$out .= esc_html( str_replace( "\xc2\xa0", ' ', $child->textContent ) );
			continue;
		}
		if ( XML_ELEMENT_NODE !== $child->nodeType ) {
			continue;
		}
		$tag   = strtolower( $child->nodeName );
		$inner = npm_inline( $child );
		$bold  = preg_match( '/font-weight\s*:\s*(bold|[6-9]00)/i', $child->getAttribute( 'style' ) );
		switch ( true ) {
			case in_array( $tag, array( 'strong', 'b' ), true ) || ( 'span' === $tag && $bold ):
				$out .= '' === trim( $inner ) ? $inner : "<strong>$inner</strong>";
				break;
			case in_array( $tag, array( 'em', 'i' ), true ):
				$out .= "<em>$inner</em>";
				break;
			case 'a' === $tag:
				$href = $child->getAttribute( 'href' );
				$map  = npm_link_map();
				if ( isset( $map[ $href ] ) ) {
					$href = $map[ $href ];
				}
				$href = 0 === strpos( $href, '/' ) ? home_url( $href ) : $href;
				$out .= '<a href="' . esc_url( $href ) . '">' . $inner . '</a>';
				break;
			case 'br' === $tag:
				$out .= '<br>';
				break;
			case in_array( $tag, array( 'p', 'ul', 'ol' ), true ): // Nested blocks inside quotes/cells: keep the text.
				$out .= $inner;
				break;
			default: // span, font, div…: keep the words, drop the wrapper.
				$out .= $inner;
		}
	}
	// Strip leading/trailing <br> and whitespace.
	return trim( preg_replace( '#^(\s|<br>)+|(\s|<br>)+$#', '', $out ) );
}

/**
 * Import every file in legacy-export/ as a post, and write the report and redirect list.
 *
 * @param string $dir     Folder with exported HTML.
 * @param string $out_dir Where to write migration-report.md and redirects.csv (null to skip writing).
 * @return int[] New post IDs.
 */
function npm_import_all( $dir, $out_dir = null ) {
	$ids       = array();
	$report    = "# Content migration report\n\nGenerated by `content/migrate.php`. Each article from the old site, what changed, and what a person should check.\n";
	$redirects = "source,target\n";

	foreach ( glob( trailingslashit( $dir ) . '*.html' ) as $file ) {
		$post = npm_convert_file( $file );
		$slug = sanitize_title( basename( $file, '.html' ) );

		$existing = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $existing ) {
			$ids[] = $existing->ID;
		} else {
			$ids[] = wp_insert_post(
				array(
					'post_type'    => 'post',
					'post_status'  => 'publish',
					'post_name'    => $slug,
					'post_title'   => $post['title'],
					'post_content' => $post['content'],
					'post_date'    => $post['date'] ? $post['date'] . ' 09:00:00' : current_time( 'mysql' ),
					'meta_input'   => array( '_legacy_url' => $post['old_url'] ),
				)
			);
		}

		$report    .= "\n## " . $post['title'] . "\n\n- Old URL: `" . $post['old_url'] . "`\n- New URL: `/" . $slug . "/`\n";
		$report    .= implode( '', array_map( fn( $n ) => "- $n\n", $post['notes'] ) );
		$redirects .= $post['old_url'] . ',/' . $slug . "/\n";
	}
	foreach ( npm_link_map() as $from => $to ) {
		$redirects .= "$from,$to\n";
	}
	$report .= "\n## Redirects\n\n`redirects.csv` lists every old URL and its new address (301 redirects). Import it into the free Redirection plugin, or the host's redirect rules, before launch.\n";

	if ( $out_dir && is_writable( $out_dir ) ) {
		file_put_contents( trailingslashit( $out_dir ) . 'migration-report.md', $report ); // phpcs:ignore
		file_put_contents( trailingslashit( $out_dir ) . 'redirects.csv', $redirects ); // phpcs:ignore
	}
	return $ids;
}
