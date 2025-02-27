<?php
/**
 * Site Footer
 *
 * @package      CultivateBuilder
 * @subpackage   site-footer/04
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use Cultivate\Blocks\Social_Links;

/**
 * Site Footer
 */
function cwp_site_footer() {

	echo '<div class="site-footer__inner">';
	echo '<a href="' . esc_url( trailingslashit( home_url() ) ) . '" class="site-footer__logo"><span class="screen-reader-text">' . get_bloginfo( 'name' ) . '</span>' . cwp_site_logo() . '</a>';

	if ( has_nav_menu( 'site-footer' ) ) {
		wp_nav_menu( [ 'theme_location' => 'site-footer', 'depth' => 1, 'container_class' => 'nav-footer' ] );
	}

	if ( function_exists( 'Cultivate\Blocks\Social_Links\site' ) ) {
		echo Social_Links\site();
	}

	echo '</div>';

}
add_action( 'tha_footer_top', 'cwp_site_footer' );

/**
 * Footer Copyright
 */
function cwp_footer_copyright() {

	$copyright = '<span class="site-footer__copyright">&copy;' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. All rights reserved.</span>';
	$content = '';
	$social  = '';

	if ( function_exists( 'get_field' ) ) {
		$links = get_field( 'cwp_footer_copyright_links', 'option' );
		if ( ! empty( $links ) ) {
			$link_output = [];
			foreach( $links as $link ) {
				if ( empty( $link['link']['url'] ) ) {
					continue;
				}
				$target      = ! empty( $link['link']['target'] ) ? ' target="' . $link['link']['target'] . '"' : '';
				$link_output[] = '<a href="' . esc_url( $link['link']['url'] ) . '"' . $target . '>' . esc_html( $link['link']['title'] ) . '</a>';
			}
			$content .= ' ' . join( ' &bull; ', $link_output );
		}
	}
	$content .= ' &bull; Powered by <a href="https://cultivatewp.com" target="_blank" rel="noopener nofollow">CultivateWP</a>.';

	if ( ! empty( $content ) ) {
		$content = '<span class="site-footer__links">' . $content . '</span>';
	}

	echo '<div class="site-footer__bottom"><div class="wrap">' . $social . wp_kses_post( wpautop( $copyright . $content ) ) . '</div></div>';

}
add_action( 'tha_footer_after', 'cwp_footer_copyright' );

/**
 * Register nav menus
 */
function cwp_register_footer_menus() {
	register_nav_menus(
		[
			'site-footer' => esc_html__( 'Footer Menu', 'cultivate_textdomain' ),
		]
	);

}
add_action( 'after_setup_theme', 'cwp_register_footer_menus', 20 );
