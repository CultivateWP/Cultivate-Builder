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

namespace Cultivate\Blocks\Post_Listing;
use Cultivate\Affiliate_Links;

/**
 * Aspect Ratios:
 * - square 1:1 = 1
 * - wide 4:3 = 1.33
 * - wide 3:2 = 1.5
 * - wide 21:9 = 2.33
 * - tall 3:4 = 0.75
 * - tall 2:3 = 0.67
 */

/**
 * Post Listing Settings
 */
function settings() {

	// Aspect ratio set in theme.json
	$aspect_ratio = 1;
	if ( class_exists( '\WP_Theme_JSON_Resolver' ) ) {
		$settings = \WP_Theme_JSON_Resolver::get_theme_data()->get_settings();
		if ( isset( $settings['custom']['aspect-ratio'] ) ) {
			$aspect_ratio = $settings['custom']['aspect-ratio'];
		}
	}

	$settings = [
		'aspect_ratio'   => $aspect_ratio,
		'archive_layout' => '4up-grid',
		'read_more'      => [
			'locations' => [ 'footer' ],
			'button_style'    => 'link',
		],
		'post_types'     => [
			'post' => [
				[
					'tax'   => 'category',
					'field' => 'category',
					'label' => 'Category',
				],
				[
					'tax'   => 'post_tag',
					'field' => 'post_tag',
					'label' => 'Tag',
				]
			]
		],
		'layouts'         => [
			'1up-featured' => [
				'label' => __( '1up Featured', 'cultivate_textdomain' ),
				'posts_per_page' => 1,
				'sizes' => '(max-width: 1200px) 100vw, 1200px'
			],
			'3up-full' => [
				'label' => __( '3up Full', 'cultivate_textdomain' ),
				'posts_per_page' => 3,
				'sizes' => '(max-width: 600px) 100vw, 378px'
			],
			'3up-featured' => [
				'label' => __( '3up Featured', 'cultivate_textdomain' ),
				'posts_per_page' => 3,
				'sizes' => '(max-width: 600px) 100vw, 378px'
			],
			'3up-list' => [
				'label' => __( '3up List', 'cultivate_textdomain' ),
				'posts_per_page' => 3,
				'sizes' => '(max-width: 600px) 138px, 378px'
			],
			'4up-2x2' => [
				'label' => __( '4up 2x2 Large', 'cultivate_textdomain' ),
				'posts_per_page' => 4,
				'sizes' => '(max-width: 600px) 100vw, 50vw'
			],
			'4up-2x2-list' => [
				'label' => __( '4up 2x2 List', 'cultivate_textdomain' ),
				'posts_per_page' => 4,
				'sizes' => '(max-width: 600px) 138px, 276px'
			],
			'4up-full' => [
				'label' => __( '4up Full', 'cultivate_textdomain' ),
				'posts_per_page' => 4,
				'sizes' => '(max-width: 600px) 100vw, 276px'
			],
			'4up-featured' => [
				'label' => __( '4up Featured', 'cultivate_textdomain' ),
				'posts_per_page' => 4,
				'sizes' => '(max-width: 600px) 100vw, 276px'
			],
			'4up-grid' => [
				'label' => __( '4up Grid', 'cultivate_textdomain' ),
				'posts_per_page' => 4,
				'sizes' => '(max-width: 600px) 50vw, 276px'
			],
			'4up-list' => [
				'label' => __( '4up List', 'cultivate_textdomain' ),
				'posts_per_page' => 4,
				'sizes' => '(max-width: 600px) 138px, 276px'
			],
			'4up-text' => [
				'label' => __( '4up Text', 'cultivate_textdomain' ),
				'posts_per_page' => 4,
			],
			'5up-featured' => [
				'label' => __( '5up Featured', 'cultivate_textdomain' ),
				'posts_per_page' => 5,
				'sizes' => '(max-width: 600px) 100vw, 276px'
			],
			'5up-list' => [
				'label' => __( '5up List', 'cultivate_textdomain' ),
				'posts_per_page' => 5,
				'sizes' => '(max-width: 600px) 138px, 214px'
			],
			'6up-featured' => [
				'label' => __( '6up Featured', 'cultivate_textdomain' ),
				'posts_per_page' => 6,
				'sizes' => '(max-width: 600px) 100vw, 186px'
			],
			'6up-grid' => [
				'label' => __( '6up Grid', 'cultivate_textdomain' ),
				'posts_per_page' => 6,
				'sizes' => '(max-width: 600px) 50vw, 186px'
			],
			'6up-list' => [
				'label' => __( '6up List', 'cultivate_textdomain' ),
				'posts_per_page' => 6,
				'sizes' => '(max-width: 600px) 138px, 186px'
			],
			'6up-text' => [
				'label' => __( '6up Text', 'cultivate_textdomain' ),
				'posts_per_page' => 6
			],
			'sidebar-4up-grid' => [
				'label' => __( 'Sidebar 4up Grid', 'cultivate_textdomain' ),
				'posts_per_page' => 4,
				'sizes' => '(max-width: 600px) 164px, 164px'
			],
			'sidebar-4up-list' => [
				'label' => __( 'Sidebar 4up List', 'cultivate_textdomain' ),
				'posts_per_page' => 4,
				'sizes' => '(max-width: 600px) 138px, 138px'
			],
			'sidebar-4up-full' => [
				'label' => __( 'Sidebar 4up Full', 'cultivate_textdomain' ),
				'posts_per_page' => 4,
				'sizes' => '(max-width: 600px) 336px, 336px'
			],
			'sidebar-6up-grid' => [
				'label' => __( 'Sidebar 6up Grid', 'cultivate_textdomain' ),
				'posts_per_page' => 6,
				'sizes' => '(max-width: 600px) 164px, 164px'
			],
			'sidebar-6up-list' => [
				'label' => __( 'Sidebar 6up List', 'cultivate_textdomain' ),
				'posts_per_page' => 6,
				'sizes' => '(max-width: 600px) 138px, 138px'
			],
			'sidebar-6up-full' => [
				'label' => __( 'Sidebar 6up Full', 'cultivate_textdomain' ),
				'posts_per_page' => 6,
				'sizes' => '(max-width: 600px) 336px, 336px'
			],
			'archive' => [
				'label' => __( 'Archive', 'cultivate_textdomain' ),
				'posts_per_page' => get_option( 'posts_per_page' ),
				'sizes' => ''
			]
		]
	];

	// Remove certain layouts based on aspect ratio
	// -- wide
	if ( $settings['aspect_ratio'] > 1 ) {
		unset( $settings['layouts']['3up-featured'] );
		unset( $settings['layouts']['4up-featured'] );
		unset( $settings['layouts']['4up-list'] );
		unset( $settings['layouts']['5up-featured'] );
		unset( $settings['layouts']['5up-list'] );
		unset( $settings['layouts']['6up-list'] );
		unset( $settings['layouts']['sidebar-4up-list'] );
		unset( $settings['layouts']['sidebar-6up-list'] );

	// -- tall
	} elseif( $settings['aspect_ratio'] < 1 ) {
		unset( $settings['layouts']['4up-2x2'] );
	}


	if ( function_exists( 'Cultivate\Affiliate_Links\post_type_slug' ) ) {
		$aff_post_type = Affiliate_Links\post_type_slug();
		$aff_tax = Affiliate_Links\taxonomy_slug();
		if ( ! empty( $aff_post_type ) ) {
			$settings['post_types'][ $aff_post_type ] = [];
			if ( ! empty( $aff_tax ) ) {
				$settings['post_types'][ $aff_post_type ][] = [
					'tax' => $aff_tax,
					'field' => $aff_tax,
					'label' => 'Shop Category'
				];
			}
		}
	}

	$settings = apply_filters( 'cultivate_pro/post_listing/settings', $settings );
	return $settings;
}

