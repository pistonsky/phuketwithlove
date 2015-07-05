<div class="offer">
	
	<address><p><?php echo $property->getTitle() ?></p></address>
    
    <?php if($property_status != '') : ?>
		<div class="ribbon ribbon-<?php echo $property_status ?>"></div>
    <?php endif ?>
	
	<a href="<?php echo $property->getLink() ?>" class="hover-image">
		<span class="roll link"></span>
		<?php $post_thumbnail = get_the_post_thumbnail( $property->getId(), 'properties-carousel-thumbnail', array('class' => 'imgborder') ); if( ! empty($post_thumbnail ) ) : ?>
			<?php echo $post_thumbnail ?>
		<?php else: ?>
			<img src="<?php echo bloginfo( 'template_url' ) ?>/assets/core/img/default.png">
		<?php endif ?>
		<?php if(isset($show_featured_flag) && $show_featured_flag && $property->isFeatured()) : ?>
			<div class="featured-flag" title="<?php echo __('Featured', THEME_NAME) ?>"></div>
		<?php endif ?>
	</a>



	<ul class="prop-info">
		<li class="highlight">
			<span class="label"><?php echo $property_type ?></span>
			<?php if($for_sale_rent != 'neither') : ?>
			<span class="value"><?php echo $price ?></span>
			<?php endif ?>
		</li>
        
		<?php if( $bedrooms ) : ?>
		<li>
			<span class="label"><?php echo __('Bedrooms', THEME_NAME) ?>:</span>
			<span class="value"><?php echo $bedrooms ?></span>
		</li>
		<?php endif ?>

		<?php if( $bathrooms ) : ?>
		<li>
			<span class="label"><?php echo __('Bathrooms', THEME_NAME) ?>:</span>
			<span class="value"><?php echo $bathrooms ?></span>
		</li>
		<?php endif ?>

		<?php if( $sq_feet ) : ?>
		<li>
			<span class="label"><?php echo __('Sq Feet', THEME_NAME) ?>:</span>
			<span class="value"><?php echo $sq_feet ?></span>
		</li>
		<?php endif ?>
		<li class="last">
			<a href="<?php echo $property->getLink() ?>" class="button th-brown view-details"><?php echo __('View details', THEME_NAME) ?></a>
		</li>
	</ul>

</div>