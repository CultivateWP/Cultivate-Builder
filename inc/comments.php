<?php
/**
 * Comments
 *
 * @package      CultivateBuilder
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace Cultivate\Comments;

/**
 * Display Comments
 */
function display_comments() {
	echo '<div id="comments" class="entry-comments">';

	\comment_form();

	if ( \have_comments() ) :
		echo '<h2 class="comments-title">' . esc_html( get_comments_number_text() ) . '</h2>';
		comment_navigation( 'before' );
		echo '<ol class="comment-list">';
		\wp_list_comments( [ 'style' => 'ol', 'type' => 'comment', 'callback' =>  __NAMESPACE__ . '\comment_callback' ] );
		echo '</ol>';
		comment_navigation( 'after' );
	endif;

	echo '</div>';
}
add_action( 'tha_comments_before', __NAMESPACE__ . '\display_comments', 40 );

/**
 * Comments Tempalte
 */
function comments_template() {
	if ( is_single() && ( comments_open() || get_comments_number() ) ) {
		\comments_template();
	}
}
add_action( 'tha_content_while_after', __NAMESPACE__ . '\comments_template' );

/**
 * Comment Navigation
 *
 * @param string $location Location.
 */
function comment_navigation( $location = '' ) {
	$comment_nav_locations = [ 'after' ];
	if ( ! in_array( $location, $comment_nav_locations, true ) ) {
		return;
	}

	if ( get_comment_pages_count() <= 1 ) {
		return;
	}

	$output  = '<nav id="comment-nav-' . esc_attr( $location ) . '" class="navigation comment-navigation" role="navigation">';
	$output .= '<h3 class="screen-reader-text">' . esc_html__( 'Comment navigation', 'cultivate_textdomain' ) . '</h3>';
	$output .= '<div class="nav-links">';
	$output .= '<div class="nav-previous">' . get_previous_comments_link( esc_html__( 'Older Comments', 'cultivate_textdomain' ) ) . '</div>';
	$output .= '<div class="nav-next">' . get_next_comments_link( esc_html__( 'Newer Comments', 'cultivate_textdomain' ) ) . '</div>';
	$output .= '</div>';
	$output .= '</nav>';

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is hardcoded and safe, not set via input.
	echo apply_filters( 'cwp_comment_navigation', $output, $location );
}

/**
 * Comment links as buttons
 */
function comment_link_attributes( $atts = '' ) {
	return ' class="wp-block-button__link"';
}
add_filter( 'previous_comments_link_attributes', __NAMESPACE__ . '\comment_link_attributes' );
add_filter( 'next_comments_link_attributes', __NAMESPACE__ . '\comment_link_attributes' );

/**
 * Staff comment class
 *
 * @param array       $classes    An array of comment classes.
 * @param string      $class      A comma-separated list of additional classes added to the list.
 * @param int         $comment_id The comment ID.
 * @param WP_Comment  $comment    The comment object.
 * @param int|WP_Post $post_id    The post ID or WP_Post object.
 */
function staff_comment_class( $classes, $class, $comment_id, $comment, $post_id ) {
	if ( empty( $comment->user_id ) ) {
		return $classes;
	}
	$staff_roles = array( 'comment_manager', 'author', 'editor', 'administrator' );
	$staff_roles = apply_filters( 'cwp_staff_roles', $staff_roles );
	$user        = get_userdata( $comment->user_id );
	if ( $user instanceof \WP_User && is_array( $user->roles ) && ! empty( array_intersect( $user->roles, $staff_roles ) ) ) {
		$classes[] = 'staff';
	}
	return $classes;
}
add_filter( 'comment_class', __NAMESPACE__ . '\staff_comment_class', 10, 5 );


/**
 * Remove avatars from comment list
 *
 * @param string $avatar Avatar.
 */
function remove_avatars_from_comments( $avatar ) {
	global $in_comment_loop;
	return $in_comment_loop ? '' : $avatar;
}
add_filter( 'get_avatar', __NAMESPACE__ . '\remove_avatars_from_comments' );

/**
 * Remove URL field from comment form
 *
 * @param array $fields Comment form fields.
 */
function remove_url_from_comment_form( $fields ) {
	unset( $fields['url'] );
	return $fields;
}
add_filter( 'comment_form_default_fields', __NAMESPACE__ . '\remove_url_from_comment_form' );

/**
 * Remove URL from existing comments
 *
 * @param string $author_link HTML of author link.
 * @param string $author Author Name.
 */
function remove_url_from_existing_comments( $author_link, $author ) {
	return $author;
}
add_filter( 'get_comment_author_link', __NAMESPACE__ . '\remove_url_from_existing_comments', 10, 2 );

/**
 * Comment form, button class
 *
 * @param array $args Comment Form args.
 */
function comment_form_button_class( $args ) {
	$args['class_submit'] = 'submit wp-element-button';
	$args['title_reply'] = __( 'Leave a comment', 'cultivate_textdomain' );
	$args['title_reply_before'] = str_replace( 'h3', 'h2', $args['title_reply_before'] );
	$args['title_reply_after'] = str_replace( 'h3', 'h2', $args['title_reply_after'] );
	return $args;
}
add_filter( 'comment_form_defaults', __NAMESPACE__ . '\comment_form_button_class' );


/**
 * Custom comment output.
 * Customized to remove the link from the comment date
 * @link https://developer.wordpress.org/reference/classes/walker_comment/html5_comment/
 *
 */
function comment_callback( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

	$commenter          = wp_get_current_commenter();
	$show_pending_links = ! empty( $commenter['comment_author'] );

	if ( $commenter['comment_author_email'] ) {
		$moderation_note = __( 'Your comment is awaiting moderation.' );
	} else {
		$moderation_note = __( 'Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.' );
	}
	?>
	<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $args['has_children'] ? 'parent' : '', $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
					if ( 0 != $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );
					}
					?>
					<?php
					$comment_author = get_comment_author_link( $comment );

					if ( '0' == $comment->comment_approved && ! $show_pending_links ) {
						$comment_author = get_comment_author( $comment );
					}

					printf(
						/* translators: %s: Comment author link. */
						__( '%s <span class="says">says:</span>' ),
						sprintf( '<b class="fn">%s</b>', $comment_author )
					);
					?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<?php

					printf(
						'<time datetime="%s">%s</time>',
						get_comment_time( 'c' ),
						sprintf(
							/* translators: 1: Comment date, 2: Comment time. */
							__( '%1$s at %2$s' ),
							get_comment_date( '', $comment ),
							get_comment_time()
						)
					);

					edit_comment_link( __( 'Edit' ), ' <span class="edit-link">', '</span>' );
					?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<em class="comment-awaiting-moderation"><?php echo $moderation_note; ?></em>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
			if ( '1' == $comment->comment_approved || $show_pending_links ) {
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply">',
							'after'     => '</div>',
						)
					)
				);
			}
			?>
		</article><!-- .comment-body -->
	<?php
}
