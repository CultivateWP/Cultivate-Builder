<?php
/**
 * Functions
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// General cleanup.
require_once get_template_directory() . '/inc/wordpress-cleanup.php';

// Theme.
require_once get_template_directory() . '/inc/tha-theme-hooks.php';
require_once get_template_directory() . '/inc/layouts.php';
require_once get_template_directory() . '/inc/helper-functions.php';
require_once get_template_directory() . '/inc/comments.php';
include_once get_template_directory() . '/inc/site-header.php';
include_once get_template_directory() . '/inc/post-header.php';
include_once get_template_directory() . '/inc/archive-header.php';
include_once get_template_directory() . '/inc/archive-navigation.php';
include_once get_template_directory() . '/inc/site-footer.php';

// Functionality.
require_once get_template_directory() . '/inc/block-areas.php';
require_once get_template_directory() . '/inc/blocks.php';
require_once get_template_directory() . '/inc/favorites.php';
include_once get_template_directory() . '/inc/login-logo.php';
require_once get_template_directory() . '/inc/loop.php';
include_once get_template_directory() . '/inc/recipe-first.php';
require_once get_template_directory() . '/inc/template-tags.php';

// Plugin Support.
require_once get_template_directory() . '/inc/acf.php';
include_once get_template_directory() . '/inc/feast.php';
include_once get_template_directory() . '/inc/tasty-recipes.php';
require_once get_template_directory() . '/inc/wordpress-seo.php';
include_once get_template_directory() . '/inc/wp-recipe-maker.php';

// Disable auto-updates for major versions
add_filter( 'allow_major_auto_core_updates', '__return_false' );

/**
 * Enqueue scripts and styles.
 */
function cwp_scripts() {

	wp_enqueue_script( 'theme-global', get_theme_file_uri( '/assets/js/global.js' ), [], filemtime( get_theme_file_path( '/assets/js/global.js' ) ), true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_style( 'theme-style', get_theme_file_uri( '/assets/css/main.css' ), array(), filemtime( get_theme_file_path( '/assets/css/main.css' ) ) );
	wp_enqueue_style( 'block-post-listing' );
	wp_enqueue_style( 'block-social-links' );

}
add_action( 'wp_enqueue_scripts', 'cwp_scripts' );

/**
 * Gutenberg scripts and styles
 */
function cwp_gutenberg_scripts() {
	wp_enqueue_script( 'theme-editor', get_theme_file_uri( '/assets/js/editor.js' ), array( 'wp-blocks', 'wp-dom' ), filemtime( get_theme_file_path( '/assets/js/editor.js' ) ), true );
}
add_action( 'enqueue_block_editor_assets', 'cwp_gutenberg_scripts' );

if ( ! function_exists( 'cwp_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function cwp_setup() {
		/*
		 * Make theme available for translation.
		 */
		load_theme_textdomain( 'cultivate_textdomain', get_template_directory() . '/languages' );

		// Editor Styles.
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/editor-style.css' );

		// Admin Bar Styling.
		add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Body open hook.
		add_theme_support( 'body-open' );

		// Remove block templates.
		remove_theme_support( 'block-templates' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Set the content width in pixels, based on the theme's design and stylesheet.
		 */
		$GLOBALS['content_width'] = apply_filters( 'cwp_content_width', 800 );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'cwp_small', 400, 0, false ); // leave this for mobile srcset
		add_image_size( 'cwp_square', 300, 300, true );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			]
		);

		// Gutenberg.

		// -- Responsive embeds
		add_theme_support( 'responsive-embeds' );

		// Custom Mediavine Create Jump to Recipe button placement.
		add_theme_support( 'mv_create_custom_jtr' );
	}

endif;
add_action( 'after_setup_theme', 'cwp_setup' );

/**
 * Template Hierarchy
 *
 * @param string $template Template.
 */
function cwp_template_hierarchy( $template ) {

	if ( is_search() ) {
		$template = get_query_template( 'archive' );
	}
	return $template;
}
add_filter( 'template_include', 'cwp_template_hierarchy' );
