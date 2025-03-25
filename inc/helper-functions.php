<?php
/**
 * Helper Functions
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Duplicate 'the_content' filters.
global $wp_embed;
add_filter( 'cwp_the_content', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'cwp_the_content', array( $wp_embed, 'autoembed'     ), 8 );
add_filter( 'cwp_the_content', 'do_blocks', 9 );
add_filter( 'cwp_the_content', 'wptexturize' );
add_filter( 'cwp_the_content', 'convert_chars' );
add_filter( 'cwp_the_content', 'wpautop' );
add_filter( 'cwp_the_content', 'shortcode_unautop' );
add_filter( 'cwp_the_content', 'do_shortcode' );

/**
 * Get the first term attached to post
 *
 * @param array $args Args.
 */
function cwp_first_term( $args = [] ) {

	$defaults = [
		'taxonomy' => 'category',
		'field'    => null,
		'post_id'  => null,
	];

	$args = wp_parse_args( $args, $defaults );

	$post_id = ! empty( $args['post_id'] ) ? intval( $args['post_id'] ) : get_the_ID();
	$field   = ! empty( $args['field'] ) ? esc_attr( $args['field'] ) : false;
	$term    = false;

	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		$term = get_term( ( new WPSEO_Primary_Term( $args['taxonomy'], $post_id ) )->get_primary_term(), $args['taxonomy'] );
	} elseif( class_exists( 'RankMath' ) ) {
        $term_id = get_post_meta( $post_id, 'rank_math_primary_' . $args['taxonomy'], true );
		if ( ! empty( $term_id ) ) {
			$term = get_term_by( 'term_id', $term_id, $args['taxonomy'] );
		}
	}

	if ( ! $term || is_wp_error( $term ) ) {

		$terms = get_the_terms( $post_id, $args['taxonomy'] );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return false;
		}

		if ( 1 === count( $terms ) ) {
			$term = array_shift( $terms );

		} else {

			if ( isset( $terms[0]->order ) ) {
				$list = array();
				foreach ( $terms as $term ) {
					$list[ $term->order ] = $term;
				}
				ksort( $list, SORT_NUMERIC );

			} else {
				$list = array();
				foreach ( $terms as $term ) {
					$list[ $term->count ] = $term;
				}
				ksort( $list, SORT_NUMERIC );
				$list = array_reverse( $list );
			}

			$term = array_shift( $list );
		}
	}

	if ( ! empty( $field ) && isset( $term->$field ) ) {
		return $term->$field;
	} else {
		return $term;
	}
}

/**
 * Conditional CSS Classes
 *
 * @param string $base_classes classes always applied.
 * @param string $optional_class additional class applied if $conditional is true.
 * @param bool   $conditional whether to add $optional_class or not.
 * @return string $classes
 */
function cwp_class( $base_classes, $optional_class, $conditional ) {
	return $conditional ? $base_classes . ' ' . $optional_class : $base_classes;
}

/**
 *  Background Image Style
 *
 * @param int    $image_id Image ID.
 * @param string $image_size Image Size.
 */
function cwp_bg_image_style( $image_id = false, $image_size = 'full' ) {
	if ( ! empty( $image_id ) ) {
		return ' style="background-image: url(' . wp_get_attachment_image_url( $image_id, $image_size ) . ');"';
	}
}

/**
 * Get Icon
 * This function is in charge of displaying SVG icons across the site.
 *
 * Place each <svg> source in the /assets/icons/{group}/ directory.
 *
 * All icons are assumed to have equal width and height, hence the option
 * to only specify a `$size` parameter in the svg methods. For icons with
 * custom (non-square) sizes, set 'size' => false.
 *
 * Icons will be loaded once in the footer and referenced throughout document.
 *
 * @param array $atts Shortcode Attributes.
 */
