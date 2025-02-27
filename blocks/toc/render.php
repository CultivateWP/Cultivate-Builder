<?php
/**
 * Table of Contents block
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use Cultivate\Blocks\TOC;

$classes = [ 'wp-block-yoast-seo-table-of-contents',  'yoast-table-of-contents' ];
if ( wp_script_is( 'toc' ) ) {
	$classes[] = 'yoast-table-of-contents--no-js';
}
if ( ! empty( $block['className'] ) ) {
	$classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}

$title = __( 'Table of Contents', 'cultivate_textdomain' );

echo '<div class="' . esc_attr( join( ' ', $classes ) ) . '">';
echo '<h2 id="table-of-contents">' . esc_html( $title ) . '</h2>';
echo TOC\output();
echo '</div>';