// Post Summary

/**
 * See available options in inc/template-tags.php
 *
 * Available hooks:
 * cwp_post_summary_title_before
 * cwp_post_summary_title_after
 */
add_action( 'cwp_post_summary_title_before',  'cwp_entry_category' );
add_action( 'cwp_post_summary_title_before',  __NAMESPACE__ . '\post_summary_recipe_key' );
add_action( 'cwp_post_summary_title_after',  __NAMESPACE__ . '\post_summary_excerpt', 20, 2 );
add_action( 'cwp_post_summary_title_after', __NAMESPACE__ . '\post_summary_buy_now', 10, 2 );

// Excerpt Length
add_filter(
	'excerpt_length',
	function() {
		return 20;
	}
);

/**
 * Image Sizes
 */
function register_image_sizes() {

	$settings = settings();
	$sizes = [
		'cwp_archive' => 378,
		'cwp_archive_sm' => 276,
		'cwp_archive_lg' => 600
	];
	foreach( $sizes as $name => $width ) {
		$height = round( $width * ( 1 / $settings['aspect_ratio'] ) );
		add_image_size( $name, $width, $height, true );
	}

}
add_action( 'init',  __NAMESPACE__ . '\register_image_sizes' );


/**
 * Register field group
 */
function register_field_group() {

	if( ! function_exists('acf_add_local_field_group' ) ) {
		return;
	}

	$settings = settings();

	$layout_choices = [];
	foreach( $settings['layouts'] as $layout => $atts ) {
			$layout_choices[ $layout ] = $atts['label'];
	}

	$fields = array(
		array(
			'key' => 'field_5e6a894753d11',
			'label' => __( 'Layout', 'cultivate_textdomain' ),
			'name' => 'layout',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => $layout_choices,
			'default_value' => '4up-featured',
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_5e6a895053d12',
			'label' => __( 'Order', 'cultivate_textdomain' ),
			'name' => 'orderby',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'recent' => __( 'Recent', 'cultivate_textdomain' ),
				'popular' => __( 'Popular', 'cultivate_textdomain' ),
				'modified' => __( 'Recently Updated', 'cultivate_textdomain' ),
				'manual' => __( 'Manual', 'cultivate_textdomain' ),
			),
			'default_value' => array(
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_5e6a897453d13',
			'label' => __( 'Posts', 'cultivate_textdomain' ),
			'name' => 'post__in',
			'type' => 'post_object',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5e6a895053d12',
						'operator' => '==',
						'value' => 'manual',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => array_keys( $settings['post_types' ] ),
			'taxonomy' => '',
			'allow_null' => 1,
			'multiple' => 1,
			'return_format' => 'id',
			'ui' => 1,
		),
	);

	$has_post_types = false;
	$tax_count = 1;
	$post_types = $settings['post_types'];
	if ( count( $post_types ) > 1 ) {
		$has_post_types = true;

		$choices = [];
		foreach( $post_types as $post_type => $tax ) {
			$choices[ $post_type ] = get_post_type_object( $post_type )->labels->singular_name;
		}

		$fields[] = [
			'key' => 'field_5e8a8fa253d2',
			'label' => __( 'Post Type', 'cultivate_textdomain' ),
			'name'  => 'post_type',
			'type' => 'select',
			'choices' => $choices,
			'default_value' => array_key_first( $post_types ),
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5e6a895053d12',
						'operator' => '!=',
						'value' => 'manual',
					)
				),
			),

		];
	}

	foreach( $post_types as $post_type => $taxonomies ) {
		$conditional_logic = $has_post_types ? array(
			array(
				array(
					'field' => 'field_5e8a8fa253d2',
					'operator' => '==',
					'value' => $post_type,
				),
				array(
					'field' => 'field_5e6a895053d12',
					'operator' => '!=',
					'value' => 'manual',
				)
			) ) : false;

		foreach( $taxonomies as $taxonomy ) {
			$fields[] = [
				'key' => 'field_5e6a8aa253d2' . $tax_count,
				'label' => __( 'Limit to', 'cultivate_textdomain' ) . ' ' . $taxonomy['label'],
				'name' => $taxonomy['field'],
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => $conditional_logic,
				'taxonomy' => $taxonomy['tax'],
				'field_type' => 'multi_select',
				'all_null' => 1,
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'multiple' => 1,
			];
			$tax_count++;
		}
	}

	$fields[] = [
		'key' => 'field_8e6a893b53d10',
		'label' => __( 'Read More Text', 'cultivate_textdomain' ),
		'name' => 'read_more_text',
		'type' => 'text',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => 0,
		'wrapper' => array(
			'width' => '',
			'class' => '',
			'id' => '',
		),
		'default_value' => '',
		'placeholder' => '',
		'prepend' => '',
		'append' => '',
		'maxlength' => '',
	];
	$fields[] = [
		'key' => 'field_5f2b0c3d9cbe4',
		'label' => __( 'Read More URL', 'cultivate_textdomain' ),
		'name' => 'read_more_url',
		'type' => 'text',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => 0,
		'wrapper' => array(
			'width' => '',
			'class' => '',
			'id' => '',
		),
		'default_value' => '',
		'placeholder' => '',
		'prepend' => '',
		'append' => '',
		'maxlength' => '',
	];

	$args = [
		'key' => 'group_5e6a89326b7cb',
		'title' => __( 'Post Listing Block', 'cultivate_textdomain' ),
		'fields' => $fields,
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'cwp/post-listing',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	];

	$args = apply_filters( 'cultivate_pro/post_listing/field_group', $args );

	if( ! empty( $args ) ) {
		acf_add_local_field_group( $args );
	}

}
add_action( 'init',  __NAMESPACE__ . '\register_field_group' );

