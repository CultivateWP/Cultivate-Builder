<?php
/**
 * Blocks
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace Cultivate\Blocks;

/**
 * Load Blocks
 */
function load_blocks() {
	$blocks = get_blocks();
	foreach( $blocks as $block ) {
		if ( file_exists( get_template_directory() . '/blocks/' . $block . '/block.json' ) ) {
			register_block_type( get_template_directory() . '/blocks/' . $block . '/block.json' );
			if ( file_exists( get_template_directory() . '/blocks/' . $block . '/style.css' ) ) {
				wp_register_style( 'block-' . $block, get_template_directory_uri() . '/blocks/' . $block . '/style.css', null, filemtime( get_template_directory() . '/blocks/' . $block . '/style.css' ) );
			}
			if ( file_exists( get_template_directory() . '/blocks/' . $block . '/init.php' ) ) {
				include_once get_template_directory() . '/blocks/' . $block . '/init.php';
			}
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\load_blocks', 5 );

/**
 * Load ACF field groups for blocks
 */
function load_acf_field_group( $paths ) {
	$blocks = get_blocks();
	foreach( $blocks as $block ) {
		$paths[] = get_template_directory() . '/blocks/' . $block;
	}
	return $paths;
}
add_filter( 'acf/settings/load_json', __NAMESPACE__ . '\load_acf_field_group' );

/**
 * Get Blocks
 */
function get_blocks() {
	$blocks = scandir( get_template_directory() . '/blocks/' );
	$blocks = array_values( array_diff( $blocks, array( '..', '.', '.DS_Store', '_base-block' ) ) );
	return $blocks;
}

/**
 * Block categories
 *
 * @since 1.0.0
 */
function block_categories( $categories ) {

	// Check to see if we already have a CultivateWP category
	$include = true;
	foreach( $categories as $category ) {
		if( 'cultivatewp' === $category['slug'] ) {
			$include = false;
		}
	}

	if( $include ) {
		$categories = array_merge(
			$categories,
			[
				[
					'slug'  => 'cultivatewp',
					'title' => __( 'CultivateWP', 'cultivate_textdomain' ),
					'icon'  => \cwp_icon( [ 'icon' => 'cultivatewp', 'group' => 'color', 'force' => true ] )
				]
			]
		);
	}

	return $categories;
}
add_filter( 'block_categories_all', __NAMESPACE__ . '\block_categories' );
