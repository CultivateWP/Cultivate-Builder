<?php
/**
 * Template Tags
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use Cultivate\Blocks\Social_Share;

/**
 * Post Header Author
 */
function cwp_post_header_author( $atts = [] ) {

	$atts = shortcode_atts(
		[
			'prefix' => 'By ',
			'class'  => 'entry-author',
		],
		$atts,
	);
	global $post;
	$id = $post->post_author;

	echo '<p class="' . esc_attr( $atts['class'] ) . '">' . wp_kses_post( $atts['prefix'] ) . '<a href="' . esc_url( get_author_posts_url( $id ) ) . '">' . get_the_author_meta( 'display_name', $id ) . '</a></p>';
}

/**
 * Post Header Avatar
 */
function cwp_post_header_avatar( $size = 48 ) {
	global $post;
	$id = $post->post_author;
	echo '<a href="' . esc_url( get_author_posts_url( $id ) ) . '" aria-hidden="true" tabindex="-1" class="entry-avatar">' . get_avatar( $id, $size ) . '</a>';
}

/**
 * Post Header Date
 */
function cwp_post_header_date( $atts = [] ) {
	cwp_post_date( [ 'class' => 'post-header__date', 'format' => 'M d, Y' ] );
}

/**
 * Post Date
 */
function cwp_post_date( $atts = [] ) {
	$post_id         = isset( $atts['id'] ) ? intval( $atts['id'] ) : get_queried_object_id();
	$include_publish = isset( $atts['include_publish' ] ) ? filter_var( $atts['include_publish' ], FILTER_VALIDATE_BOOLEAN ) : true;
	$include_updated = isset( $atts['include_updated' ] ) ? filter_var( $atts['include_updated'], FILTER_VALIDATE_BOOLEAN ) : true;
	$sep             = ! empty( $atts['sep'] ) ? $atts['sep'] : ', ';
	$publish_label   = ! empty( $atts['publish_label'] ) ? $atts['publish_label'] : '';
	$updated_label   = ! empty( $atts['updated_label'] ) ? $atts['updated_label'] : 'Updated';
	$class           = ! empty( $atts['class'] ) ? $atts['class'] : 'post-date';
	$format          = ! empty( $atts['format'] ) ? $atts['format'] : 'F j, Y';
	$post_date       = $include_publish ? $publish_label . ' ' . get_the_date( $format, $post_id ) : '';
	$single_date     = ! empty( $atts['single_date'] ) ? $atts['single_date'] : false;

	if ( empty( $post_date ) || ! empty( $single_date ) ) {
		$sep = '';
	}

	$updated_date    = $include_updated ? $sep . $updated_label . ' ' . get_the_modified_date( $format, $post_id ) : '';
	if ( ! ( get_the_date( 'U', $post_id ) < ( get_the_modified_date( 'U', $post_id ) - WEEK_IN_SECONDS ) ) ) {
		$updated_date = '';
	}

	if ( ! empty( $single_date ) && ! empty( $post_date ) && ! empty( $updated_date ) ) {
		$post_date = '';
	}

	$author = '';
	if ( ! empty( $atts['include_author'] ) && true === filter_var( $atts['include_author'], FILTER_VALIDATE_BOOLEAN ) ) {
		$author_id = ! is_single() ? 1 : get_queried_object()->post_author;
		$author = ' by <a href="' . esc_url( get_author_posts_url( $author_id ) ) . '">' . get_the_author_meta( 'display_name', $author_id ) . '</a>';
	}

	if ( ! empty( $post_date ) || ! empty( $updated_date ) || ! empty( $author ) ) {
		echo '<p class="' . esc_attr( $class ) . '">' . $post_date . $updated_date . $author . '</p>';
	}

}

/**
 * Post Header Summary
 */
function cwp_post_header_summary() {
	$recipe_id = \cwp_get_recipe_id();
	if ( empty( $recipe_id ) ) {
		return;
	}

	$recipe = \WPRM_Template_Shortcodes::get_recipe( $recipe_id );
	if ( ! empty( $recipe->summary() ) ) {
		echo '<div class="post-header__summary">' . wpautop( $recipe->summary() ) . '</div>';
	}
}

/**
 * Post Header Recipe Key
 */
function cwp_post_header_recipe_key() {
	if ( function_exists( 'Cultivate\Recipe_Key\display' ) ) {
		\Cultivate\Recipe_Key\display( [ 'link' => true ] );
	}
}

/**
 * Post Terms
 */
function cwp_post_terms( $atts = [] ) {
	$label   = ! empty( $atts['label'] ) ? '<span>' . $atts['label'] . '</span>' : '';
	$class   = ! empty( $atts['class'] ) ? $atts['class'] : 'post-terms';
	$sep     = ! empty( $atts['sep'] ) ? $atts['sep'] : ', ';
	$tax     = ! empty( $atts['taxonomy'] ) ? $atts['taxonomy'] : 'category';
	echo get_the_term_list( get_queried_object_id(), $tax, '<p class="' . esc_attr( $class ) . '">' . wp_kses_post( $label ), esc_attr( $sep ), '</p>' );
}