/**
* Parse Args
*
*/
function parse_args( $args = [] ) {
	$settings = settings();
	$args['query_args']['ignore_sticky_posts'] = true;
	if ( class_exists( 'FacetWP' ) ) {
		$args['query_args']['facetwp'] = false;
	}

	if( empty( $args['query_args']['posts_per_page'] ) && !empty( $args['layout'] ) && array_key_exists( $args['layout'], $settings['layouts'] ) ) {
		$args['query_args']['posts_per_page'] = $settings['layouts'][ $args['layout'] ]['posts_per_page'];
	}

	if ( ! empty( $args['post_type'] ) ) {
		$args['query_args']['post_type'] = $args['post_type'];
	}

	$exclude = [];
	global $cp_displayed_posts;
	if ( ! empty( $cp_displayed_posts ) ) {
		$exclude = array_merge( $exclude, $cp_displayed_posts );
	}
	if ( is_singular() ) {
		$exclude[] = get_queried_object_id();
	}
	$args['query_args']['post__not_in'] = $exclude;

	foreach( $settings['post_types'] as $post_type => $taxonomies ) {

		foreach( $taxonomies as $taxonomy ) {
			$tax_terms = !empty( $args[ $taxonomy['field'] ] ) ? $args[ $taxonomy['field'] ] : [];

			if( ! empty( $tax_terms ) ) {
				if( ! is_array( $tax_terms ) ) {
					$tax_terms = array( $tax_terms );
				}

				$tax_args = [
					'taxonomy' => $taxonomy['tax'],
					'field'    => 'term_id',
					'terms'    => $tax_terms,
					'include_children' => true,
				];
				if( 1 < count( $tax_args['terms'] ) ) {
					$tax_args['operator'] = 'AND';
					$tax_args['include_children'] = false;
				}
				$args['query_args']['tax_query'][] = $tax_args;
			}
		}
	}

	if( ! empty( $args['orderby'] ) && 'popular' === $args['orderby'] ) {
		$args['query_args']['orderby'] = 'comment_count';
	}

	if ( ! empty( $args['orderby'] ) && 'modified' === $args['orderby'] ) {
		$args['query_args']['orderby'] = 'modified';
	}

	if( ! empty( $args['orderby'] ) && 'manual' === $args['orderby'] ) {
		if( ! empty( $args['query_args']['tax_query'] ) ) {
			unset( $args['query_args']['tax_query'] );
		}
		$args['query_args']['post_type'] = array_keys( $settings['post_types'] );

		$post_ids = !empty( $args['post__in'] ) ? $args['post__in'] : [ 1 ];
		if( ! is_array( $post_ids ) ) {
			$post_ids = [ intval( $post_ids ) ];
		} else {
			$post_ids = array_map( 'intval', $post_ids );
		}

		$args['query_args']['post__in'] = $post_ids;
		$args['query_args']['orderby'] = 'post__in';
		$args['query_args']['post__not_in'] = array_diff( $args['query_args']['post__not_in'], $args['query_args']['post__in'] );
	}

	if( 'block_area' === get_post_type( get_the_ID() ) ) {
		$block_area = get_post_meta( get_the_ID(), 'cwp_block_area', true );
		if( 'after-post' === $block_area && 'manual' !== $args['orderby'] && empty( $args['query_args']['tax_query']) ) {
			$args['query_args']['cat'] = \cwp_first_term( [ 'field' => 'term_id', 'post_id' => get_queried_object_id() ] );
		}
	}

	if ( 'archive' === $args['layout'] ) {
		$args['layout'] = $settings['archive_layout'];
	}

	$args = apply_filters( 'cultivate_pro/post_listing/args', $args );
	return $args;
}

