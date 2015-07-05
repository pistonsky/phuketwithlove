<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header box	">
		<?php if ( is_single() ) : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else : ?>
		<h1 class="entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h1>
		<?php endif; // is_single() ?>

		<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
		<div class="entry-thumbnail">
			<div class="the-date">
				<span class="date"><?php echo get_the_date('d') ?></span>
				<span class="month"><?php echo get_the_date('M') ?></span>
			</div>
			<?php the_post_thumbnail(); ?>
		</div>
		<?php endif; ?>

		<?php /* Do not show meta bar if post type is properties */ if(get_post_type() != 'properties') : ?>
			<div class="entry-meta">
				<?php $author_name = get_the_author(); if( ! empty( $author_name ) ) : ?>
				<div class="meta author">by <?php the_author() ?></div>
				<?php endif ?>
				<div class="meta posted-in">posted in <?php the_category(', ') ?></div>

				<?php
				// Translators: used between list items, there is a space after the comma.
				$tag_list = get_the_tag_list( '', __( ', ', THEME_NAME ) );
				if( ! empty($tag_list)) : ?>
				<div class="meta comments">

					<?php echo '<span class="tags-links">' . $tag_list . '</span>'; ?>
				</div>
				<?php endif ?>
			

				<div class="meta comments"><?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a comment', THEME_NAME ) . '</span>', __( 'One comment so far', THEME_NAME ), __( 'View all % comments', THEME_NAME) ); ?></div>
					<?php edit_post_link( __( 'Edit', THEME_NAME ), '<div class="meta flat">', '</div>' ); ?>
			</div><!-- .entry-meta -->
		<?php endif ?>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php if(is_single()): ?>
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', THEME_NAME ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="paging-page-content"><span class="page-links-title">' . __( 'Pages:', THEME_NAME ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
		<?php else: ?>
			<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', THEME_NAME ) ); ?>
		<?php endif ?>
	</div><!-- .entry-content -->
	<?php endif; ?>
	
</article><!-- #post -->
