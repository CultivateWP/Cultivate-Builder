<?php
/**
 * Base template
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

get_header();

tha_content_before();

	echo '<div class="content-area">';
	tha_content_wrap_before();
	if ( apply_filters( 'cwp_display_sidebar_first', false ) ) {
		get_sidebar();
	}
	echo '<main class="site-main" role="main">';
	tha_content_top();
	tha_content_loop();
	tha_content_bottom();
	echo '</main>';
	if ( ! apply_filters( 'cwp_display_sidebar_first', false ) ) {
		get_sidebar();
	}
	tha_content_wrap_after();
	echo '</div>';
tha_content_after();

get_footer();
