<?php
/**
 * Quick Links block
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/
use Cultivate\Blocks\Quick_Links;

$classes = [ 'block-quick-links', 'cwp-large' ];
if ( ! empty( $block['className'] ) ) {
	$classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}

$style = get_field( 'style' );
if ( ! empty( $style ) ) {
	$classes[] = 'style-' . $style;
}

$layout = get_field( 'layout' );
if ( ! empty( $layout ) ) {
	$classes[] = 'layout-' . $layout;
}

$inner_classes = [ 'block-quick-links__inner' ];
if ( $is_preview ) {
	$inner_classes[] = 'wp-block--disable-click';
}

$quicklinks_label_position = Quick_Links\label_position();
if ( ! empty( $quicklinks_label_position ) ) {
	$classes[] = 'label-position-' . $quicklinks_label_position;
}

printf(
	'<div class="%s"%s>',
	esc_attr( join( ' ', $classes ) ),
	! empty( $block['anchor'] ) ? ' id="' . esc_attr( sanitize_title( $block['anchor'] ) ) . '"' : '',
);

$template = [ [ 'core/heading', [ 'level' => 2, 'placeholder' => 'Title Goes Here' ] ] ];
echo '<InnerBlocks class="block-quick-links__header cwp-inner" template="' . esc_attr( wp_json_encode( $template ) ) . '" allowedBlocks="' . esc_attr( wp_json_encode( [ 'core/heading', 'core/paragraph' ] ) ) . '" />';

echo '<div class="' . esc_attr( join( ' ', $inner_classes ) ) . '">';
$items = get_field( 'items' );

$image_attr = [ 'class' => 'nopin', 'data-pin-nopin' => true ];
if ( 'flex' === $layout ) {
	if ( 'circle' === $style ) {
		$image_attr['sizes'] = '(max-width: 600px) 33vw, ' . round( 100 / count( $items ) ) . 'vw';
	} else {
		$image_attr['sizes'] = '(max-width: 600px) 50vw, ' . round( 100 / count( $items ) ) . 'vw';
	}
}

$image_size = 'square' === $style ? 'cwp_archive' : 'cwp_square';
$types = Quick_Links\types();
if ( ! empty( $items ) ) {
	foreach( $items as $item ) {
		if ( in_array( $item['type'], $types['taxonomy'] ) ) {
			$taxonomy = $types['taxonomy'][ $item['type'] ];
			$term_id = $item[ $item['type'] ];
			$title = get_term_meta( $term_id, 'cwp_short_title', true );
			if ( empty( $title ) ) {
				$title = get_term_field( 'name', $term_id, $taxonomy );
			}
			if ( is_wp_error( $title ) ) {
				continue;
			}
			$url = get_term_link( $term_id, $taxonomy );
			if ( is_wp_error( $url ) ) {
				continue;
			}
			$image_id = get_term_meta( $term_id, 'image', true );

		} elseif( in_array( $item['type'], $types['post_object'] ) ) {
			$post_id = $item[ $item['type'] ];
			$title = get_the_title( $post_id );
			$url = get_permalink( $post_id );
			$image_id = get_post_thumbnail_id( $post_id );
		} elseif ( 'manual' === $item['type'] ) {
			$title = $item['title'];
			$url = $item['url'];
			$image_id = $item['image'];
		}

		if ( empty( $image_id ) ) {
			$image_id = get_option( 'options_cwp_default_image' );
		}

		if ( is_wp_error( $url ) ) {
			continue;
		}

		echo '<a href="' . esc_url( $url ) . '">' . wp_get_attachment_image( $image_id, $image_size, false, $image_attr ) . '<span>' . esc_html( $title ) . '</span></a>';
	}
}
echo '</div>';
echo '</div>';
