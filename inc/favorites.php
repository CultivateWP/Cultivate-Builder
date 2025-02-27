<?php
/**
 * Favorite
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Localize Script
 */
function cwp_favorite_localize_script() {
	wp_localize_script( 'theme-global', 'cwp', [ 'favorites' => cwp_favorites_service(), 'search' => cwp_search_service() ] );
}
add_action( 'wp_enqueue_scripts', 'cwp_favorite_localize_script', 11 );

/**
 * Favorites service
 */
function cwp_favorites_service() {

	$service = get_option( 'options_cwp_favorite_service' );

	if( 'slickstream' === $service && is_plugin_active( 'slick-engagement/slick-engagement.php' ) ) {
		return 'slickstream';
	} elseif( 'grow' === $service ) {
		return 'grow';
	} else {
		return false;
	}
}

/**
 * Search Service
 */
function cwp_search_service() {
	$service = get_option( 'options_cwp_search_service' );

	if( 'slickstream' === $service && is_plugin_active( 'slick-engagement/slick-engagement.php' ) ) {
		return 'slickstream';
	} elseif( 'grow' === $service ) {
		return 'grow';
	} else {
		return 'wordpress';
	}

}
