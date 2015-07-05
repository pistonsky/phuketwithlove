<?php
$schemaorg_enabled = estetico_get_setting( 'schemaorg_enabled' );

if(!isset($list_style)) {
	$list_style = ! empty( $_GET['list_style'] ) ? $_GET['list_style'] : estetico_get_setting('properties_default_listing_type');
}

if( $list_style == "list" || $list_style == 'list_map' ) {
	$list_class = "items list";
} else {
	$list_class = "items grid grid-fluid-3 automatch";
}

$div_id = 'properties-carousel-' . rand();
?>

<div class="offers">
	
	<?php

	if($list_style == 'carousel') : ?>

	<div class="offers slide use-box-sizing-content-box" id="<?php echo $div_id;?>">
		<ul class="items">

		<?php foreach( $properties as $property ): 

			extract($property->vars());

			?>

			<li>
				<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common'. DIRECTORY_SEPARATOR . 'offer_grid.php' ?>
			</li>

	<?php endforeach ?>
	</ul>
	</div>

	<?php elseif ($list_style == 'grid_map') : ?>

	<div class="forced-tabs">
		<ul>
			<li><a href="#shortcode-properties-view-grid-1"><?php _e('Grid', THEME_NAME) ?></a></li>
			<li><a href="#shortcode-properties-view-map-1"><?php _e('Map', THEME_NAME) ?></a></li>
		</ul>
		<div class="box" id="shortcode-properties-view-grid-1">
			<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'properties_view_list_grid.php' ?>
			<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'paging.php' ?>
		</div>
		<div class="box" id="shortcode-properties-view-map-1">
			<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'properties_view_map.php' ?>
		</div>
	</div>

	<?php elseif ($list_style == 'list_map') : ?>

	<div class="forced-tabs">
		<ul>
			<li><a href="#shortcode-properties-view-list-1"><?php _e('List', THEME_NAME) ?></a></li>
			<li><a href="#shortcode-properties-view-map-1"><?php _e('Map', THEME_NAME) ?></a></li>
		</ul>
		<div class="box" id="shortcode-properties-view-list-1">
			<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'properties_view_list_grid.php' ?>
			<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'paging.php' ?>
		</div>
		<div class="box" id="shortcode-properties-view-map-1">
			<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'properties_view_map.php' ?>
		</div>
	</div>

	<?php elseif ($list_style == 'map') : ?>
		
		<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'properties_view_map.php' ?>

	<?php else : ?>

		<?php

		if( ! empty( $properties ) ) : ?>

			<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'properties_view_list_grid.php' ?>
			<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'paging.php' ?>
		
		<?php else : ?>

			<p><?php echo estetico_get_setting( 'no_properties_found_text' ) ?></p>

		<?php endif; ?>
		
	<?php endif ?>
</div>
<script type="text/javascript">
(function($) {
    try {		
		var resizeTimeout = null;
        var slider1;
        var autoSlide = '<?php echo $auto_slide; ?>' == 'yes' ? true : false;
        var infLoop = '<?php echo $infinite_loop; ?>' == 'yes' ? true : false;
        var containerId = '<?php echo $div_id ?>';

		$(window).resize(function() {
			
			clearTimeout(resizeTimeout);
			resizeTimeout = setTimeout(function() {

				var isSmartphone = matchMedia('screen and (max-width: 480px)').matches;
				var isTablet = matchMedia('screen and (max-width: 979px)').matches;
				var isDesktop = !(isSmartphone && isTablet);

				var slider1options = {};
				
				// Destory offer slider after resize and recreate it again.
				if(slider1 && slider1.length > 0) {
					slider1.destroySlider();
					slider1 = null;
				}

				if(isSmartphone || isTablet) {

					$('.slider .slides').css('transform', 'none').find('img').width('100%');
				}

				// Smartphones
				if(isSmartphone) {

					slider1options = {
						minSlides: 1,
						maxSlides: 1,
						slideMargin: 0,
                        auto: autoSlide,
                        infiniteLoop: infLoop
					};

				// Tablets
				} else if(isTablet) {
					
					slider1options = {
						minSlides: 1,
						maxSlides: 2,
						slideMargin: 18,
						slideWidth : $(window).width()/2-2, // 2 x 10 side margins - 6 slide margin
                        auto: autoSlide,
                        infiniteLoop: infLoop
					};

				// Desktop
				} else {
					
					slider1options = {
						minSlides: 4,
						maxSlides: 4,
						slideWidth : 225,
						slideMargin: 18,
                        auto: autoSlide,
                        infiniteLoop: infLoop
					};
				}

               slider1 = $('#' + containerId).find('.items').bxSlider(slider1options);
				
			}, 200);

		}).trigger('resize');
		
	} catch (e) {}

})(jQuery);
</script>