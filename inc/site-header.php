<?php
/**
 * Site Header
 *
 * @package      CultivateBuilder
 * @subpackage   site-header/06
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Register nav menus
 */
function cwp_register_menus() {
	register_nav_menus(
		[
			'primary'   => esc_html__( 'Primary Navigation Menu', 'cultivate_textdomain' ),
			'secondary' => esC_html__( 'Secondary Navigation Menu', 'cultivate_textdomain' )
		]
	);

}
add_action( 'after_setup_theme', 'cwp_register_menus' );

/**
 * Menu Extras
 */
function cwp_menu_extras( $output, $menu ) {
	if ( 'primary' !== $menu->theme_location ) {
		return $output;
	}

	$search = cwp_search_toggle();
	if ( ! empty( $search ) ) {
		$output .= '<li class="menu-item menu-item-search">' . $search . '</li>';
	}

	return $output;
}
add_filter( 'wp_nav_menu_items', 'cwp_menu_extras', 10, 2 );

/**
 * Site Logo
 */
function cwp_site_logo( $icon = 'primary' ) {

	$image_id = get_option( 'options_cwp_site_logo' );
	if ( empty( $image_id ) ) {
		return get_bloginfo( 'name' );
	}

	return wp_get_attachment_image( $image_id, 'large', false, [ 'data-pin-nopin' => true ] );
}

/**
 * Mobile Menu
 */
function cwp_site_header() {

	$classes = [ 'site-header__logo' ];
	$image_id = get_option( 'options_cwp_site_logo' );
	if ( empty( $image_id ) ) {
		$classes[] = 'site-header__logo-text';
	}
	echo '<a href="' . esc_url( home_url() ) . '" rel="home" class="' . esc_attr( join( ' ', $classes ) ) . '" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . ' Home">' . cwp_site_logo() . '</a>';
	echo cwp_search_toggle();
	echo cwp_mobile_menu_toggle();

	echo '<nav class="nav-menu" role="navigation">';
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'container_class' => 'nav-primary' ) );
	}
	if ( has_nav_menu( 'secondary' ) ) {
		wp_nav_menu( array( 'theme_location' => 'secondary', 'menu_id' => 'secondary-menu', 'container_class' => 'nav-secondary' ) );
	}
	echo '</nav>';

	echo '<div class="header-search">' . cwp_render_search() . '</div>';

}
add_action( 'tha_header_bottom', 'cwp_site_header', 11 );

/**
 * Favorite Toggle
 */
function cwp_favorite_toggle() {
	$service = cwp_favorites_service();
	if ( empty( $service ) ) {
		return;
	}

	$classes = [ 'favorite-toggle' ];
	if ( 'grow' === $service ) {
		$classes[] = 'grow-bookmarks-open';
	}

	$output  = '<button class="' . esc_attr( join( ' ', $classes ) ) . '">';
	$output .= cwp_icon( ['icon' => 'heart-empty' ] );
	$output .= '<span class="screen-reader-text">My Favorites</span>';
	$output .= '</button>';
	return $output;
}

/**
 * Search toggle
 */
function cwp_search_toggle() {
	$output  = '<button aria-label="Search" class="search-toggle">';
	$output .= cwp_icon( array( 'icon' => 'search-fat', 'class' => 'open' ) );
	$output .= cwp_icon( array( 'icon' => 'close-circle', 'class' => 'close' ) );
	$output .= '</button>';
	return $output;
}

/**
 * Mobile menu toggle
 */
function cwp_mobile_menu_toggle() {
	$output  = '<button aria-label="Menu" class="menu-toggle">';
	$output .= cwp_icon( array( 'icon' => 'menu', 'class' => 'open' ) );
	$output .= cwp_icon( array( 'icon' => 'close-circle', 'class' => 'close' ) );
	$output .= '</button>';
	return $output;
}

/**
 * Add a dropdown icon to top-level menu items.
 *
 * @param string $output Nav menu item start element.
 * @param object $item   Nav menu item.
 * @param int    $depth  Depth.
 * @param object $args   Nav menu args.
 * @return string Nav menu item start element.
 * Add a dropdown icon to top-level menu items
 */
function cwp_nav_add_dropdown_icons( $output, $item, $depth, $args ) {

	if ( ! isset( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $output;
	}

	if ( 1 === $args->depth ) {
		return $output;
	}

	if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {

		// Add SVG icon to parent items.
		$icon = cwp_icon( array( 'icon' => 'chevron-down', 'size' => 12 ) );

		// Optional - two icons based on open/close state
		//$icon = cwp_icon( [ 'icon' => 'plus', 'class' => 'open' ] ) . cwp_icon( [ 'icon' => 'minus', 'class' => 'close' ] );

		$output .= sprintf(
			'<button aria-label="Submenu Dropdown" class="submenu-expand" tabindex="-1">%s</button>',
			$icon
		);
	}

	return $output;
}
add_filter( 'walker_nav_menu_start_el', 'cwp_nav_add_dropdown_icons', 10, 4 );
