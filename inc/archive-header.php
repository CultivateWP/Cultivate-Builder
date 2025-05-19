<?php
/**
 * Archive Header
 *
 * @package      CultivateBuilder
 * @subpackage   archive-header/5
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Archive Header
 */
function cwp_archive_header() {

	if ( is_singular() ) {
		return;
	}

	// Breadcrumbs in archive header
	remove_action( 'tha_content_top', 'cwp_breadcrumbs' );
	add_action( 'cwp_archive_header_before', 'cwp_breadcrumbs' );

	$title       = false;
	$description = false;
	$image       = false;
	$icon        = false;

	if ( is_home() && get_option( 'page_for_posts' ) ) {
		$post_id = get_option( 'page_for_posts' );
		$title = get_post_meta( $post_id, 'cwp_archive_title', true );
		if ( empty( $title ) ) {
			$title = get_the_title( $post_id );
		}
		$description = get_post_meta( $post_id, 'cwp_archive_description', true );


	} elseif ( is_search() ) {
		$title = 'Search Results: ' . get_search_query();

	} elseif ( is_archive() ) {
		$title = false;
		if ( is_category() || is_tag() || is_tax() ) {
			$title = get_term_meta( get_queried_object_id(), 'cwp_archive_headline', true );
			$image = get_term_meta( get_queried_object_id(), 'image', true );
			$icon  = cwp_icon( [ 'icon' => get_queried_object()->slug, 'group' => 'category', 'size' => 64 ] );
		}
		if ( empty( $title ) ) {
			$title = get_the_archive_title();
		}
		if ( ! get_query_var( 'paged' ) ) {
			$description = get_the_archive_description();
		}
	}

	if ( empty( $title ) && empty( $description ) ) {
		return;
	}

	echo '<div class="archive-header"><div class="wrap">';
	do_action( 'cwp_archive_header_before' );

	echo '<div class="archive-header__inner">';
	if ( ! empty( $icon ) ) {
		echo '<div class="category-icon">' . $icon . '</div>';
	}
	if ( ! empty( $title ) ) {
		echo '<h1 class="archive-title">' . esc_html( wp_strip_all_tags( $title ) ) . '</h1>';
	}
	if ( ! empty( $description ) ) {
		echo '<div class="archive-description">' . wp_kses_post( apply_filters( 'cwp_the_content', $description ) ) . '</div>';
	}
	echo '</div>';

	do_action( 'cwp_archive_header_after' );
	echo '</div></div>';

}
add_action( 'tha_header_after', 'cwp_archive_header', 16 );
