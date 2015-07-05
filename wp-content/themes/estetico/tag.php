<?php

get_header(); 

?>

<?php $sidebar_position_mobile = estetico_get_setting('sidebar_position_mobile'); ?>

<?php if($sidebar_position_mobile == 'before') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

<div class="main-content" role="main">

	<?php if ( have_posts() ) : ?>
		<header class="archive-header">
			<h1 class="archive-title"><?php printf( __( 'Tag Archives: %s', THEME_NAME ), single_tag_title( '', false ) ); ?></h1>

			<?php if ( tag_description() ) : // Show an optional tag description ?>
			<div class="archive-meta"><?php echo tag_description(); ?></div>
			<?php endif; ?>
		</header><!-- .archive-header -->

		<?php /* The loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>

		<?php estetico_paging_nav(); ?>

	<?php else : ?>
		<?php get_template_part( 'content', 'none' ); ?>
	<?php endif; ?>

</div><!-- .main-content -->

<?php if($sidebar_position_mobile == 'after') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

<?php get_footer(); ?>