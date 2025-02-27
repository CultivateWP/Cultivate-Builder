<?php
/**
 * WordPress Cleanup
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Header Meta Tags
 */
function cwp_header_meta_tags() {
	echo '<meta charset="' . esc_attr( get_bloginfo( 'charset' ) ) . '">';
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
	echo '<link rel="profile" href="http://gmpg.org/xfn/11">';
	echo '<link rel="pingback" href="' . esc_url( get_bloginfo( 'pingback_url' ) ) . '">';
}
add_action( 'wp_head', 'cwp_header_meta_tags' );

/**
 * Extra body classes
 *
 * @param array $classes Body classes.
 */
function cwp_extra_body_classes( $classes ) {
	if ( is_singular() ) {
		$classes[] = 'singular';
	}

	if ( function_exists( 'adthrive_ads_autoload' ) && function_exists( 'wp_get_environment_type' ) && 'staging' === wp_get_environment_type() ) {
		$classes[] = 'adthrive-staging';
	}

	return $classes;
}
add_filter( 'body_class', 'cwp_extra_body_classes' );

/**
 * Clean body classes
 *
 * @param array $classes Body classes.
 */
function cwp_clean_body_classes( $classes ) {

	$allowed_classes = [
		'singular',
		'single',
		'page',
		'archive',
		'home',
		'search',
		'admin-bar',
		'logged-in',
		'wp-embed-responsive',
		'cultivate-category-page',
		'affiliate-links-archive'
	];

	// AdThrive Classes.
	$allowed_classes = array_merge(
		$allowed_classes,
		[
			'adthrive-disable-all',
			'adthrive-disable-in-image',
			'adthrive-disable-content',
			'adthrive-disable-video',
			'adthrive-staging',
			'adthrive-device-desktop',
			'adthrive-device-tablet',
			'adthrive-device-phone',
		]
	);

	if ( function_exists( 'cwp_page_layout_options' ) ) {
		$allowed_classes = array_merge( $allowed_classes, cwp_page_layout_options() );
	}

	return array_intersect( $classes, $allowed_classes );

}
add_filter( 'body_class', 'cwp_clean_body_classes', 20 );

/**
 * ID specific body classes
 */
function cwp_id_body_classes( $classes ) {
	if ( is_page() ) {
		$classes[] = 'page-id-' . get_queried_object_id();
	} elseif( is_singular() ) {
		$classes[] = 'postid-' . get_queried_object_id();
	} elseif( is_category() || is_tag() || is_tax() ) {
		$classes[] = 'term-id-' . get_queried_object_id();
	}
	if ( is_singular() ) {
		$custom = get_post_meta( get_queried_object_id(), 'cwp_custom_body_class', true );
		if ( ! empty( $custom ) ) {
			$custom = explode( ' ', $custom );
			foreach( $custom as $custom_class ) {
				$classes[] = esc_attr( $custom_class );
			}
		}
	}
	return $classes;
}
add_filter( 'body_class', 'cwp_id_body_classes', 25 );

/**
 * Clean Nav Menu Classes
 *
 * @param array $classes Nav item classes.
 */
function cwp_clean_nav_menu_classes( $classes ) {
	if ( ! is_array( $classes ) ) {
		return $classes;
	}

	foreach ( $classes as $i => $class ) {

		// Remove class with menu item id.
		$id = strtok( $class, 'menu-item-' );
		if ( 0 < intval( $id ) ) {
			unset( $classes[ $i ] );
		}

		// Remove menu-item-type-*.
		if ( false !== strpos( $class, 'menu-item-type-' ) ) {
			unset( $classes[ $i ] );
		}

		// Remove menu-item-object-*.
		if ( false !== strpos( $class, 'menu-item-object-' ) ) {
			unset( $classes[ $i ] );
		}

		// Change page ancestor to menu ancestor.
		if ( 'current-page-ancestor' === $class ) {
			$classes[] = 'current-menu-ancestor';
			unset( $classes[ $i ] );
		}
	}

	// Remove submenu class if depth is limited.
	if ( isset( $args->depth ) && 1 === $args->depth ) {
		$classes = array_diff( $classes, array( 'menu-item-has-children' ) );
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'cwp_clean_nav_menu_classes', 5 );

/**
 * Clean Post Classes
 *
 * @param array $classes Post Classes.
 */
function cwp_clean_post_classes( $classes ) {

	if ( ! is_array( $classes ) ) {
		return $classes;
	}

	$allowed_classes = [
		'entry',
		'type-' . get_post_type(),
	];

	return array_intersect( $classes, $allowed_classes );
}
add_filter( 'post_class', 'cwp_clean_post_classes', 5 );

/**
 * Archive Title, remove prefix
 *
 * @param string $title Title.
 */
function cwp_archive_title_remove_prefix( $title ) {
	$title_pieces = explode( ': ', $title );
	if ( count( $title_pieces ) > 1 ) {
		unset( $title_pieces[0] );
		$title = join( ': ', $title_pieces );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'cwp_archive_title_remove_prefix' );

/**
 * Use custom website as author url
 *
 * @param string $link Link.
 * @param int    $author_id Author ID.
 */
function cwp_custom_author_url( $link, $author_id ) {
	$website = get_the_author_meta( 'user_url', $author_id );
	if ( ! empty( $website ) && false !== strpos( $website, home_url() ) ) {
		$link = esc_url_raw( $website );
	}
	return $link;
}
add_filter( 'author_link', 'cwp_custom_author_url', 10, 2 );

/**
 * Excerpt More
 */
function cwp_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'cwp_excerpt_more' );

// Remove inline CSS for emoji.
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Max srcset width
 *
 * @param int   $max_width  The maximum image width to be included in the 'srcset'. Default '2048'.
 * @param int[] $size_array {
 *     An array of requested width and height values.
 *
 *     @type int $0 The width in pixels.
 *     @type int $1 The height in pixels.
 * }
 */
function be_max_srcset_width( $max_width, $size_array ) {
	return 1200;
}
add_filter( 'max_srcset_image_width', 'be_max_srcset_width', 10, 2 );

// Disable Convert Pro fonts
add_filter( 'cpro_disable_google_font', '__return_false' );

// Remove empty headings
add_filter(
	'the_content',
	function( $content ) {
		$content = preg_replace( '/<h[1-6][^>]*><\/h[1-6]>/', '', $content );
		return $content;
	}
);

/**
 * Add additional social account inputs to user profile.
 *
 * @param array $contactmethods Array of contact methods.
 */
function cwp_social_profiles( $contactmethods ) {
	if ( ! isset( $contactmethods['tiktok'] ) ) {
		$contactmethods['tiktok'] = 'TikTok profile URL';
	}
	return $contactmethods;
}
add_filter( 'user_contactmethods', 'cwp_social_profiles', 10, 1 );
