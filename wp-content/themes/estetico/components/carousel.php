<?php

$propMgr = new PropertiesManager();
$properties = $propMgr->getProperties('featured');

if(empty($properties)) {
	return;
}

$div_id = 'properties-carousel-' . rand();

?>
<div class="train-railing separator m10">
	<div class="railing"></div>
	<h3 class="train"><?php echo $title ?></h3>
	<div class="railing"></div>
</div>

<div class="offers slide use-box-sizing-content-box" id="<?php echo $div_id;?>">
	<ul class="items grid">

	<?php foreach( $properties as $property ): 

		extract($property->vars());

		?>

		<li>
			<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common'. DIRECTORY_SEPARATOR . 'offer_grid.php' ?>
		</li>

<?php endforeach ?>
</ul>
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