<?php
/**
 * Feast
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// add/remove/main category page.
remove_action( 'category_add_form_fields', 'Feast\TaxonommiesMeta\add_category_image', 10, 2 );
remove_filter( 'manage_edit-category_columns', 'Feast\TaxonommiesMeta\manage_edit_category_columns' );
remove_filter( 'manage_category_custom_column', 'Feast\TaxonommiesMeta\manage_category_custom_column', 10, 3 );

// individual category edit screen.
remove_action( 'category_edit_form_fields', 'Feast\TaxonommiesMeta\update_category_image', 10, 2 );

/**
 * Feast block styles
 */
function cwp_feast_block_styles() {

    // Only run if Feast is active
    if ( ! defined( 'FEAST_PLUGIN_VERSION' ) ) {
        return;
    }

    // Only load styles if Minimal UI is active
    $minimal_ui = get_option( 'feast_minimal_ui' );
    if ( ! $minimal_ui ) {
        return;
    }

    ?>
    <style type="text/css">
	.feast-category-index-list, .fsri-list {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr) );
		grid-gap: 57px 17px;
		list-style: none;
		list-style-type: none;
		margin: 17px 0 !important;
	}
	.feast-category-index-list li, .fsri-list li {
		min-height: 150px;
		text-align: center;
		position: relative;
		list-style: none !important;
		margin-left: 0 !important;
		list-style-type: none !important;
		overflow: hidden;
	}
	.feast-category-index-list li a.title {
		text-decoration: none;
	}
	.feast-category-index-list-overlay .fsci-title {
		position: absolute;
		top: 88%;
		left: 50%;
		transform: translate(-50%, -50%);
		background: #FFF;
		padding: 5px;
		color: #333;
		font-weight: bold;
		border: 2px solid #888;
		text-transform: uppercase;
		width: 80%;
	}
	.listing-item:focus-within, .wp-block-search__input:focus {outline: 2px solid #555; }
	.listing-item a:focus, .listing-item a:focus .fsri-title, .listing-item a:focus img { opacity: 0.8; outline: none; }
	a .fsri-title, a .fsci-title { text-decoration: none; }
	li.listing-item:before { content: none !important; } /* needs to override theme */
	.listing-item { display: grid; } .fsri-rating, .fsri-time { place-self: end center; } /* align time + rating bottom */
	.feast-image-frame, .feast-image-border { border: 3px solid #DDD; }
	.feast-image-round, .feast-image-round img { border-radius: 50%; }
	.feast-image-shadow { box-shadow: 3px 3px 5px #AAA; }
	.feast-line-through { text-decoration: line-through; }
	.feast-grid-full, .feast-grid-half, .feast-grid-third, .feast-grid-fourth, .feast-grid-fifth { display: grid; grid-gap: 57px 17px; }
	.feast-grid-full { grid-template-columns: 1fr !important; }
	.feast-grid-half { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
	.feast-grid-third { grid-template-columns: repeat(3, minmax(0, 1fr)) !important; }
	.feast-grid-fourth { grid-template-columns: repeat(4, minmax(0, 1fr)) !important; }
	.feast-grid-fifth { grid-template-columns: repeat(5, minmax(0, 1fr)) !important; }
	@media only screen and (min-width: 600px)  {
		.feast-category-index-list { grid-template-columns: repeat(4, minmax(0, 1fr) ); }
		.feast-desktop-grid-full { grid-template-columns: 1fr !important; }
		.feast-desktop-grid-half { grid-template-columns: repeat(2, 1fr) !important; }
		.feast-desktop-grid-third { grid-template-columns: repeat(3, 1fr) !important; }
		.feast-desktop-grid-fourth { grid-template-columns: repeat(4, 1fr) !important; }
		.feast-desktop-grid-fifth { grid-template-columns: repeat(5, 1fr) !important; }
		.feast-desktop-grid-sixth { grid-template-columns: repeat(6, 1fr) !important; }
	}
	@media only screen and (min-width: 1024px) {
		.feast-full-width-wrapper { width: 100vw; position: relative; left: 50%; right: 50%; margin: 37px -50vw; background: #F5F5F5; padding: 17px 0; }
		.feast-full-width-wrapper .feast-recipe-index { width: 1140px; margin: 0 auto; }
		.feast-full-width-wrapper .listing-item { background: #FFF; padding: 17px; }
	}
	.feast-prev-next { display: grid; grid-template-columns: 1fr;  border-bottom: 1px solid #CCC; margin: 57px 0;  }
	.feast-prev-post, .feast-next-post { padding: 37px 17px; border-top: 1px solid #CCC; }
	.feast-next-post { text-align: right; }
	@media only screen and (min-width: 600px) {
		.feast-prev-next { grid-template-columns: 1fr 1fr; border-bottom: none; }
		.feast-next-post { border-left: 1px solid #CCC;}
		.feast-prev-post, .feast-next-post { padding: 37px; }
	}
</style>
    <?php
}
add_action( 'enqueue_block_editor_assets', 'cwp_feast_block_styles' );
add_action( 'wp_head', 'cwp_feast_block_styles', 99 );
