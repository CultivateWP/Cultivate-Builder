<?php
/**
 * No Results partial
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

echo '<section class="no-results not-found">';

echo '<header class="entry-header"><h1 class="entry-title">' . esc_html__( 'Nothing Found', 'cultivate_textdomain' ) . '</h1></header>';
echo '<div class="entry-content">';

if ( is_search() ) {

	echo '<p>' . esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'cultivate_textdomain' ) . '</p>';
	get_search_form();

} else {

	echo '<p>' . esc_html__( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'cultivate_textdomain' ) . '</p>';
	get_search_form();
}

echo '</div>';
echo '</section>';
