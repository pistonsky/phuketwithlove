<ul class="<?php echo $list_class ?>">

	<?php
	
	$counter = 0;
	foreach( $properties as $property ) :

		$counter++;

		// Create short variables
		extract($property->vars());

		$show_featured_flag = estetico_get_setting('mark_featured_items') == 'yes';
		?>

		<?php if( $schemaorg_enabled ) : ?>
			<?php echo $property->getSchemaOrgMetaTags() ?>
		<?php endif ?>
		
		<?php if( $list_style == 'list' || $list_style == 'list_map' ) : ?>

			<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'offer_list.php' ?>

		<?php else: ?>

			<li>
				<?php include COMPONENTS_PATH . DIRECTORY_SEPARATOR . 'common'. DIRECTORY_SEPARATOR . 'offer_grid.php' ?>
			</li>
			
			<?php if($counter % 3 == 0) : ?>
			<div class="clearfix"></div>
			<?php endif ?>

		<?php endif ?>
	
	<?php endforeach; ?>
</ul>