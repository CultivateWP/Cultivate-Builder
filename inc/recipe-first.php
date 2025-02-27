<?php
/**
 * Recipe First
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/
namespace Cultivate\Recipe_First;

/**
 * Is WPRM the first block
 */
function is_first() {
	if ( ! is_singular() ) {
		return false;
	}

	global $post;
	$blocks = parse_blocks( $post->post_content );
	if ( empty( $blocks ) ) {
		return false;
	}
	return 'wp-recipe-maker/recipe' === $blocks[0]['blockName'];
}

/**
 * Remove post header
 */
function remove_post_header() {
	if ( is_first() ) {
		remove_action( 'wp_head', 'cwp_post_header_elements' );
		remove_action( 'tha_entry_top', 'cwp_entry_header' );
		remove_action( 'tha_content_top', 'cwp_breadcrumbs' );
		add_action( 'tha_header_after', 'cwp_breadcrumbs' );
	}
}
add_action( 'wp_head', __NAMESPACE__ . '\remove_post_header', 1 );

/**
 * Body Class
 */
function body_class( $classes ) {
	if ( is_first() ) {
		$classes[] = 'has-recipe-first';
	}
	return $classes;
}
add_filter( 'body_class', __NAMESPACE__ . '\body_class', 40 );

/**
 * WPRM Recipe Name
 */
function recipe_name( $output ) {
	if ( is_first() ) {
		$output = str_replace(
			[
				'<h2',
				'</h2'
			],
			[
				'<h1',
				'</h1'
			],
			$output
		);
	}

	return $output;
}
add_filter( 'wprm_recipe_name_shortcode', __NAMESPACE__ . '\recipe_name' );

/**
 * WPRM Recipe H2
 */
function recipe_h2( $output ) {
	if ( is_first() ) {
		$output = str_replace(
			[
				'<h3',
				'</h3',
				'<h4',
				'</h4'
			],
			[
				'<h2',
				'</h2',
				'<h3',
				'</h3'
			],
			$output
		);
	}

	return $output;

}
add_filter( 'wprm_recipe_equipment_shortcode', __NAMESPACE__ . '\recipe_h2', 90 );
add_filter( 'wprm_recipe_ingredients_shortcode', __NAMESPACE__ . '\recipe_h2', 90 );
add_filter( 'wprm_recipe_instructions_shortcode', __NAMESPACE__ . '\recipe_h2', 90 );
add_filter( 'wprm_recipe_video_shortcode', __NAMESPACE__ . '\recipe_h2', 90 );
add_filter( 'wprm_recipe_notes_shortcode', __NAMESPACE__ . '\recipe_h2', 90 );

/**
 * WPRM Ingredients ID
 */
function ingredients_id( $output, $atts ) {
	$id = 'recipe-ingredients';
	return str_replace( '<' . $atts['header_tag'] . ' class="wprm-recipe-header', '<' . $atts['header_tag'] . ' id="' . esc_attr( $id ) . '" class="wprm-recipe-header', $output );
}
add_filter( 'wprm_recipe_ingredients_shortcode', __NAMESPACE__ . '\ingredients_id', 10, 2 );

/**
 * WPRM Instructions ID
 */
function instructions_id( $output, $atts ) {
	$id = 'recipe-instructions';
	return str_replace( '<' . $atts['header_tag'] . ' class="wprm-recipe-header', '<' . $atts['header_tag'] . ' id="' . esc_attr( $id ) . '" class="wprm-recipe-header', $output );
}
add_filter( 'wprm_recipe_instructions_shortcode', __NAMESPACE__ . '\instructions_id', 10, 2 );
