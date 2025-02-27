<?php
/**
 * Single Post
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use Cultivate\Block_Areas;

/**
 * Before & After First Image block area
 */
function cwp_before_after_image( $output, $block ) {

	if ( 'core/image' !== $block['blockName'] || get_the_ID() !== get_queried_object_id() ) {
		return $output;
	}

	global $wp_query;
	if ( ! $wp_query->in_the_loop ) {
		return $output;
	}

	// Only run once.
	remove_filter( 'render_block', 'cwp_before_after_image', 10, 2 );

	$before = Block_Areas\show( 'before-first-image', false );
	$after  = Block_Areas\show( 'after-first-image', false );
	$output = $before . $output . $after;

	return $output;
}
add_filter( 'render_block', 'cwp_before_after_image', 10, 2 );

/**
 * TOC Block Area
 */
function cwp_toc_block_area( $output, $original_block ) {

	if ( 'core/heading' !== $original_block['blockName'] || get_the_ID() !== get_queried_object_id() ) {
		return $output;
	}

	global $wp_query;
	if ( ! $wp_query->in_the_loop ) {
		return $output;
	}

	// Only run once.
	remove_filter( 'render_block', 'cwp_toc_block_area', 10, 2 );

	// Only run if 3+ headings
	if( cwp_has_block( 'core/heading' ) < 2 ) {
		return $output;
	}

	if ( ! ( has_block( 'cwp/toc' ) || has_block( 'yoast-seo/table-of-contents' ) || has_block( 'feast/advanced-jump-to-block' ) ) ) {
		$output = Block_Areas\show( 'table-of-contents', false ) . $output;
	}

	return $output;
}
add_filter( 'render_block', 'cwp_toc_block_area', 10, 2 );


/**
 * After Post
 */
function cwp_after_post() {
	Block_Areas\show( 'after-post' );
}
add_action( 'tha_content_while_after', 'cwp_after_post', 8 );


// Build the page.
require get_template_directory() . '/index.php';
