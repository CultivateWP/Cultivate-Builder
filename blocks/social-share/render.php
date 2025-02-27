<?php
/**
 * Social Share block
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use Cultivate\Blocks\Social_Share;

if ( function_exists( 'cwp_is_block_area' ) && ! cwp_is_block_area( 'after-post', $post_id ) ) {
	if ( $is_preview ) {
		echo '<p>This block only works in the "After Post" block area.</p>';
	}
	return;
}

$title = get_field( 'title' );
Social_Share\display( [ 'title' => esc_html( $title ), 'icon_size' => 32 ] );
