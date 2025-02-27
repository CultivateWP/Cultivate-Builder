<?php
/**
 * Tasty Recipes
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use Cultivate\Block_Areas;

/**
 * Tasty Recipes Block Areas
 */
function cwp_tasty_block_areas( $block_areas ) {
	if ( class_exists( 'Tasty_Recipes' ) ) {
		$block_areas[] = 'before-tasty-recipe';
	}
	return $block_areas;
}
add_filter( 'cultivate_block_areas', 'cwp_tasty_block_areas' );

/**
 * Tasty Before Recipe
 */
function cwp_tasty_before_recipe( $before_recipe ) {
	if ( tasty_recipes_is_print() ) {
		return $before_recipe;
	}

	$block_area = Block_Areas\show( 'before-tasty-recipe', false );
	return $block_area . $before_recipe;
}
add_filter( 'tasty_recipes_before_recipe', 'cwp_tasty_before_recipe' );