/**
 * Post Header Pin
 */
function cwp_post_header_pin( $atts = [] ) {

	$atts = shortcode_atts(
		[
			'id' => '',
			'hide_icon' => false,
			'icon_size' => '',
			'hide_label' => true,
			'label' => 'Pin',
			'class' => ''
		],
		$atts
	);

	foreach( $atts as $key => $value ) {
		if ( '' === $value ) {
			unset( $atts[ $key ] );
		}
	}

	if ( function_exists( 'Cultivate\Blocks\Social_Share\link' ) ) {
		echo Social_Share\link( 'pinterest', $atts );
	}
}

/**
 * Post Header Share
 */
function cwp_post_header_share( $atts = [] ) {

	$atts = shortcode_atts(
		[
			'id' => '',
			'hide_icon' => false,
			'icon_size' => '',
			'hide_label' => true,
			'label' => 'Share',
			'class' => ''
		],
		$atts
	);

	foreach( $atts as $key => $value ) {
		if ( '' === $value ) {
			unset( $atts[ $key ] );
		}
	}

	if ( function_exists( 'Cultivate\Blocks\Social_Share\link' ) ) {
		echo Social_Share\link( 'facebook', $atts );
	}
}

/**
 * Post Header Favorite
 */
function cwp_post_header_favorite( $atts = [] ) {

	if ( ! function_exists( 'cwp_favorites_service' ) ) {
		return;
	}

	$service = cwp_favorites_service();

	if ( empty( $service ) ) {
		return;
	}

	$atts = shortcode_atts(
		[
			'label' => '',
			'style' => ''
		],
		$atts
	);

	$classes = [ 'post-header__favorite', 'wp-element-button' ];
	if ( ! empty( $atts['style'] ) ) {
		$classes[] = 'is-style-' . $atts['style'];
	}

	echo '<button class="' . esc_attr( join( ' ', $classes ) ) . '">';
	echo '<span class="saved">' . cwp_icon( [ 'icon' => 'heart-full' ] ) . '</span>';
	echo '<span class="save">' . cwp_icon( [ 'icon' => 'heart-empty' ] ) . '</span>';
	if ( empty( $atts['label'] ) ) {
		echo '<span class="screen-reader-text">Save to Favorites</span>';
	} else {
		echo esc_html( $atts['label'] );
	}
	echo '</button>';
}

/**
 * Post Header WPRM Save
 */
function cwp_post_header_wprm_save( $atts = [] ) {
	$recipe_id = \cwp_get_recipe_id();
	if ( empty( $recipe_id ) ) {
		return;
	}

	$atts = shortcode_atts(
		[
			'text_color' => 'var(--wp--preset--color--foreground)',
			'icon_color' => 'var(--wp--preset--color--foreground)',
			'icon' => 'cwp-heart',
			'icon_added' => 'cwp-heart-full',
			'text' => 'Save',
			'text_added' => 'Saved'
		],
		$atts
	);

	echo do_shortcode( '[wprm-recipe-add-to-collection id="' . $recipe_id . '" text_color="' . $atts['text_color'] . '" icon_color="' . $atts['icon_color'] . '" icon="' . $atts['icon'] . '" text="' . $atts['text'] . '" icon_added="' . $atts['icon_added'] . '" text_added="' . $atts['text_added'] . '"]' );

}

/**
 * Post Header Print
 */
function cwp_post_header_print() {
	$url       = 'javascript:window.print()';
	$recipe_id = cwp_get_recipe_id();
	if ( ! empty( $recipe_id ) ) {
		$url = home_url( '/wprm_print/' . $recipe_id . '/' );
	}

	echo '<a class="post-header__print" href="' . $url . '"><span class="screen-reader-text">Print</span>' . cwp_icon( [ 'icon' => 'printer' ] ) . '</a>';
}

/**
 * Post Header Email
 */
function cwp_post_header_email( $atts = [] ) {

	$atts = shortcode_atts(
		[
			'id' => '',
			'hide_icon' => false,
			'icon_size' => '',
			'hide_label' => true,
			'label' => 'Email',
			'class' => ''
		],
		$atts
	);

	foreach( $atts as $key => $value ) {
		if ( '' === $value ) {
			unset( $atts[ $key ] );
		}
	}

	if ( function_exists( 'Cultivate\Blocks\Social_Share\link' ) ) {
		echo Social_Share\link( 'email', $atts );
	}
}

/**
 * Recipe URL
 */
