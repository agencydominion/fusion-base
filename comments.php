<div id="comments">
<?php if ( post_password_required() ) : ?>
	<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'fusion-base' ); ?></p>
</div><!-- #comments -->
	<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
		endif;
	?>
	<?php if ( have_comments() ) : ?>
		<h3 id="comments-title"><?php
		printf( _n( 'One Comment on %2$s', '%1$s Comments on %2$s', get_comments_number(), 'fusion-base' ),
		number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
		?></h3>
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav class="comments-nav">
			<div class="comments-nav-previous"><?php previous_comments_link( __( 'Older Comments &raquo;', 'fusion-base' ) ); ?></div>
			<div class="comments-nav-next"><?php next_comments_link( __( '&laquo; Newer Comments', 'fusion-base' ) ); ?></div>
		</nav> <!-- .navigation -->
	<?php endif; // check for comment navigation ?>
		<ol class="commentlist">
			<?php wp_list_comments( array( 'callback' => 'fsn_base_comment' ) ); ?>
		</ol>
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav class="comments-nav">
			<div class="comments-nav-previous"><?php previous_comments_link( __( 'Older Comments &raquo; ', 'fusion-base' ) ); ?></div>
			<div class="comments-nav-next"><?php next_comments_link( __( '&laquo; Newer Comments', 'fusion-base' ) ); ?></div>
		</nav><!-- .navigation -->
	<?php endif; // check for comment navigation ?>
	<?php else : // or, if we don't have comments:
	/* If there are no comments and comments are closed,
	* let's leave a little note, shall we?
	*/
	if ( ! comments_open() ) :
	?>
	<p class="nocomments"><?php _e( 'Comments are closed.', 'fusion-base' ); ?></p>
	<?php endif; // end ! comments_open() ?>
	<?php endif; // end have_comments() ?>
	<?php comment_form(); ?>
</div><!-- #comments -->