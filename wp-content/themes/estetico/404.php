<?php

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content page-404" role="main">

			<header class="page-header">
				<h1 class="page-title"><?php _e( '404 page', THEME_NAME ); ?></h1>
			</header>

			<div class="page-wrapper">
				<div class="page-content">
					<p><?php echo nl2br( estetico_get_setting('page_not_found_text') ) ?></p>
					<p>
					<?php
					$jump_to_pages = estetico_get_setting('page_not_found_jump_to_links');
					if(!$jump_to_pages) {
						$jump_to_pages = array();
					}
					$counter = 1;
					foreach($jump_to_pages as $page_id) :
						$page = get_post($page_id); if($page == null) {continue;} ?>
					<a href="<?php echo get_permalink($page->ID); ?>"><?php echo $page->post_title ?></a><?php if($counter++ < count($jump_to_pages)) : ?> | <?php endif ?>
					<?php endforeach; ?>
				</p>
				</div><!-- .page-content -->
			</div><!-- .page-wrapper -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>