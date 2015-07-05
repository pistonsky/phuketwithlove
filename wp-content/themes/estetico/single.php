<?php

get_header(); 

global $sidebar_name; $sidebar_name = 'blog'; 

?>

<?php $sidebar_position_mobile = estetico_get_setting('sidebar_position_mobile'); ?>

<?php if($sidebar_position_mobile == 'before') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

	<div class="main-content" role="main">

		<?php $unsupported_post_formats = array('audio', 'gallery'); $post_format = get_post_format(); if( in_array( $post_format, $unsupported_post_formats )  ) : ?>

			<p><?php echo sprintf( __('We are sorry, but we don not yet support <strong>%s</strong> post format.', THEME_NAME), $post_format ) ?></p>

			<?php estetico_paging_nav(); ?>

		<?php else : ?>

		<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content' ); ?>
				<?php estetico_paging_nav(); ?>
				<?php comments_template(); ?>

			<?php endwhile; ?>
		<?php endif ?>

	</div><!-- .main-content -->

<?php if($sidebar_position_mobile == 'after') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

<?php get_footer(); ?>