<?php
/**
 * Login Logo
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Login Logo URL
 *
 * @param string $url URL.
 */
function cwp_login_header_url( $url ) {
	return esc_url( home_url() );
}
add_filter( 'login_headerurl', 'cwp_login_header_url' );
add_filter( 'login_headertext', '__return_empty_string' );

/**
 * Login Logo
 */
function cwp_login_logo() {

	$image_id = get_option( 'options_cwp_site_logo' );
	$image = wp_get_attachment_image_src( $image_id, 'full' );
	if ( empty( $image ) ) {
		return;
	}

	$logo   = $image[0];
	$height = floor( $image[2] / $image[1] * 312 );
	?>
		<style type="text/css">
		.login h1 a {
			background-image: url(<?php echo esc_url( $logo ); ?>);
			background-size: contain;
			background-repeat: no-repeat;
			background-position: center center;
			display: block;
			overflow: hidden;
			text-indent: -9999em;
			width: 312px;
			height: <?php echo esc_attr( $height ); ?>px;
		}
		</style>
		<?php
}
add_action( 'login_head', 'cwp_login_logo' );
