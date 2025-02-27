<?php
/**
 * 404
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/
use Cultivate\Block_Areas;

// Full width.
add_filter( 'cwp_page_layout', 'cwp_return_full_width_content' );

/**
 * 404 Content
 */
function cwp_404_content() {
	$output = Block_Areas\show( '404', $echo = false );
    if ( empty( $output ) ) {
        $output = '<p>' . esc_html__( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'simplegreensmoothies-2023' ) . '</p>';
		$output .= get_search_form( false );
    }

    if ( false === strpos( $output, '<h1' ) ) {
        $output = '<h1>Nothing Found</h1>' . $output;
    }

    echo '<div class="entry-content">' . $output . '</div>';
}
add_action( 'tha_content_loop', 'cwp_404_content' );
remove_action( 'tha_content_loop', 'cwp_default_loop' );

// Build the page.
require get_template_directory() . '/index.php';