/**
 * Display
 */
function display( $args, $post_id = 0, $is_preview = false ) {
	$args = parse_args( $args );
	$settings = settings();

	$block = !empty( $args['block'] ) ? $args['block'] : '';
	unset( $args['block'] );

	$classes = ['block-post-listing', 'cwp-large'];
	if( ! empty( $block['className'] ) ) {
		$classes = array_merge( $classes, explode( ' ', $block['className'] ) );
	}

	if( ! empty( $args['layout'] ) ) {
		$classes[] = 'layout-' . $args['layout'];
	}

	if ( function_exists( 'cwp_is_block_area' ) && cwp_is_block_area( 'sidebar', $post_id ) ) {
		$classes[] = 'block-post-listing--sidebar';
	}

	$anchor = '';
	if( ! empty( $block['anchor'] ) ) {
		$anchor = ' id="' . sanitize_title( $block['anchor'] ) . '"';
	}

	global $cp_loop, $cp_displayed_posts;
	$cp_loop = new \WP_Query( $args['query_args'] );
	if( ! $cp_loop->have_posts() && ! $is_preview ) {
		return;
	}

	if ( empty( $cp_displayed_posts ) ) {
		$cp_displayed_posts = [];
	}
	$cp_displayed_posts = array_unique( array_merge( $cp_displayed_posts, wp_list_pluck( $cp_loop->posts, 'ID' ) ) );

	$read_more = '';
	$read_more_text = !empty( $args['read_more_text'] ) ? $args['read_more_text'] : '';
	$read_more_url = !empty( $args['read_more_url'] ) ? $args['read_more_url'] : '';
	$read_more_aria_label = '';
	if ( ! empty( $read_more_text ) && empty( $read_more_url ) && ! empty( $args['query_args']['tax_query'] ) && 1 === count( $args['query_args']['tax_query'] ) && 1 === count( $args['query_args']['tax_query'][0]['terms'] ) ) {
		$read_more_term_id = $args['query_args']['tax_query'][0]['terms'][0];
		$read_more_taxonomy = $args['query_args']['tax_query'][0]['taxonomy'];
		$read_more_url = get_term_link( $read_more_term_id, $read_more_taxonomy );
		$read_more_aria_label = 'Read more posts in ' . get_term_field( 'name', $read_more_term_id, $read_more_taxonomy );
	}
	$read_more_classes = [ 'block-post-listing__more', 'wp-block-button__link', 'wp-element-button' ];
	$container_class = '';;
	if ( ! empty( $settings['read_more']['button_style'] ) ) {
		$container_class = 'wp-block-button is-style-' . $settings['read_more']['button_style'];
	}
	if( $read_more_text && $read_more_url ) {
		$aria = ! empty( $read_more_aria_label ) ? ' aria-label="' . esc_attr( $read_more_aria_label ) . '"' : '';
		$read_more = '<a class="' . esc_attr( join( ' ', $read_more_classes ) ) . '" href="' . esc_url( $read_more_url ) . '"' . $aria . '>' . esc_html( $read_more_text ) . '</a>';
	}

	$classes = apply_filters( 'cultivate_pro/post_listing/classes', $classes, $args );
	echo '<section class="' . join( ' ', $classes ) . '"' . $anchor . '>';

	$header = '';
	$template = [ [ 'core/heading', [ 'level' => 2, 'placeholder' => 'Title Goes Here' ] ] ];
	$header .= '<InnerBlocks class="block-post-listing__title cwp-inner" template="' . esc_attr( wp_json_encode( $template ) ) . '" allowedBlocks="' . esc_attr( wp_json_encode( [ 'core/heading', 'core/paragraph' ] ) ) . '" />';

	$more = apply_filters( 'cultivate_pro/post_listing/more', $read_more, $read_more_text, $read_more_url, 'header' );
	if( !empty( $more ) && in_array( 'header', $settings['read_more']['locations'] ) ) {

		$header_more = $more;
		if ( ! empty( $container_class ) ) {
			$header_more = '<div class="' . esc_attr( $container_class ) . '">' . $header_more . '</div>';
		}
		$header .= $header_more;
	}
	$header = apply_filters( 'cultivate_pro/post_listing/header', $header, $args );
	if( !empty( $header ) )
		echo '<header>' . $header . '</header>';

	$inner_classes = [ 'block-post-listing__inner' ];
	if ( $is_preview ) {
		$inner_classes[] = 'wp-block--disable-click';
	}

	echo '<div class="' . esc_attr( join( ' ', $inner_classes ) ) . '">';
	if( $cp_loop->have_posts() ) {
		while( $cp_loop->have_posts() ): $cp_loop->the_post();
			post_summary( $args['layout'], $cp_loop->current_post );
		endwhile;
	} elseif( $is_preview ) {
		for( $i = 0; $i < $args['query_args']['posts_per_page']; $i ++ ) {
			echo '<div class="post-summmary post-summary--preview" style="height: 300px; background: #ccc;">';
			echo '</div>';
		}
	}
	wp_reset_postdata();
	echo '</div>';

	$footer = '';
	$more = apply_filters( 'cultivate_pro/post_listing/more', $read_more, $read_more_text, $read_more_url, 'footer' );
	if( !empty( $more ) && in_array( 'footer', $settings['read_more']['locations'] ) )
		$footer .= $more;
	$footer = apply_filters( 'cultivate_pro/post_listing/footer', $footer );
	if( !empty( $footer ) ) {
		printf(
			'<footer%s>',
			! empty( $container_class ) ? ' class="' . esc_attr( $container_class ) . '"' : '',
		);
		echo $footer;
		echo '</footer>';
	}
	echo '</section>';

	$cp_loop = false;

}