function cwp_icon( $atts = array() ) {

	$atts = shortcode_atts(
		[
			'icon'  => false,
			'group' => 'utility',
			'size'  => 24,
			'width' => false,
			'height' => false,
			'class' => false,
			'label' => false,
			'defs'  => false,
			'force' => false,
		],
		$atts
	);

	if ( empty( $atts['icon'] ) ) {
		return;
	}

	if ( is_admin() ) {
		$atts['force'] = true;
	}

	$icon_path = get_theme_file_path( '/assets/icons/' . $atts['group'] . '/' . $atts['icon'] . '.svg' );
	if ( 'images' === $atts['group'] ) {
		$icon_path    = get_theme_file_path( '/assets/images/' . $atts['icon'] . '.svg' );
		$atts['size'] = false;
	}
	if ( ! file_exists( $icon_path ) ) {
		return;
	}

	// Display the icon directly.
	if ( true === $atts['force'] ) {
		ob_start();
		readfile( $icon_path );
		$icon = ob_get_clean();
		if ( false !== $atts['size'] ) {
			$repl = sprintf( '<svg width="%d" height="%d" aria-hidden="true" role="img" focusable="false" ', $atts['size'], $atts['size'] );
			$svg  = preg_replace( '/^<svg /', $repl, trim( $icon ) ); // Add extra attributes to SVG code.
		} elseif( false === $atts['size'] && ! empty( $atts['width'] ) && ! empty( $atts['height'] ) ) {
			$repl = sprintf( '<svg width="%d" height="%d" aria-hidden="true" role="img" focusable="false" ', $atts['width'], $atts['height'] );
			$svg  = preg_replace( '/^<svg /', $repl, trim( $icon ) ); // Add extra attributes to SVG code.
		} else {
			$svg = preg_replace( '/^<svg /', '<svg ', trim( $icon ) );
		}
		$svg  = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
		$svg  = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.
		if ( ! empty( $atts['class'] ) ) {
			$svg = preg_replace( '/^<svg /', '<svg class="' . $atts['class'] . '"', $svg );
		}

		// Display the icon as symbol in defs.
	} elseif ( true === $atts['defs'] ) {
		ob_start();
		readfile( $icon_path );
		$icon = ob_get_clean();
		$svg  = preg_replace( '/^<svg /', '<svg id="' . $atts['group'] . '-' . $atts['icon'] . '"', trim( $icon ) );
		$svg  = str_replace( '<svg', '<symbol', $svg );
		$svg  = str_replace( '</svg>', '</symbol>', $svg );
		$svg  = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
		$svg  = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.

		// Display reference to icon.
	} else {

		global $cwp_icons;
		if ( empty( $cwp_icons[ $atts['group'] ] ) ) {
			$cwp_icons[ $atts['group'] ] = [];
		}
		if ( empty( $cwp_icons[ $atts['group'] ][ $atts['icon'] ] ) ) {
			$cwp_icons[ $atts['group'] ][ $atts['icon'] ] = 1;
		} else {
			$cwp_icons[ $atts['group'] ][ $atts['icon'] ]++;
		}

		$attr = '';
		if ( ! empty( $atts['class'] ) ) {
			$attr .= ' class="' . esc_attr( $atts['class'] ) . '"';
		}
		if ( false !== $atts['size'] ) {
			$attr .= sprintf( ' width="%d" height="%d"', $atts['size'], $atts['size'] );
		} elseif( false === $atts['size'] && ! empty( $atts['width'] ) && ! empty( $atts['height'] ) ) {
			$attr .= sprintf( ' width="%d" height="%d"', $atts['width'], $atts['height'] );
		}
		if ( ! empty( $atts['label'] ) ) {
			$attr .= ' aria-label="' . esc_attr( $atts['label'] ) . '"';
		} else {
			$attr .= ' aria-hidden="true" role="img" focusable="false"';
		}
		$svg = '<svg' . $attr . '><use href="#' . $atts['group'] . '-' . $atts['icon'] . '"></use></svg>';
	}

	return $svg;
}

/**
 * Icon as image
 *
 * @param array $atts
 */
