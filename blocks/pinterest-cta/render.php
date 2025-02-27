<?php
/**
 * Pinterest CTA block
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use Cultivate\Blocks\Social_Share;

if ( ! function_exists( 'Cultivate\Blocks\Social_Share\display' ) ) {
	return;
}

$classes = [ 'block-pinterest-cta' ];
if ( ! empty( $block['className'] ) ) {
	$classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}

printf(
	'<div class="%s"%s>',
	esc_attr( join( ' ', $classes ) ),
	! empty( $block['anchor'] ) ? ' id="' . esc_attr( sanitize_title( $block['anchor'] ) ) . '"' : '',
);

if ( function_exists( 'cwp_icon' ) ) {
	echo '<div class="block-pinterest-cta__icon">' . cwp_icon( [ 'icon' => 'pinterest', 'size' => 64 ] ) . '</div>';
}

echo '<p class="block-pinterest-cta__title">' . esc_html__( 'Pin this now to find it later', 'cultivate_textdomain' ) . '</p>';
echo Social_Share\link( 'pinterest', [ 'hide_icon' => true, 'label' => 'Pin It', 'hide_label' => false, 'class' => 'wp-element-button' ] );
echo '</div>';