/**
 * Post Summary
 */
function post_summary( $layout = '', $current_post = 0 ) {
	$settings = settings();
	if ( empty( $layout ) ) {
		$layout = $settings['archive_layout'];
	}

	$classes = [ 'post-summary' ];

	if ( function_exists( 'Cultivate\Affiliate_Links\post_type_slug' ) && get_post_type() === Affiliate_Links\post_type_slug() ) {
		$classes[] = 'post-summary--shop';
	}

	if ( in_array( $layout, [ '3up-list', '4up-list', '4up-2x2-list', '5up-list', '6up-list', 'sidebar-4up-list', 'sidebar-6up-list' ] ) ) {
		$classes[] = 'm-list';
	}

	if ( in_array( $layout, [ '3up-featured', '4up-featured', '5up-featured', '6up-featured' ] ) && 0 !== $current_post ) {
		$classes[] = 'm-list';
	}

	if ( in_array( $layout, [ 'sidebar-4up-list', 'sidebar-6up-list', '4up-2x2-list' ] ) ) {
		$classes[] = 'd-list';
	}

	echo '<article class="' . esc_attr( join( ' ', $classes ) ) . '">';
	post_summary_image( $layout, $current_post );

	echo '<div class="post-summary__content">';
	do_action( 'cwp_post_summary_title_before', $layout, $current_post );
	post_summary_title();
	do_action( 'cwp_post_summary_title_after', $layout, $current_post );
	echo '</div>';

	echo '</article>';
}

