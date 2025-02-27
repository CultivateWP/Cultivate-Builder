<?php
/**
 * Table of Contents block
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace Cultivate\Blocks\TOC;

/**
 * Supported headings
 */
function headings() {
	return [ 'h2' ];
}

/**
 * Enqueue JavaScript for expand button
 */
function expand_button() {
	wp_enqueue_script( 'toc', get_theme_file_uri( '/blocks/toc/toc.js' ), [], filemtime( get_theme_file_path( '/blocks/toc/toc.js' ) ), true );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\expand_button' );

/**
 * Add IDs to headings
 */
function ids_to_headings( $content ) {

	if ( '' == $content ) {
		return $content;
	}

	if ( is_admin() ) {
		return $content;
	}

	global $post;
	if ( ! is_object( $post ) || empty( $post->post_content ) ) {
		return $content;
	}

	//$include_anchors = apply_filters( 'cultivate_pro/toc/include_anchors', false !== strpos( $post->post_content, 'wp:cwp/toc' ) );
	$include_anchors = apply_filters( 'cultivate_pro/toc/include_anchors', true );
	if ( ! $include_anchors ) {
		return $content;
	}

	$anchors = array();
	$doc     = new \DOMDocument();
	// START LibXML error management.
	// Modify state.
	$libxml_previous_state = libxml_use_internal_errors( true );
	$doc->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
	// handle errors.
	libxml_clear_errors();
	// restore.
	libxml_use_internal_errors( $libxml_previous_state );
	// END LibXML error management.

	$supported_headings = headings();
	foreach ( $supported_headings as $h ) {
		$headings = $doc->getElementsByTagName( $h );
		foreach ( $headings as $heading ) {
			$slug = $heading->getAttribute( 'id' );
			if( empty( $slug ) ) {
				$slug = $tmpslug = sanitize_title( $heading->nodeValue );
				// @codingStandardsIgnoreEnd
				$i = 2;
				while ( false !== in_array( $slug, $anchors ) ) {
					$slug = sprintf( '%s-%d', $tmpslug, $i++ );
				}
				$heading->setAttribute( 'id', $slug );
			}
			$anchors[] = $slug;
		}
	}
	$content = $doc->saveHTML();

	// Remove opening/closing body and html.
	$content = str_replace(
		[
			'<html><body>',
			'</body></html>',
		],
		'',
		$content
	);
	return $content;
}
add_filter( 'the_content', __NAMESPACE__ . '\ids_to_headings' );
add_filter( 'cultivate_pro/toc/content', __NAMESPACE__ . '\ids_to_headings' );
add_filter( 'cultivate_pro/landing/the_content', __NAMESPACE__ . '\ids_to_headings' );


/**
 * Table of Contents
 *
 */
function output() {

	$post = cwp_post_object();
	if ( empty( $post ) ) {
		return;
	}

	$content = apply_filters( 'cultivate_pro/toc/content', $post->post_content );

	if ( empty( $content ) ) {
		return;
	}

	// Include reusable blocks.
	$blocks = parse_blocks( $content );
	foreach ( $blocks as $block ) {
		if ( 'core/block' === $block['blockName'] && ! empty( $block['attrs']['ref'] ) ) {
			$inner_block = get_post( $block['attrs']['ref'] );
			if ( $inner_block instanceof WP_Post && ! empty( $inner_block->post_content ) ) {
				$content     = str_replace( '<!-- wp:block {"ref":' . $block['attrs']['ref'] . '} /-->', $inner_block->post_content, $content );
			}
		}
	}

	$doc = new \DOMDocument();
	// START LibXML error management.
	// Modify state.
	$libxml_previous_state = libxml_use_internal_errors( true );
	$doc->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

	// handle errors.
	libxml_clear_errors();
	// restore.
	libxml_use_internal_errors( $libxml_previous_state );
	// END LibXML error management.

	$query = join( '|', array_map(
		function($h) {
			return '//' . $h;
		},
		headings()
	) );

	$domxpath = new \DOMXPath( $doc );
	$headings = $domxpath->query( $query );
	if ( empty( $headings ) ) {
		return;
	}

	$output    = '';
	$depth     = 1;
	$nodeDepth = 1;
	$allowed_html = apply_filters( 'cultivate_pro/toc/allowed_html', [ 'sub' => [], 'sup' => [] ] );
	foreach ( $headings as $heading ) {
		$nodeDepth = intval( str_replace('h', '', $heading->nodeName ) );
		if ( $nodeDepth > $depth ) {
			while ( $nodeDepth > $depth ) {
				$output .= '<ul class="is-style-fancy">';
				$depth++;
			}
		} elseif( $nodeDepth < $depth ) {
			while ( $nodeDepth < $depth ) {
				$output .= '</ul>';
				$depth--;
			}
		}

		$title = wp_kses( $doc->saveHTML( $heading ), $allowed_html );
		if ( empty( $title ) ) {
			continue;
		}

		$class = $heading->getAttribute( 'class' );
		$id = $heading->getAttribute( 'id' );
		if ( empty( $id ) ) {
			$id = sanitize_title( $heading->nodeValue );
		}
		if ( 'wprm-fallback-recipe-name' === $class ) {
			$append = apply_filters( 'cultivate_pro/toc/append_recipe', ' Recipe' );
			if ( ! empty ( $append ) && false === strpos( $title, $append ) ) {
				$title .= $append;
			}
			$title = apply_filters( 'cultivate_pro/toc/recipe_title', $title );
			$id    = 'wprm-recipe-container-' . recipe_id();
		}

		if ( 'table-of-contents' === $id ) {
			continue;
		}

		$output .= '<li><a href="#' . $id . '">' . $title . '</a></li>';
		$depth = $nodeDepth;
	}

	if ( 2 < $nodeDepth ) {
		while ( 2 < $nodeDepth ) {
			$output .= '</ul>';
			$nodeDepth--;
		}
	}

	// Tasty Recipes.
	if ( class_exists( 'Tasty_Recipes' ) ) {
		foreach ( $blocks as $block ) {
			if ( 'wp-tasty/tasty-recipe' === $block['blockName'] ) {
				$recipe_id = $block['attrs']['id'];
				$anchor    = '#tasty-recipes-' . $recipe_id . '-jump-target';
				$title     = get_the_title( $recipe_id );
				$append    = apply_filters( 'cultivate_pro/toc/append_recipe', ' Recipe' );
				if ( ! empty ( $append ) && false === strpos( $title, $append ) ) {
					$title .= $append;
				}
				$title = apply_filters( 'cultivate_pro/toc/recipe_title', $title );
				$output .= '<li><a href="' . $anchor . '">' . esc_html( $title ) . '</a></li>';
			}
		}
	}

	// MV Create.
	if ( function_exists( 'mv_create_is_compatible' ) ) {
		foreach( $blocks as $block ) {
			if ( 'mv/recipe' === $block['blockName'] ) {
				$recipe_id = $block['attrs']['id'];
				$anchor = '#mv-creation-' . $recipe_id;
				$title = $block['attrs']['title'];
				$append = apply_filters( 'cultivate_pro/toc/append_recipe', ' Recipe' );
				if ( ! empty ( $append ) && false === strpos( $title, $append ) ) {
					$title .= $append;
				}
				$title = apply_filters( 'cultivate_pro/toc/recipe_title', $title );
				$output .= '<li><a href="' . $anchor . '">' . esc_html( $title ) . '</a></li>';
			}
		}
	}

	$output .= '</ul>';

	global $cwp_toc_recipe;
	$cwp_toc_recipe = 0;

	return $output;
}

/**
 * Get Recipe ID
 */
function recipe_id() {
	if ( ! class_exists( 'WPRM_Recipe_Manager' ) ) {
		return false;
	}

	$recipe_id = false;
	$post      = cwp_post_object();
	if ( empty( $post ) ) {
		return false;
	}

	$recipes = \WPRM_Recipe_Manager::get_recipe_ids_from_content( $post->post_content );
	if ( empty( $recipes ) ) {
		return $recipe_id;
	}

	global $cwp_toc_recipe;
	if ( empty( $cwp_toc_recipe ) ) {
		$cwp_toc_recipe = 0;
	}

	if ( ! empty( $recipes[ $cwp_toc_recipe ] ) ) {
		$recipe_id = $recipes[ $cwp_toc_recipe ];
	}

	$cwp_toc_recipe++;
	return $recipe_id;
}

/**
 * Get Current Post Object
 */
function cwp_post_object() {

	global $post;

	if ( ! $post instanceof \WP_Post ) {
		return false;
	}

	if ( 'block_area' === get_post_type( $post->ID ) ) {
		$post = get_queried_object();
	}

	return $post;
}
