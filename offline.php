<?php
/**
 * PWA Offline
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Offline Content
 */
function cwp_pwa_offline_content() {
	echo '<h1>Oops, it looks like you\'re offline</h1>';
	if ( function_exists( 'wp_service_worker_error_message_placeholder' ) ) {
		wp_service_worker_error_message_placeholder();
	}

}
add_action( 'tha_content_loop', 'cwp_pwa_offline_content' );
remove_action( 'tha_content_loop', 'cwp_default_loop' );

// Build the page.
require get_template_directory() . '/index.php';