/**
 * Post Summary Image
 */
function post_summary_image( $layout = '', $current_post = 0 ) {
	$settings = settings();

	if ( in_array( $layout, [ '4up-text', '6up-text' ] ) ) {
		return;
	}

	$image_size = 'cwp_archive_lg';
	$image_id   = has_post_thumbnail() ? get_post_thumbnail_id() : get_option( 'options_cwp_default_image' );
	$image_attr = [ 'class' => 'nopin', 'data-pin-nopin' => true ];

	if ( 'video' === get_post_type() && ! has_post_thumbnail() ) {
		$connected_post = get_post_meta( get_the_ID(), 'cwp_recipe_url', true );
		if ( ! empty( $connected_post ) ) {
			$connected_post_id = url_to_postid( $connected_post );
			if ( ! empty( $connected_post_id ) ) {
				$image_id = get_post_thumbnail_id( $connected_post_id );
			}
		}
	}

	if ( '1up-featured' === $layout ) {
		$image_size = 'large';
	}

	if ( function_exists( 'Cultivate\Affiliate_Links\post_type_slug' ) && get_post_type() === Affiliate_Links\post_type_slug() ) {
		$image_size = 'cwp_square';
	}

	if ( ! empty( $settings['layouts'][ $layout ]['sizes'] ) ) {
		$image_attr['sizes'] = $settings['layouts'][ $layout ]['sizes'];
	}

	if ( isset( $settings['layouts'][ $layout ]['size_override'] ) && ! empty( $settings['layouts'][ $layout ]['size_override'][ $current_post ] ) ) {
		$image_attr['sizes'] = $settings['layouts'][ $layout ]['size_override'][ $current_post ];
	}

	echo '<div class="post-summary__image"><a href="' . esc_url( get_permalink() ) . '" tabindex="-1" aria-hidden="true">';
	echo wp_get_attachment_image( $image_id, $image_size, null, $image_attr );
	echo '</a></div>';
}

