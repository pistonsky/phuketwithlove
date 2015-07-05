<?php

/**
 * Template name: Blog
 */
get_header(); ?>

<?php 
global $sidebar_name;
$sidebar_name = 'blog';

?>

<?php $sidebar_position_mobile = estetico_get_setting('sidebar_position_mobile'); ?>

<?php if($sidebar_position_mobile == 'before') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

<?php

$page 			= (int)get_query_var('paged');
$posts_per_page = (int)get_option('posts_per_page');

if( 0 === $page ) {
	$page = 1;
}

$args = array(
	'posts_per_page' 	=> $posts_per_page,
	'offset' 			=> ($page - 1) * $posts_per_page
);
global $posts_query;
$posts_query = new WP_Query($args);

?>

<div class="main-content">

	<?php if ( $posts_query->have_posts() ) : ?>

		<?php /* The loop */ ?>
		<?php while ( $posts_query->have_posts() ) :  $posts_query->the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>

		<?php estetico_pagination_nav($posts_query) ?>

		<?php wp_reset_query(); ?>
		
	<?php else : ?>
		<?php get_template_part( 'content', 'none' ); ?>
	<?php endif; ?>

</div>

<?php if($sidebar_position_mobile == 'after') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>
				
<?php get_footer(); ?>