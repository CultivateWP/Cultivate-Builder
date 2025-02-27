<?php
/**
 * Archive
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Full width.
add_filter( 'cwp_page_layout', 'cwp_return_full_width_content' );

/**
 * Body Class
 *
 * @param array $classes Body classes.
 */
function cwp_archive_body_class( $classes ) {
	$classes[] = 'archive';
	return $classes;
}
add_filter( 'body_class', 'cwp_archive_body_class' );

// Cultivate Category Page Title
add_filter(
	'cultivate_pro/landing/archive_title',
	function( $title ) {
		return 'All ' . get_the_archive_title();
	}
);

// Build the page.
require get_template_directory() . '/index.php';
