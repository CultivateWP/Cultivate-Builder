<?php
/**
 * About block
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$classes = [ 'block-about' ];
if ( ! empty( $block['className'] ) ) {
	$classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}
if ( ! empty( $block['backgroundColor'] ) ) {
	$classes[] = 'has-background';
	$classes[] = 'has-' . $block['backgroundColor'] . '-background-color';
}

$image = get_field( 'image' );
if ( ! empty( $image ) ) {
	$classes[] = 'block-about--has-image';
}

printf(
	'<div class="%s"%s>',
	esc_attr( join( ' ', $classes ) ),
	! empty( $block['anchor'] ) ? ' id="' . esc_attr( sanitize_title( $block['anchor'] ) ) . '"' : '',
);

if ( ! empty( $image ) ) {
	echo wp_get_attachment_image( $image, 'full', null, [ 'class' => 'block-about__image' ] );
}

$template = [
	[
		'core/heading',
		[
			'content' => 'Welcome to My Blog!',
		],
	],
	[
		'core/paragraph',
		[
			'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.',
		]
	],
	[
		'core/buttons',
		[],
		[
			[
				'core/button',
				[
					'text' => 'More About Me',
					'url' => home_url( 'about/' )
				]
			]
		]
	]
];
echo '<div class="block-about__content has-background has-background-background-color"><InnerBlocks class="cwp-inner" template="' . esc_attr( wp_json_encode( $template ) ) . '" /></div>';

echo '</div>';
