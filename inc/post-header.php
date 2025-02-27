<?php
/**
 * Post Header
 *
 * @package      CultivateBuilder
 * @subpackage   post-header/2-full
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Post Header Elements
 */
function cwp_post_header_elements() {

	if ( ! is_singular( 'post' ) ) {
		return;
	}

	remove_action( 'tha_entry_top', 'cwp_entry_header' );
	remove_action( 'tha_content_top', 'cwp_breadcrumbs' );

	add_action( 'tha_header_after', 'cwp_post_header', 16 );
	add_action( 'tha_entry_content_before', 'cwp_post_intro', 1 );

}
add_action( 'wp_head', 'cwp_post_header_elements' );

/**
 * Post Header
 */
function cwp_post_header() {

	echo '<div class="post-header"><div class="wrap">';
	cwp_breadcrumbs();
	cwp_entry_header();
	cwp_post_header_summary();
	echo '<div class="post-header__lower">';
	echo '<div class="post-header__info">';
		cwp_post_header_avatar( 64 );
		echo '<div>';
			cwp_post_header_author();
			cwp_post_date( [ 'publish_label' => '', 'format' => 'M d, Y' ] );
			cwp_wprm_rating();
		echo '</div>';
	echo '</div>';

	echo '<div class="post-header__actions">';
	cwp_post_header_recipe_jump();
	echo '</div>';
	echo '</div>';

	echo '</div></div>';
}

/**
 * Post Intro
 */
function cwp_post_intro() {
	cwp_affiliate_disclosure();
}

/**
 * Affiliate Disclosure
 */
function cwp_affiliate_disclosure() {
	$content = get_option( 'options_cwp_affiliate_disclosure' );
	if ( ! empty( $content ) ) {
		echo '<div class="aff-disc">' . wp_kses_post( wpautop( $content ) ) . '</div>';
	}
}
