<?php
/**
 * ACF Customizations
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace Cultivate\ACF;

// Disable CPT and taxonomy functionality
add_filter( 'acf/settings/enable_post_types', '__return_false' );

// Don't output empty message on blocks
add_filter( 'acf/blocks/no_fields_assigned_message', '__return_empty_string' );

/**
 * Remove ACF admin menu
 */
function remove_acf_admin_menu() {
	if ( ! ( function_exists( 'wp_get_environment_type' ) && 'production' === wp_get_environment_type() ) ) {
		return;
	}

	$slug = 'edit.php?post_type=acf-field-group';
	remove_submenu_page( $slug, $slug );
	remove_submenu_page( $slug, 'post-new.php?post_type=acf-field-group' );
}
add_action( 'admin_menu', __NAMESPACE__ . '\remove_acf_admin_menu' );

/**
 * Register Options Page
 */
function register_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			[
				'title'      => __( 'Site Options', 'cultivate_textdomain' ),
				'capability' => 'manage_options',
			]
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\register_options_page' );

/**
 * Dynamic Icons
 *
 * @param array $field ACF Field.
 */
function dynamic_icons( $field ) {
	if ( 0 !== strpos( $field['name'], 'dynamic_icon_' ) ) {
		return $field;
	}

	$type  = str_replace( 'dynamic_icon_', '', $field['name'] );
	$icons = get_icons( $type );

	$field['choices'] = [ 0 => '(None)' ];
	foreach ( $icons as $icon ) {
		$field['choices'][ $icon ] = $icon;
	}

	return $field;
}
add_filter( 'acf/load_field', __NAMESPACE__ . '\dynamic_icons' );

/**
 * Get Theme Icons
 * Refresh cache by bumping CWP_ICON_VERSION
 *
 * @param string $directory Directory.
 */
function get_icons( $directory = 'utility' ) {
	$theme   = wp_get_theme();
	$icons   = get_option( 'cwp_theme_icons_' . $directory );
	$version = get_option( 'cwp_theme_icons_' . $directory . '_version' );

	if ( empty( $icons ) || version_compare( $theme->get( 'Version' ), $version ) ) {
		if ( ! is_dir( get_stylesheet_directory() . '/assets/icons/' . $directory ) ) {
			return [];
		}
		$icons = scandir( get_stylesheet_directory() . '/assets/icons/' . $directory );
		if ( empty( $icons ) ) {
			return [];
		}
		$icons = array_diff( $icons, array( '..', '.' ) );
		$icons = array_values( $icons );
		if ( empty( $icons ) ) {
			return $icons;
		}

		// remove the .svg.
		foreach ( $icons as $i => $icon ) {
			$icons[ $i ] = substr( $icon, 0, -4 );
		}
		update_option( 'cwp_theme_icons_' . $directory, $icons );
		update_option( 'cwp_theme_icons_' . $directory . '_version', $theme->get( 'Version' ) );
	}
	return $icons;
}

/**
 * Search Query
 */
function search_query( $args ) {
	if( !empty( $args['s'] ) ) {
		$args['orderby'] = 'relevance';
	}
	return $args;
}
add_filter( 'acf/fields/post_object/query', __NAMESPACE__ . '\search_query' );

/**
 * Dynamic Color Field
 */
function dynamic_color( $field ) {
	$use_for = [ 'cwp_top_hat_bg', 'dynamic_color' ];
	if ( ! in_array( $field['name'], $use_for, true ) ) {
		return $field;
	}

//	$field['instructions'] = 'Please see <a href="https://cultivatewp.com/style-guide/?cwp_url=' . esc_url_raw( home_url() ) . '" target="_blank">Your Style Guide</a> for list of color options.';

	$color_palette = [];
	if ( class_exists( '\WP_Theme_JSON_Resolver' ) ) {
		$settings = \WP_Theme_JSON_Resolver::get_theme_data()->get_settings();
		if ( isset( $settings['color']['palette']['theme'] ) ) {
			$color_palette = $settings['color']['palette']['theme'];
		}
	}
	if( ! empty( $color_palette ) ) {
		foreach( $color_palette as $color ) {
			$field['choices'][ $color['slug'] ] = $color['name'];
		}
	}

	return $field;
}
add_filter( 'acf/load_field', __NAMESPACE__ . '\dynamic_color' );
