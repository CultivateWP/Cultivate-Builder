<?php
/**
 * Content Image block
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$classes = [ 'block-ci', 'cwp-inner' ];
if ( ! empty( $block['className'] ) ) {
    $classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}

$reverse = get_field( 'reverse' );
if ( $reverse ) {
    $classes[] = 'block-ci--reverse';
}


$template = [
	[
		'core/group',
		[
			'className' => 'block-ci__image',
		],
		[
			[
				'core/image',
				[
					'url' => 'https://p198.p4.n0.cdn.getcloudapp.com/items/Blu2pJNN/f9e08b43-1abf-4982-92cd-b71796a78c0f.png',
					'aspectRatio' => '1',
					'scale' => 'cover'
				]
			]
		]
	],
	[
		'core/group',
		[
			'className' => 'block-ci__content',
		],
		[
			[
				'core/heading',
				[
					'content' => 'Title goes here',
				]
			],
			[
				'core/paragraph',
				[
					'content' => 'Content goes here'
				]
			]
		]
	]
];

echo '<InnerBlocks class="' . esc_attr( join( ' ', $classes ) ) . '" template="' . esc_attr( wp_json_encode( $template ) ) . '" />';
