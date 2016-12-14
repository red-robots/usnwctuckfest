<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to shaken_comment which is
 * located in the functions.php file.
 *
 * @since 1.0
 * @alter 1.6
 */
?>
<?php if( ! comments_open() ) return; ?>
    <div id="comments"<?php if( !have_comments() ){ echo ' class="no-comments"'; } ?>>
    <?php if ( post_password_required() ) : ?>
    	<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'shaken' ); ?></p>
	</div><!-- #comments -->
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
?>

<?php if ( have_comments() ) : ?>
			<h2 id="comments-title"><?php echo('Questions / Comments'); ?></h2>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'shaken' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'shaken' ) ); ?></div>
                <div class="clearfix"></div>
			</div> <!-- .navigation -->
<?php endif; // check for comment navigation ?>

			<ol class="commentlist">
				<?php
					/* Loop through and list the comments. Tell wp_list_comments()
					 * to use shaken_comment() to format the comments.
					 * If you want to overload this in a child theme then you can
					 * define shaken_comment() and that will be used instead.
					 * See shaken_comment() in shaken/functions.php for more.
					 */
					wp_list_comments( array( 'callback' => 'shaken_comment' ) );
				?>
			</ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'shaken' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'shaken' ) ); ?></div>
                <div class="clearfix"></div>
			</div><!-- .navigation -->
<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments: ?>
	
	<h2 id="comments-title">Comments or Questions</h2>

<?php endif; // end have_comments() ?>





<?php //comment_form(); ?>
<?php //We are deleting the default comment form and substituting our own. ?>

<?php $comment_args = array( 'fields' => apply_filters( 'comment_form_default_fields', array(
    'author' => '<p class="comment-form-author">' .
                '<label for="author">' . __( 'Your Name' ) . '</label> ' .
                ( $req ? '<span class="required">*</span>' : '' ) .
                '<input id="author" name="author" type="text" value="' .
                esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
                '</p><!-- #form-section-author .form-section -->',
    'email'  => '<p class="comment-form-email">' .
                '<label for="email">' . __( 'Your Email' ) . '</label> ' .
                ( $req ? '<span class="required">*</span>' : '' ) .
                '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' .
		'</p><!-- #form-section-email .form-section -->',
    'url'    => '' ) ),
    'comment_field' => '<p class="comment-form-comment">' .
                '<label for="comment">' . __( 'Question or Comment? Let us know what you have to say.' ) . '</label>' .
                '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>' .
                '</p><!-- #form-section-comment .form-section -->',
    'comment_notes_after' => '',
);
comment_form($comment_args); ?>





</div><!-- #comments -->