function cwp_post_recipe_url( $id = false ) {
	$anchor = '';
	$id = $id ? intval( $id ) : get_the_ID();
	$wprm_recipe_id = cwp_get_recipe_id( $id );

	if ( ! empty( $wprm_recipe_id ) ) {
		$anchor = '#wprm-recipe-container-' . $wprm_recipe_id;
	}

	if ( class_exists( 'Tasty_Recipes' ) ) {
		$recipe_ids = Tasty_Recipes::get_recipe_ids_for_post( $id );
		if ( ! empty( $recipe_ids ) ) {
			$recipe_id = array_shift( $recipe_ids );
			$anchor    = '#tasty-recipes-' . $recipe_id . '-jump-target';
		}
	}

	if ( class_exists( '\Mediavine\Create\Creations_Jump_To_Recipe' ) ) {
		$recipe_atts = \Mediavine\Create\Creations_Jump_To_Recipe::get_jtr_atts( get_post_field( 'post_content', $id ) );
		$recipe_id   = ! empty( $recipe_atts ) ? $recipe_atts['id'] : false;
		if ( ! empty( $recipe_id ) ) {
			$anchor = '#mv-creation-' . $recipe_id;
			if ( class_exists( '\Mediavine\Settings' ) ) {
				$jtr_enabled = \Mediavine\Settings::get_setting( 'mv_create_enable_jump_to_recipe', false );
				if ( $jtr_enabled ) {
					$anchor = $anchor . '-jtr';
				}
			}
		}
	}

	if ( ! empty( $anchor ) ) {
		return apply_filters( 'cwp_post_recipe_url', trailingslashit( get_permalink( $id ) ) . $anchor, $anchor );
	} else {
		return false;
	}
}

/**
 * Post Header Recipe Jump
 */
function cwp_post_header_recipe_jump( $atts = [] ) {

	$atts = shortcode_atts(
		[
			'label' => 'Jump to Recipe',
			'style' => '',
		],
		$atts
	);
	$anchor = cwp_post_recipe_url();
	$classes = [ 'wp-element-button' ];
	if ( ! empty( $atts['style'] ) ) {
		$classes[] = 'is-style-' . $atts['style'];
	}

	if ( ! empty( $anchor ) ) {
		echo '<a class="' . esc_attr( join( ' ', $classes ) ) . '" href="' . esc_url( $anchor ) . '">' . esc_html( $atts['label'] ) . '</a>';
	}
}


/**
 * Post Header Recipe Video URL
 */
function cwp_recipe_video_url() {
	global $post;

	if ( has_block( 'cwp/video-container', $post ) ) {
		return '#video';
	}

	if ( ! class_exists( 'WPRM_Recipe_Manager' ) ) {
		return false;
	}

	$recipes = \WPRM_Recipe_Manager::get_recipe_ids_from_content( $post->post_content );
	if( empty( $recipes ) )
		return false;

	$recipe_id = $recipes[0];
	$recipe = WPRM_Recipe_Manager::get_recipe( $recipe_id );
	if ( $recipe->video() ) {
		return '#wprm-recipe-video-container-' . $recipe_id;
	}
	return false;
}

/**
 * Post Header Video Jump
 */
function cwp_post_header_video_jump( $atts = [] ) {
	$atts = shortcode_atts(
		[
			'label' => 'Jump to Video',
			'style' => '',
		],
		$atts
	);

	$video_url = cwp_recipe_video_url();
	if ( $video_url ) {
		$classes = [ 'wp-element-button' ];
		if ( ! empty( $atts['style'] ) ) {
			$classes[] = 'is-style-' . $atts['style'];
		}
		echo '<a class="' . esc_attr( join( ' ', $classes ) ) . '" href="' . $video_url . '">' . esc_attr( $atts['label'] ) . '</a>';
	}
}


/**
 * Post Header Comment Jump
 */
function cwp_post_header_comment_jump( $atts = [] ) {
	$atts = shortcode_atts(
		[
			'label' => 'Jump to Comments',
			'style' => '',
		],
		$atts
	);
	if ( comments_open() ) {
		$classes = [ 'wp-element-button' ];
		if ( ! empty( $atts['style'] ) ) {
			$classes[] = 'is-style-' . $atts['style'];
		}
		echo '<a class="' . esc_attr( join( ' ', $classes ) ) . '" href="#respond">' . esc_attr( $atts['label'] ) . '</a>';
	}
}

/**
 * Entry Category
 */
function cwp_entry_category() {
	$category = \cwp_first_term();
	if ( ! empty( $category ) ) {
		$title = get_term_meta( $category->term_id, 'cwp_short_title', true );
		if ( empty( $title ) ) {
			$title = $category->name;
		}
		echo '<p class="entry-category">' . esc_html( $title ) . '</p>';
	}
}

/**
 * Entry Category Link
 */
