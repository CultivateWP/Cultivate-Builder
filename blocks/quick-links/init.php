<?php
/**
 * Quick Links block
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace Cultivate\Blocks\Quick_Links;
use Cultivate\Blocks\Post_Listing;

/**
 * Quick Links Settings
 */
function settings() {
	$settings = [
		'style' => [
			'square' => 'Square',
			'circle' => 'Circle'
		],
		'layout' => [
			'grid' => 'Grid',
			'flex' => 'Flex'
		]
	];

	// Use the post types & taxonomies from Post Listing
	// You can remove this and manually specify them in array if needed.
	$post_listing_settings = Post_Listing\settings();
	$settings['post_types'] = $post_listing_settings['post_types'];

	return $settings;
}

/**
 * Quick Links Types
 */
function types() {

	$settings = settings();
	$types = [];

	foreach( $settings['post_types'] as $post_type => $taxonomies ) {

		if ( ! empty( $taxonomies ) ) {
			foreach( $taxonomies as $taxonomy ) {
				$types['choices'][ $taxonomy['field'] ] = $taxonomy['label'];
				$types['taxonomy'][ $taxonomy['field'] ] = $taxonomy['tax'];
			}
		}

		$post_type_object = get_post_type_object( $post_type );
		if ( ! is_wp_error( $post_type_object ) ) {
			$types['choices'][ $post_type_object->name ] = $post_type_object->labels->singular_name;
			$types['post_object'][ $post_type ] = $post_type;
		}
	}

	$types['choices']['manual'] = 'Manual';
	return $types;
}

/**
 * Register field group
 */
function register_field_group() {

	if( ! function_exists('acf_add_local_field_group' ) ) {
		return;
	}

	$settings = settings();

	$fields = array(
		array(
			'key' => 'field_633de11c65e37',
			'label' => __( 'Style', 'cultivate_textdomain' ),
			'name' => 'style',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => $settings['style'],
			'default_value' => array_key_first( $settings['style'] ),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_633de1a465e38',
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
			'choices' => $settings['layout'],
			'default_value' => array_key_first( $settings['layout'] ),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
	);

	$sub_fields = [];
	$types = types();

	$sub_fields[] = [
		'key' => 'field_633de1c965e3a',
		'label' => 'Type',
		'name' => 'type',
		'type' => 'select',
		'choices' => $types['choices'],
		'parent_repeater' => 'field_633de1b865e39',
	];

	$counter = 0;
	foreach( $types['choices'] as $type => $label ) {
		$counter++;
		if ( array_key_exists( $type, $types['taxonomy'] ) ) {
			$sub_fields[] = [
				'key' => 'field_633de1c966e3' . $counter,
				'label' => $label,
				'name' => $type,
				'type' => 'taxonomy',
				'taxonomy' => $types['taxonomy'][ $type ],
				'conditional_logic' => [
					[
						[
							'field' => 'field_633de1c965e3a',
							'operator' => '==',
							'value' => $type
						]
					]
				],
				'return_format' => 'id',
				'field_type' => 'select',
				'allow_null' => 0,
				'multiple' => 0,
				'parent_repeater' => 'field_633de1b865e39'
			];
		} elseif( array_key_exists( $type, $types['post_object'] ) ) {
			$sub_fields[] = [
				'key' => 'field_633de1c966e3' . $counter,
				'label' => $label,
				'name' => $type,
				'type' => 'post_object',
				'post_type' => $types['post_object'][ $type ],
				'conditional_logic' => [
					[
						[
							'field' => 'field_633de1c965e3a',
							'operator' => '==',
							'value' => $type
						]
					]
				],
				'return_format' => 'id',
				'allow_null' => 0,
				'multiple' => 0,
				'parent_repeater' => 'field_633de1b865e39'
			];
		} elseif( 'manual' == $type ) {
			$sub_fields[] = [
				'key' => 'field_633de21265e3c',
				'label' => 'Title',
				'name' => 'title',
				'type' => 'text',
				'conditional_logic' => [
					[
						[
							'field' => 'field_633de1c965e3a',
							'operator' => '==',
							'value' => 'manual'
						]
					]
				],
				'parent_repeater' => 'field_633de1b865e39'
			];

			$sub_fields[] = [
				'key' => 'field_633de22265e3d',
				'label' => 'URL',
				'name' => 'url',
				'type' => 'text',
				'conditional_logic' => [
					[
						[
							'field' => 'field_633de1c965e3a',
							'operator' => '==',
							'value' => 'manual'
						]
					]
				],
				'parent_repeater' => 'field_633de1b865e39'
			];

			$sub_fields[] = [
				'key' => 'field_633de23765e3e',
				'label' => 'Image',
				'name' => 'image',
				'type' => 'image',
				'conditional_logic' => [
					[
						[
							'field' => 'field_633de1c965e3a',
							'operator' => '==',
							'value' => 'manual'
						]
					]
				],
				'parent_repeater' => 'field_633de1b865e39',
				'return_format' => 'id',
				'preview_size' => 'cwp_square'
			];

		}
	}


	$fields[] = [
		'key' => 'field_633de1b865e39',
		'label' => 'Items',
		'name' => 'items',
		'type' => 'repeater',
		'layout' => 'block',
		'button_label' => 'Add Item',
		'sub_fields' => $sub_fields
	];


	$args = [
		'key' => 'group_633de11c68e6b',
		'title' => __( 'Quick Links', 'cultivate_textdomain' ),
		'fields' => $fields,
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'cwp/quick-links',
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
 * Label Position
 */
function label_position() {
	$position = 'under';
	if ( class_exists( '\WP_Theme_JSON_Resolver' ) ) {
		$settings = \WP_Theme_JSON_Resolver::get_theme_data()->get_settings();
		if ( isset( $settings['custom']['quick-links']['label'] ) ) {
			$position = $settings['custom']['quick-links']['label'];
		}
	}

	return apply_filters( 'cultivate_pro/quick_links/label_position', $position );
}