/**
 * Post Summary Title
 */
function post_summary_title() {
	global $wp_query, $cp_loop;
	$tag = ( is_singular() || -1 === $wp_query->current_post ) ? 'h3' : 'h2';
	if ( isset( $cp_loop->cp_no_title ) ) {
		$tag = 'h2';
	}
	echo '<' . esc_attr( $tag ) . ' class="post-summary__title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></' . esc_attr( $tag ) . '>';
}

/**
 * Post Summary Recipe Key
 */
function post_summary_recipe_key() {
	if ( function_exists( 'Cultivate\Recipe_Key\display' ) ) {
		$link = get_option( 'options_cwp_recipe_key_link' );
		\Cultivate\Recipe_Key\display( [ 'link' => $link ] );
	}
}

/**
 * Post Summary Excerpt
 */
function post_summary_excerpt( $layout = '', $current_post = 0 ) {

	$supported_layouts = [ '4up-text', '6up-text', '1up-featured' ];
	if ( ! in_array( $layout, $supported_layouts ) ) {
		return;
	}

	$excerpt = get_the_excerpt();
	if ( empty( $excerpt ) ) {
		$excerpt = get_post_meta( get_the_ID(), '_yoast_wpseo_metadesc', true );
	}

	echo '<p class="post-summary__excerpt">' . wp_kses_post( $excerpt ) . '</p>';
}

/**
 * Post Summary Rating
 */
function post_summary_rating( $layout = '', $current_post = 0 ) {

	$recipe_id = cwp_get_recipe_id();
	if ( empty( $recipe_id ) ) {
		return;
	}

	$recipe = \WPRM_Template_Shortcodes::get_recipe( $recipe_id );
	$rating = $recipe->rating();
	if ( empty( $rating ) ) {
		return;
	}

	$rating = round( $rating['average'] );
	echo '<div class="post-summary__rating">';
		for( $i = 0; $i < $rating; $i++ ) {
			echo cwp_icon( [ 'icon' => 'star-full', 'size' => 16 ] );
		}
	echo '</div>';

}

/**
 * Buy Now
 */
function post_summary_buy_now( $layout = '', $current_post = 0 ) {
	if ( ! ( function_exists( 'Cultivate\Affiliate_Links\post_type_slug' ) && get_post_type() === Affiliate_Links\post_type_slug() ) ) {
		return;
	}

	$meta_key = Affiliate_Links\meta_key();

	$url = get_post_meta( get_the_ID(), $meta_key, true );
	if ( ! empty( $url ) ) {
		echo cwp_button( [ 'url' => $url, 'title' => 'Buy Now'], [ 'style' => 'arrow', 'container' => true, 'align' => 'center' ] );
	}

}
