<?php
/**
 * Post Listing block
 *
 * @package      CultivateBuilder
 * @subpackage   blocks/post-listing/01
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use Cultivate\Blocks\Post_Listing;

$settings = Post_Listing\settings();
$args['block'] = $block;
$args['query_args'] = [
	'post_status' => 'publish',
];

$args['title'] = get_field( 'title' );
$args['read_more_text'] = get_field( 'read_more_text' );
$args['read_more_url'] = get_field( 'read_more_url' );
$args['layout'] = get_field( 'layout' );
if( empty( $args['layout'] ) ) {
	$args['layout'] = array_key_first( $settings['layouts'] );
}
$args['orderby'] = get_field( 'orderby' );
$args['post__in'] = get_field( 'post__in' );

$args['post_type'] = get_field( 'post_type' );
if ( empty( $args['post_type'] ) ) {
	$args['post_type'] = array_key_first( $settings['post_types'] );
}
foreach( $settings['post_types'] as $post_type => $taxonomies ) {
	if ( $post_type === $args['post_type'] ) {
		foreach( $taxonomies as $taxonomy ) {
			$args[ $taxonomy['field'] ] = get_field( $taxonomy['field'] );
		}
	}
}

Post_Listing\display( $args, $post_id, $is_preview );
