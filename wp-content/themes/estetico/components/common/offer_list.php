<li class="offer">
	<?php if($property_status != '') : ?>
        <div class="ribbon ribbon-<?php echo $property_status ?>"></div>
    <?php endif ?>
	<div class="left">
		<div class="image">
			<a class="hover-image" href="<?php echo $link ?>">
				<span class="roll link" style="opacity: 0;"></span>
				<?php
				if($thumbnail == null) {
					$thumbnail = get_bloginfo( 'template_url' ) . "/assets/core/img/default.png";
				}
				?>
				<img src="<?php echo $thumbnail ?>" alt="">		
			</a>
			<div class="prop-info price">
				<div class="highlight">
					<span class="label"><?php echo $property_type ?></span>
					<span class="value"><?php echo $price ?></span>
				</div>
			</div>
			<?php if( $show_featured_flag && $property->isFeatured()) : ?>
				<div class="featured-flag" title="<?php echo __('Featured', THEME_NAME) ?>"></div>
			<?php endif ?>
		</div>
	</div>
	<div class="right">
		<address><?php echo $property->getTitle() ?></address>
		<div class="prop-info horizontal">
			
			<?php if( $bedrooms ) : ?>
			<span class="label"><?php echo __('Bedrooms', THEME_NAME) ?>:</span> 
			<span class="value"><?php echo $bedrooms ?></span>
			<?php endif ?>
			
			<?php if( $bathrooms ) : ?>
			<span class="label"><?php echo __('Bathrooms', THEME_NAME) ?>:</span> 
			<span class="value"><?php echo $bathrooms ?></span>
			<?php endif ?>

			<?php if( $sq_feet ) : ?>
			<span class="label"><?php echo __('Sq Feet', THEME_NAME) ?>:</span> 
			<span class="value"><?php echo $sq_feet ?></span>
			<?php endif ?>
		</div>
	</div>
	<div class="clearfix"></div>
</li>