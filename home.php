<?php
/**
 * Home
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Home Content
 */
function cwp_home_content() {

	if ( get_query_var( 'paged' ) ) {
		return;
	}

	$page_id = get_option( 'page_for_posts' );
	if ( empty( $page_id ) ) {
		return;
	}

	$loop = new WP_Query( [ 'post_type' => 'page', 'p' => $page_id ] );
	if ( $loop->have_posts() ) {
		while( $loop->have_posts() ) {
			$loop->the_post();

			global $post;
			if ( ! empty( $post->post_content ) ) {
				echo '<div class="block-area block-area-home">';
				the_content();
				echo '</div>';
				echo '<header id="recent" class="archive-recent-header"><h2>All Recipes</h2></header>';
			}

		}
	}
	wp_reset_postdata();
}
add_action( 'tha_content_while_before', 'cwp_home_content', 20 );

// Build the page.
require get_template_directory() . '/archive.php';
