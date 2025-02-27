<?php
/**
 * Navigation
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Archive Paginated Navigation
 */
function cwp_archive_paginated_navigation() {

	if ( ! ( is_home() || is_archive() || is_search() ) ) {
		return;
	}

	$settings = array(
		'count' => 7,
		'prev_text' => '<span class="screen-reader-text">Go to Previous Page</span>' . cwp_icon( [ 'icon' => 'chevron-left' ] ),
		'next_text' => '<span class="screen-reader-text">Go to Next Page</span>' . cwp_icon( [ 'icon' => 'chevron-right' ] ),
	);

	global $wp_query;
	$current = max( 1, get_query_var( 'paged' ) );
	$total = $wp_query->max_num_pages;
	$links = array();

	// Offset for next link
	if( $current < $total )
		$settings['count']--;

	if( $current + 3 < $total ) {
		$settings['count'] = $settings['count'] - 2;
	}

	// Previous
	if( $current > 1 ) {
		$settings['count']--;
		$links[] = cwp_archive_navigation_link( $current - 1, 'pagination-previous', $settings['prev_text'] );

		$settings['count']--;
		$links[] = cwp_archive_navigation_link( $current - 1 );
	}

	// Current
	$links[] = cwp_archive_navigation_link( $current, 'active' );

	// Next Pages
	for( $i = 1; $i < $settings['count']; $i++ ) {
		$page = $current + $i;
		if( $page <= $total ) {
			$links[] = cwp_archive_navigation_link( $page );
		}
	}

	// Next
	if( $current < $total ) {

		if( $current + 3 < $total ) {
			$links[] = '<li class="pagination-omission">&hellip;</li>';
			$links[] = cwp_archive_navigation_link( $total );
		}
		$links[] = cwp_archive_navigation_link( $current + 1, 'pagination-next', $settings['next_text'] );
	}

	if ( 2 > count( $links ) ) {
		return;
	}

	echo '<nav class="archive-pagination pagination" role="navigation">';
    	echo '<h2 class="screen-reader-text">Posts navigation</h2>';
    	echo '<ul>' . join( '', $links ) . '</ul>';
	echo '</nav>';
}
add_action( 'tha_content_while_after', 'cwp_archive_paginated_navigation' );

/**
 * Archive Navigation Link
 *
 * @author Bill Erickson
 * @link https://www.billerickson.net/custom-pagination-links/
 *
 * @param int $page
 * @param string $class
 * @param string $label
 * @return string $link
 */
function cwp_archive_navigation_link( $page = false, $class = '', $label = '' ) {

	if( ! $page )
		return;

	$label = $label ? $label : $page;
	$link = esc_url_raw( get_pagenum_link( $page ) );

	$output = '';
	if ( ! empty( $class ) ) {
		$output .= '<li class="' . esc_attr( $class ) . '">';
	} else {
		$output .= '<li>';
	}
	$output .= '<a href="' . $link . '">' . $label . '</a></li>';

	return $output;
}
