	<div class="clearfix"></div>
</div>
	<!-- /.container -->

</section>


<?php 
$enable_footer = estetico_get_setting('enable_footer');

if( $enable_footer == 'yes') : ?>

<footer id="footer">

	<div class="container grid grid-fluid-3">
		<div class="one-true">
			<?php 
			$footer_1_menu_object = null;
			$footer_2_menu_object = null;

			$nav_locations = get_nav_menu_locations(); 
			
			if(isset($nav_locations['footer_1'])) {
				$footer_1_menu_id = $nav_locations['footer_1']; 
				$footer_1_menu_object = wp_get_nav_menu_object($footer_1_menu_id); 
			}

			if(isset($nav_locations['footer_2'])) {
				$footer_2_menu_id = $nav_locations['footer_2']; 
				$footer_2_menu_object = wp_get_nav_menu_object($footer_2_menu_id);
			}
			?>

			<?php if($footer_1_menu_object || $footer_2_menu_object) : ?>
			<div class="col">

				<?php if($footer_1_menu_object !== false) : ?>
				<div class="links">
					<h6><?php echo $footer_1_menu_object->name ?></h6>
					<?php wp_nav_menu( array( 'theme_location' => 'footer_1', 'container_class' => '' ) ); ?>
				</div>
				<?php endif ?>
				
				<?php if($footer_2_menu_object !== false) : ?>
				<div class="links">
					<h6><?php echo $footer_2_menu_object->name ?></h6>
					<?php wp_nav_menu( array( 'theme_location' => 'footer_2', 'container_class' => 'links' ) ); ?>
				</div>
				<?php endif ?>
			</div>
			<?php endif ?>

			<?php if( estetico_get_setting('enable_footer_middle_section') == true ) : ?>
			<div class="col">
				<h6><?php echo estetico_get_setting('footer_middle_section_title') ?></h6>
				<p><?php echo estetico_get_setting('footer_middle_section_text') ?></p>
				<a href="<?php echo estetico_get_setting('footer_middle_section_read_more_link') ?>"><?php echo estetico_get_setting('footer_middle_section_read_more_text') ?></a>
			</div>
			<?php endif ?>

			<div class="col omega">

				<h6><?php echo estetico_get_setting('newsletter_title') ?></h6>
	            <p><?php echo estetico_get_setting('newsletter_description') ?></p>

				<ul class="social">
					<?php $url = estetico_social_media_link(estetico_get_setting('social_media_facebook'), 'facebook'); if($url != '#'): ?>
					<li><a href="<?php echo $url ?>" class="facebook" rel="external"></a></li>
					<?php endif ?>

					<?php $url = estetico_social_media_link(estetico_get_setting('social_media_google_plus'), 'google_plus'); if( $url != '#'): ?>
					<li><a href="<?php echo $url ?>" class="gplus" rel="external"></a></li>
					<?php endif ?>

					<?php $url = estetico_social_media_link(estetico_get_setting('social_media_youtube'), 'youtube'); if( $url != '#'): ?>
					<li><a href="<?php echo $url ?>" class="youtube" rel="external"></a></li>
					<?php endif ?>

					<?php $url = estetico_social_media_link(estetico_get_setting('social_media_pinterest'), 'pinterest'); if( $url != '#'): ?>
					<li><a href="<?php echo $url ?>" class="pinterest" rel="external"></a></li>
					<?php endif ?>

					<?php $url = estetico_social_media_link(estetico_get_setting('social_media_twitter'), 'twitter'); if( $url != '#'): ?>
					<li><a href="<?php echo $url ?>" class="twitter" rel="external"></a></li>
					<?php endif ?>

					<?php $url = estetico_social_media_link(estetico_get_setting('social_media_linkedin'), 'linkedin'); if( $url != '#'): ?>
					<li><a href="<?php echo $url ?>" class="linkedin" rel="external"></a></li>
					<?php endif ?>

					<?php $footer_hide_rss = estetico_get_setting('footer_hide_rss') == 'yes'; if($footer_hide_rss == false) : ?>
	 				<li><a href="<?php echo get_bloginfo('rss2_url') ?>" class="rss"></a></li>
	 				<?php endif ?>
				</ul>
			</div>
		</div>
		<?php $footer_free_html = estetico_get_setting('footer_free_html'); echo $footer_free_html ?>
		<?php 
		$copyright = estetico_get_setting('footer_copyright');
		if( ! empty( $copyright ) ) : ?>
			<div class="copyright"><?php echo $copyright ?></div>
		<?php endif ?>
	</div>
</footer>
<?php endif ?>

<?php wp_footer(); ?>

<script>
jQuery(function() {
	jQuery('#footer .container.grid').removeClass('grid-fluid-3').addClass('grid-fluid-' + jQuery('#footer .container .col').length);
});
</script>

</body>
</html>