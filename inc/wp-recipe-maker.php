<?php
/**
 * WP Recipe Maker
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use Cultivate\Block_Areas;

/**
 * WPRM Block Areas
 */
function cwp_wprm_block_areas( $block_areas ) {
	if ( class_exists( 'WPRM_Recipe_Manager' ) ) {
		$block_areas[] = 'before-wprm-recipe';
		$block_areas[] = 'after-wprm-recipe';
	}
	return $block_areas;
}
add_filter( 'cultivate_block_areas', 'cwp_wprm_block_areas' );

/**
 * Add blocks before recipe card
 *
 * @param string $output Output.
 */
function cwp_recipe_card_blocks( $output, $recipe ) {
	if ( ! is_single() ) {
		return $output;
	}

	if ( 'food' !== $recipe->type() ) {
		return $output;
	}

	$before = Block_Areas\show( 'before-wprm-recipe', false );
	$after  = Block_Areas\show( 'after-wprm-recipe', false );
	$output = $before . $output . $after;

	return $output;
}
add_filter( 'wprm_recipe_shortcode_output', 'cwp_recipe_card_blocks', 10, 2 );

/**
 * Icons
 */
function cwp_wprm_icons( $sources ) {
	$sources[] = [
		'dir' => get_template_directory() . '/assets/icons/wprm',
		'url' => get_template_directory_uri() . '/assets/icons/wprm/',
	];
	return $sources;
}
add_filter( 'wprm_icon_sources', 'cwp_wprm_icons' );

/**
 * Nutrition shortcode
 */
function cwp_food_nutrition() {
	$label = do_shortcode( '[wprm-nutrition-label style="simple" header="Nutrition" header_style="none" label_color="var(--wp--preset--color--foreground)" nutrition_separator=", " value_color="var(--wp--custom--color--neutral-700)" text_style="bold"]' );
	if ( ! empty( $label ) ) {
		return '<div class="cwp-food-nutrition">' . $label . '<p class="cwp-food-nutrition__disclaimer">Nutrition information is automatically calculated, so should only be used as an approximation.</p></div>';
	}
}
add_shortcode( 'cwp_food_nutrition', 'cwp_food_nutrition' );

/**
 * Food Favorite
 */
function cwp_food_favorite( $atts = [] ) {

	$service = cwp_favorites_service();
	if( empty( $service ) ) {
		return;
	}

	$atts = shortcode_atts(
		[
			'text' => 'Save',
			'text_added' => 'Saved',
			'text_color' => '',
			'icon' => '',
			'icon_added' => '',
			'icon_color' => ''
		],
		$atts
	);

	$shortcode = 'slickstream' === $service ? '[wprm-recipe-slickstream-favorites' : '[wprm-recipe-grow.me';
	foreach( $atts as $key => $value ) {
		$shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
	}
	$shortcode .= ']';
	return do_shortcode( $shortcode );
}
add_shortcode( 'cwp_food_favorite', 'cwp_food_favorite' );
