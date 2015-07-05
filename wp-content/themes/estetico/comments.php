<?php

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

		
<div class="train-railing separator">
		<div class="railing"></div>
		<h3 class="train"><?php echo __( 'Comments', THEME_NAME ) ?></h3>
		<div class="railing"></div>
	</div>
		
		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 74,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php
			// Are there comments to navigate through?
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
		?>
		<nav class="navigation comment-navigation" role="navigation">
			<h1 class="screen-reader-text section-heading"><?php _e( 'Comment navigation', THEME_NAME ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', THEME_NAME ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', THEME_NAME ) ); ?></div>
		</nav><!-- .comment-navigation -->
		<?php endif; // Check for comment navigation ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="no-comments"><?php _e( 'Comments are closed.' , THEME_NAME ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php comment_form(array(
		'title_reply' => __('Leave a comment', THEME_NAME),
		'label_submit' => __('Send', THEME_NAME)
	)); ?>

</div><!-- #comments -->