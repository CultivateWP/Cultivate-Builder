<?php
/**
 * Tip block
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$classes = [ 'block-tip', 'cwp-inner' ];
if ( ! empty( $block['className'] ) ) {
	$classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}
if ( ! empty( $block['align'] ) ) {
	$classes[] = 'align' . $block['align'];
}
if ( ! empty( $block['backgroundColor'] ) ) {
	$classes[] = 'has-background';
	$classes[] = 'has-' . $block['backgroundColor'] . '-background-color';
}
if ( ! empty( $block['textColor'] ) ) {
	$classes[] = 'has-text-color';
	$classes[] = 'has-' . $block['textColor'] . '-color';
}
$template = [
	[
		'core/heading',
		[
			'content'   => 'Recipe Tip',
			'className' => 'block-tip__title'
		]
	],
	[
		'core/paragraph',
		[
			'placeholder' => 'Tip content goes here',
		]
	]
];

echo '<InnerBlocks class="' . esc_attr( join( ' ', $classes ) ) . '" template="' . esc_attr( wp_json_encode( $template ) ) . '" />';
