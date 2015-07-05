<?php
/**
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */
?>

<?php get_header(); ?>

<?php $sidebar_position_mobile = estetico_get_setting('sidebar_position_mobile'); ?>

<?php if($sidebar_position_mobile == 'before') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

	<div class="main-content" role="main">

		<?php if ( have_posts() ) : ?>

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php estetico_pagination_nav() ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

	</div><!-- ./main-content -->

<?php if($sidebar_position_mobile == 'after') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

<?php get_footer(); ?>