function cwp_entry_category_link() {
	$category = \cwp_first_term();
	if ( ! empty( $category ) ) {
		$title = get_term_meta( $category->term_id, 'cwp_short_title', true );
		if ( empty( $title ) ) {
			$title = $category->name;
		}
		echo '<p class="entry-category"><a href="' . esc_url( get_term_link( $category, 'category' ) ) . '">' . $title . '</a></p>';
	}
}

/**
 * Entry Author
 */
function cwp_entry_author() {
	echo '<p class="entry-author">' . get_the_author() . '</p>';
}

/**
 * Entry Author with avatar
 */
function cwp_entry_author_avatar() {
	echo '<p class="entry-author">' . get_avatar( get_the_author_meta( 'ID' ), 30 ) . get_the_author() . '</p>';

}

/**
 * Entry Date
 */
function cwp_entry_date() {
	echo '<p>' . get_the_date( 'F j, Y' ) . '</p>';
}

/**
 * Entry Comments
 */
function cwp_entry_comments() {
	echo '<p>' . get_comments_number_text() . '</p>';
}

/**
 * Entry Comments Link
 */
function cwp_entry_comments_link() {
	echo '<p class="post-comments"><a href="#comments">' . get_comments_number_text() . '</a></p>';
}

/**
 * Entry Comments Small
 */
function cwp_entry_comment_small() {
	echo '<p>' . cwp_icon( [ 'icon' => 'comment', 'size' => 16 ] ) . get_comments_number() . '</p>';
}

/**
 * Entry Date & Comments
 */
function cwp_entry_date_comments() {
	echo '<p>' . get_the_date( 'F j, Y' ) . ' &bull; ' . get_comments_number_text() . '</p>';
}

/**
 * WPRM Total Time
 */
function cwp_wprm_total_time() {
	$recipe_id = \cwp_get_recipe_id();
	if ( empty( $recipe_id ) ) {
		return;
	}

	$output = wp_strip_all_tags( cwp_strip_tags_by_classname( do_shortcode( '[wprm-recipe-time type="total" shorthand="1" id="' . $recipe_id . '"]' ), 'wprm-screen-reader-text' ) );
	if ( ! empty( $output ) ) {
		echo '<p class="entry-total-time">' . cwp_icon( [ 'icon' => 'clock', 'size' => 16 ] ) . $output . '</p>';
	}
}

/**
 * WPRM Rating Small
 */
function cwp_wprm_rating_small() {
	$recipe_id = cwp_get_recipe_id();
	if ( empty( $recipe_id ) ) {
		return;
	}

	$recipe = \WPRM_Template_Shortcodes::get_recipe( $recipe_id );
	$rating = $recipe->rating();

	if ( ! empty( $rating ) ) {
		echo '<p class="entry-rating">' . cwp_icon( [ 'icon' => 'star-full', 'size' => 16 ] ) . $rating['average'] . '</p>';
	}

}

/**
 * WPRM Times
 */
function cwp_wprm_times() {
	$recipe_id = \cwp_get_recipe_id();
	if ( empty( $recipe_id ) ) {
		return;
	}

	$recipe = \WPRM_Template_Shortcodes::get_recipe( $recipe_id );
	$output = [];
	$times  = [ 'prep', 'cook', 'custom', 'total' ];
	foreach ( $times as $time ) {
		$item  = do_shortcode( '[wprm-recipe-time type="' . $time . '" shorthand="1" id="' . $recipe_id . '"]' );
		$label = 'custom' === $time ? $recipe->custom_time_label() : $time;
		if ( ! empty( $item ) ) {
			$item = cwp_strip_tags_by_classname( $item, 'wprm-screen-reader-text' );
			$output[ $label ] = wp_strip_all_tags( $item );
		}
	}

	if ( ! empty( $output ) ) {
		echo '<p class="entry-times">';
		foreach ( $output as $label => $item ) {
			echo '<span class="time"><span class="time__label">' . $label . '</span>' . $item . '</span> ';
		}
		echo '</p>';
	}

}

/**
 * WPRM Rating
 */
function cwp_wprm_rating( $atts = [] ) {
	$atts = shortcode_atts(
		[
			'display'    => 'stars-details',
			'style'      => 'inline',
			'voteable'   => 1,
			'icon'       => 'star-empty',
			'icon_color' => 'var(--wp--custom--color--star)',
			'text_style' => 'none'
		],
		$atts
	);

	$recipe_id = \cwp_get_recipe_id();
	if ( empty( $recipe_id ) ) {
		return;
	}

	$output = '';
	foreach ( $atts as $key => $value ) {
		$output .= esc_attr( $key ) . '="' . esc_attr( $value ) . '" ';
	}
	$output = do_shortcode( '[wprm-recipe-rating ' . trim( $output ) . ']' );
	if ( ! empty( $output ) ) {
		echo '<div class="cwp-wprm-rating">' . $output . '</div>';
	}
}
