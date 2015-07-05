<?php
$_options = array('show_legend' => true, 'show_places' => true, 'show_fullscreen' => true);

if(isset($options)) {
	$_options = array_merge($_options, $options);
}

?>
<div id="properties-map-pack-<?php echo $properties_map_index ?>" class="properties-map-pack" data-index="<?php echo $properties_map_index ?>">
	<?php if($_options['show_places']) : ?>
	<div class="properties-map-places-control box move-me">
		<span class="label"><?php _e('Show local:', THEME_NAME) ?></span>
		<ul>
			<li><input type="checkbox" class="trigger-local-places" data-type="hospital"><label><?php _e('Hospitals', THEME_NAME) ?></label></li>
			<li><input type="checkbox" class="trigger-local-places" data-type="school"><label><?php _e('Schools', THEME_NAME) ?></label></li>
			<li><input type="checkbox" class="trigger-local-places" data-type="sport"><label><?php _e('Sport activity', THEME_NAME) ?></label></li>
			<li><input type="checkbox" class="trigger-local-places" data-type="transit"><label><?php _e('Public transport', THEME_NAME) ?></label></li>
			<li><input type="checkbox" class="trigger-local-places" data-type="store"><label><?php _e('Stores', THEME_NAME) ?></label></li>
			<li><input type="checkbox" class="trigger-local-places" data-type="worship"><label><?php _e('Places of worship', THEME_NAME) ?></label></li>
			<li><input type="checkbox" class="trigger-local-places" data-type="service"><label><?php _e('Public services', THEME_NAME) ?></label></li>
			<li><input type="checkbox" class="trigger-local-places" data-type="food"><label><?php _e('Eat and drink', THEME_NAME) ?></label></li>
			<li><input type="checkbox" class="trigger-local-places" data-type="entertainment"><label><?php _e('Entertainment', THEME_NAME) ?></label></li>
		</ul>
	</div>
	<?php endif ?>

	<div class="box map move-me">
		<div id="properties-map-<?php echo $properties_map_index ?>" class="properties-map-instance use-box-sizing-content-box"></div>
	</div>

	<?php if($_options['show_legend']) : ?>
	<div class="properties-map-legend">
		<ul>
			<li><img src="<?php bloginfo( 'template_url' ) ?>/assets/core/img/marker.png" alt=""><?php echo __('Property for sale', THEME_NAME ) ?></li>
			<li><img src="<?php bloginfo( 'template_url' ) ?>/assets/core/img/marker_rent.png" alt=""><?php echo __('Property for rent', THEME_NAME ) ?></li>
		</ul>
	</div>
	<?php endif ?>
	<?php if($_options['show_fullscreen']) : ?>
	<a href="#" class="trigger-view-full-screen"><?php _e('View full screen map', THEME_NAME) ?></a>
	<?php endif ?>
</div>