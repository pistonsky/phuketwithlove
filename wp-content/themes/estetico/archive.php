<?php

get_header(); 

?>

<?php $sidebar_position_mobile = estetico_get_setting('sidebar_position_mobile'); ?>

<?php if($sidebar_position_mobile == 'before') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

	<div class="main-content">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php
					if ( is_day() ) :
						printf( __( 'Daily Archives: %s', THEME_NAME ), get_the_date() );
					elseif ( is_month() ) :
						printf( __( 'Monthly Archives: %s', THEME_NAME ), get_the_date( _x( 'F Y', 'monthly archives date format', THEME_NAME ) ) );
					elseif ( is_year() ) :
						printf( __( 'Yearly Archives: %s', THEME_NAME ), get_the_date( _x( 'Y', 'yearly archives date format', THEME_NAME ) ) );
					else :
						_e( 'Archives', THEME_NAME );
					endif;
				?></h1>
			</header><!-- .archive-header -->

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

	</div><!-- .main-content -->

<?php if($sidebar_position_mobile == 'after') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

<?php get_footer(); ?>