<?php
/**
 * WordPress SEO
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Breadcrumbs
 */
function cwp_breadcrumbs() {

	if ( is_front_page() ) {
		return;
	}

	$cwp_hide_breadcrumbs = get_post_meta( get_the_ID(), 'cwp_hide_breadcrumbs', true );

	if ( !$cwp_hide_breadcrumbs ) {
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<p id="breadcrumbs" class="breadcrumb">', '</p>' );
		} elseif( function_exists( 'aioseo_breadcrumbs' ) ) {
			aioseo_breadcrumbs();
		} elseif( function_exists( 'rank_math_the_breadcrumbs' ) ) {
			rank_math_the_breadcrumbs();
		}
	}
}
add_action( 'tha_content_top', 'cwp_breadcrumbs' );

/**
 * Breadcrumb Separator
 */
function cwp_breadcrumb_separator( $output ) {
	//$output = cwp_icon( [ 'icon' => 'chevron-right', 'size' => 12 ] );
	$output = '›';
	return '<span class="sep">' . $output . '</span>';
};
add_filter( 'wpseo_breadcrumb_separator', 'cwp_breadcrumb_separator' );

/**
 * Remove last item from breadcrumbs
 *
 * @param string $link_output Link Output.
 */
function cwp_breadcrumb_remove_last_item( $link_output ) {
	if ( is_singular() && strpos( $link_output, 'breadcrumb_last' ) !== false ) {
		$link_output = '';
	}
	return $link_output;
}
add_filter( 'wpseo_breadcrumb_single_link', 'cwp_breadcrumb_remove_last_item' );

/**
 * Home Breadcrumb
 *
 * @param array $crumbs Crumbs.
 */
function cwp_home_breadcrumb( $crumbs ) {

	foreach ( $crumbs as $i => $crumb ) {
		if ( ! empty( $crumb['text'] ) && 'Home' === $crumb['text'] ) {
			$crumbs[ $i ]['text'] = '<span class="home">' . cwp_icon( [ 'icon' => 'home', 'size' => 16 ] ) . '<span class="screen-reader-text">Home</span></span>';
		}
	}
	return $crumbs;
}
//add_filter( 'wpseo_breadcrumb_links', 'cwp_home_breadcrumb' );

/**
 * Recipe Index Crumb
 */
function cwp_get_recipe_index_crumb() {
	$recipe_index_id = get_option( 'options_cwp_recipe_index_page' );
	$recipe_category_id = get_option( 'options_cwp_recipes_category' );

	if ( empty( $recipe_index_id ) && empty( $recipe_category_id ) ) {
		return false;
	} elseif ( ! empty( $recipe_index_id ) ) {
		$title = get_post_meta( $recipe_index_id, '_yoast_wpseo_bctitle', true );
		if ( empty( $title ) ) {
			$title = get_the_title( $recipe_index_id );
		}
		return [
			'url' => get_permalink( $recipe_index_id ),
			'text' => $title,
			'id' => $recipe_index_id
		];
	} else {
		$term = get_term_by( 'term_id', $recipe_category_id, 'category' );
		$seo_termmeta = maybe_unserialize( get_option( 'wpseo_taxonomy_meta' ) );
		if ( isset( $seo_termmeta['category'][ $recipe_category_id ] ) && isset( $seo_termmeta['category'][ $recipe_category_id ]['wpseo_bctitle'] ) && ! empty( $seo_termmeta['category'][ $recipe_category_id ]['wpseo_bctitle'] ) ) {
			$title = $seo_termmeta['category'][ $recipe_category_id ]['wpseo_bctitle'];
		} else {
			$title = $term->name;
		}

		return [
			'taxonomy' => 'category',
			'term_id' => $recipe_category_id,
			'url' => get_term_link( $term, 'category' ),
			'text' => $title
		];

	}

}

/**
 * Replace recipe category with recipe index
 */
function cwp_replace_recipe_category_with_recipe_index( $crumbs ) {
	$recipe_index_crumb = cwp_get_recipe_index_crumb();
	if ( empty( $recipe_index_crumb ) ) {
		return $crumbs;
	}

	$recipe_category_id = get_option( 'options_cwp_recipes_category' );
	if ( empty( $recipe_category_id ) ) {
		return $crumbs;
	}

	foreach( $crumbs as $i => $crumb ) {
		if ( isset( $crumb['term_id'] ) && $recipe_category_id == $crumb['term_id'] ) {
			$crumbs[ $i ] = $recipe_index_crumb;
		}
	}
	return $crumbs;
}
add_filter( 'wpseo_breadcrumb_links', 'cwp_replace_recipe_category_with_recipe_index' );

/**
 *  Add Recipe Index crumb
 */
function cwp_add_recipe_index_crumb( $crumbs ) {
	$recipe_index_crumb = cwp_get_recipe_index_crumb();
	if ( empty( $recipe_index_crumb ) ) {
		return $crumbs;
	}

	$add_to_posts = get_option( 'options_cwp_add_recipe_index_post' );
	$add_to_category = get_option( 'options_cwp_add_recipe_index_category' );

	$exclude_categories = get_option( 'options_cwp_breadcrumb_category_exclude' );
	if ( ! empty( $exclude_categories ) ) {
		$terms = [];
		if( is_single() ) {
			$terms = wp_list_pluck( get_the_terms( get_the_ID(), 'category' ), 'term_id' );
		} elseif( is_category() ) {
			$terms = [ get_queried_object_id() ];
		}
		if ( ! empty( array_intersect( $terms, $exclude_categories ) ) ) {
			return $crumbs;
		}
	}

	if ( isset( $recipe_index_crumb['id'] ) && ! in_array( $recipe_index_crumb['id'], array_column( $crumbs, 'id' ) ) && ( ( is_single() && $add_to_posts ) || ( is_category() && $add_to_category ) ) ) {
		array_splice( $crumbs, 1, 0, [ $recipe_index_crumb ] );
	} elseif ( isset( $recipe_index_crumb['term_id'] ) && ! in_array( $recipe_index_crumb['term_id'], array_column( $crumbs, 'term_id' ) ) && ( ( is_single() && $add_to_posts ) || ( is_category() && $add_to_category ) ) ) {
		array_splice( $crumbs, 1, 0, [ $recipe_index_crumb ] );
	}

	return $crumbs;
}
add_filter( 'wpseo_breadcrumb_links', 'cwp_add_recipe_index_crumb' );

/**
 * Category image in schema
 */
function cwp_category_image_schema( $graph ) {
	if ( ! is_category() ) {
		return $graph;
	}

	$image_id = get_term_meta( get_queried_object_id(), 'image', true );
	if ( empty( $image_id ) ) {
		return $graph;
	}

	$image = wp_get_attachment_image_src( $image_id, 'full' );
	if ( empty( $image ) ) {
		return $graph;
	}

	foreach( $graph as $i => $piece ) {
		if ( isset( $piece['@type'] ) && 'CollectionPage' === $piece['@type'] ) {
			$graph[$i]['thumbnailUrl'] = $image[0];
		}
		if ( isset( $piece['@id'] ) && false !== strpos( $piece['@id'], '#primaryimage' ) )  {
			$graph[$i]['contentUrl'] = $graph[$i]['url'] = $image[0];
			$graph[$i]['width'] = $image[1];
			$graph[$i]['height'] = $image[2];
		}
	}

	return $graph;

}
add_filter( 'wpseo_schema_graph', 'cwp_category_image_schema' );