function cwp_icon_as_img( $atts = [] ) {

	$atts['size'] = false;
	$atts['force'] = true;

	$icon       = cwp_icon( $atts );
	$dimensions = str_replace( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ', '', $icon );
	$dimensions = explode( ' ', substr( $dimensions, 0, strpos( $dimensions, '">' ) ) );
	if ( 2 !== count( $dimensions ) ) {
		return;
	}

	return '<img src="' . get_stylesheet_directory_uri() . '/assets/icons/' . $atts['group'] . '/' . $atts['icon'] . '.svg" alt="' . $atts['icon'] . '" width="' . $dimensions[0] . '" height="' . $dimensions[1] . '" />';
}

/**
 * Icon Definitions
 */
function cwp_icon_definitions() {
	global $cwp_icons;

	if ( empty( $cwp_icons ) ) {
		return;
	}

	echo '<svg style="display:none;"><defs>';
	foreach ( $cwp_icons as $group => $icons ) {
		foreach ( $icons as $icon => $count ) {
			echo cwp_icon( [ 'icon' => $icon, 'group' => $group, 'defs' => true ] );
		}
	}
	echo '</defs></svg>';
}
add_action( 'wp_footer', 'cwp_icon_definitions', 20 );

/**
 * Has Action
 *
 * @param string $hook Hook.
 */
function cwp_has_action( $hook ) {
	ob_start();
	do_action( $hook );
	$output = ob_get_clean();
	return ! empty( $output );
}

/**
 * Button
 *
 * @param array $field ACF Field data.
 * @param array $atts Button attributes.
 */
function cwp_button( $field = [], $atts = [] ) {

	if ( empty( $field ) ) {
		return;
	}

	$classes = [ 'wp-element-button' ];
	$target  = ! empty( $field['target'] ) ? ' target="' . $field['target'] . '"' : '';
	$rel     = ! empty( $atts['rel'] ) ? explode( ' ', $atts['rel'] ) : [];

	if ( false !== strpos( $target, '_blank' ) && empty( $atts['rel'] ) ) {
		$rel[] = 'noreferrer';
	}

	if ( false !== strpos( $target, '_blank' ) ) {
		$rel[] = 'noopener';
	}

	if ( ! empty( $rel ) ) {
		$target .=' rel="' . esc_attr( join( ' ', $rel ) ) . '"';
	}

	if ( ! empty( $atts['style'] ) ) {
		$classes[] = 'is-style-' . $atts['style'];
	}

	if ( ! empty( $atts['bg'] ) ) {
		$classes[] = 'has-background';
		$classes[] = 'has-' . $atts['bg'] . '-background-color';
	}

	$output = '<a class="' . join( ' ', $classes ) . '" href="' . esc_html( $field['url'] ) . '"' . $target . '>' . esc_html( $field['title'] ) . '</a>';

	if ( ! empty( $atts['container'] ) && true === filter_var( $atts['container'], FILTER_VALIDATE_BOOLEAN ) ) {
		$container_class = [ 'wp-block-buttons' ];
		if ( ! empty( $atts['align' ] ) ) {
			$container_class[] = 'is-layout-flex';
			$container_class[] = 'is-content-justification-' . $atts['align'];
		}
		if ( ! empty( $atts['container_class'] ) ) {
			$container_class[] = $atts['container_class' ];
		}
		$output = '<div class="' . esc_attr( join( ' ', $container_class ) ) . '">' . $output . '</div>';
	}

	return $output;
}

/**
 * Is Block Area
 */
function cwp_is_block_area( $block_area = '', $post_id = '' ) {
	return 'block_area' === get_post_type( $post_id ) && $block_area === get_post_meta( $post_id, 'cwp_block_area', true );
}


/**
 * Strip Tags By Classname
 * Reference: https://stackoverflow.com/a/6366390/4665413
 */
function cwp_strip_tags_by_classname( $html_to_parse, $classname ) {

	if ( empty( $html_to_parse ) || ! class_exists( 'domDocument' ) ) {
		return;
	}

	$dom = new domDocument;
	// suppress the warnings
	libxml_use_internal_errors(true);
	// load the html into the object
	$dom->loadHTML( $html_to_parse );
	// discard white space
	$dom->preserveWhiteSpace = false;

	$finder = new \DOMXPath($dom);
	$nodes = $finder->query( "//*[contains(concat(' ', normalize-space(@class), ' '), ' {$classname} ')]" );

	foreach ($nodes as $node) {
		$node->parentNode->removeChild($node);
	}

	return $dom->saveHTML();

}


/**
 * Get Recipe ID
 */
function cwp_get_recipe_id( $id = false ) {
	$content = ! empty( $id ) ? get_post_field( 'post_content', $id ) : get_queried_object()->post_content;

	if ( ! class_exists( 'WPRM_Recipe_Manager' ) ) {
		return false;
	}

	$recipes = \WPRM_Recipe_Manager::get_recipe_ids_from_content( $content );
	if( empty( $recipes ) )
		return false;

	$recipe_id = $recipes[0];
	return $recipe_id;
}

/**
 * Recursively searches content for blocks.
 *
 */
function cwp_has_block( $block_type = '', $blocks = array(), $current = false ) {

	if ( ! isset( $block_type ) ) {
		return false;
	}

	if ( is_singular() && empty( $blocks ) && false === $current ) {
		global $post;
		$blocks = parse_blocks( $post->post_content );
	}
	$current = false === $current ? 0 : $current;

	foreach ( $blocks as $block ) {

		if ( ! isset( $block['blockName'] ) ) {
			continue;
		}

		if( $block['blockName'] === $block_type ) {
			$current++;

		// Scan inner blocks
		} elseif ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) ) {
			$current = cwp_has_block( $block_type, $block['innerBlocks'], $current );
		}
	}

	return $current;
}


/**
 * Render Search
 */
function cwp_render_search() {
	return render_block( [ 'blockName' => 'core/search', 'attrs' => [ 'label' => 'Search', 'showLabel' => false, 'placeholder' => 'Search the site', 'buttonText' => 'Search', 'buttonPosition' => 'button-inside', 'buttonUseIcon' => true ] ] );
}
