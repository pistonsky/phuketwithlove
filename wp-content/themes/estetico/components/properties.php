<?php

$properties_per_page = (int)estetico_get_setting( 'properties_per_page' );
$start_page 		= isset($_GET['start_page']) ? (int) $_GET['start_page'] : 1;

$sort_by = ! empty( $_GET['sort_by'] ) ? $_GET['sort_by'] : '';

$list_style = ! empty( $_GET['list_style'] ) ? $_GET['list_style'] : estetico_get_setting('properties_default_listing_type');

// Do the properties query
$properties = PropertiesManager::getFiltered($_GET);
$found_posts = PropertiesManager::getLastQueryFoundItemsCount();

?>
<div class="main-content">

	<div class="filterbar">
		<div class="listing-view-change">
			<a href="<?php echo estetico_preserve_url( array( 'list_style' => 'grid' ) ) ?>" class="grid<?php if( $list_style != 'grid' ) : ?> active<?php endif ?>"><?php echo __( 'Grid', THEME_NAME ) ?></a>
			<a href="<?php echo estetico_preserve_url( array( 'list_style' => 'list' ) ) ?>" class="list<?php if( $list_style != 'list' ) : ?> active<?php endif ?>"><?php echo __( 'List', THEME_NAME ) ?></a>
			<a href="<?php echo estetico_preserve_url( array( 'list_style' => 'map' ) ) ?>" class="map<?php if( $list_style != 'map' ) : ?> active<?php endif ?>"><?php echo __( 'Map', THEME_NAME ) ?></a>
		</div>
		<?php if($list_style != 'map') : ?>
		<div class="sortby">
			<select class="properties-sort-by">
				<option value=""><?php echo __( 'Sort by', THEME_NAME ) ?></option>
				<option value="<?php echo estetico_preserve_url( array( 'sort_by' => 'price_low_to_high' ) ) ?>"<?php if( $sort_by == 'price_low_to_high' ) : ?> selected="selected"<?php endif ?>><?php echo __( 'Price (low to high)', THEME_NAME ) ?></option>
				<option value="<?php echo estetico_preserve_url( array( 'sort_by' => 'price_high_to_low' ) ) ?>"<?php if( $sort_by == 'price_high_to_low' ) : ?> selected="selected"<?php endif ?>><?php echo __( 'Price (high to low)', THEME_NAME ) ?></option>
				<option value="<?php echo estetico_preserve_url( array( 'sort_by' => 'view_count_low_to_high' ) ) ?>"<?php if( $sort_by == 'view_count_low_to_high' ) : ?> selected="selected"<?php endif ?>><?php echo __( 'View count (low to high)', THEME_NAME ) ?></option>
				<option value="<?php echo estetico_preserve_url( array( 'sort_by' => 'view_count_high_to_low' ) ) ?>"<?php if( $sort_by == 'view_count_high_to_low' ) : ?> selected="selected"<?php endif ?>><?php echo __( 'View count (high to low)', THEME_NAME ) ?></option>
				<option value="<?php echo estetico_preserve_url( array( 'sort_by' => 'date_low_to_high' ) ) ?>"<?php if( $sort_by == 'date_low_to_high' ) : ?> selected="selected"<?php endif ?>><?php echo __( 'Date added (low to high)', THEME_NAME ) ?></option>
				<option value="<?php echo estetico_preserve_url( array( 'sort_by' => 'date_high_to_low' ) ) ?>"<?php if( $sort_by == 'date_high_to_low' ) : ?> selected="selected"<?php endif ?>><?php echo __( 'Date added (high to low)', THEME_NAME ) ?></option>
			</select>
		</div>
		<?php endif ?>
	</div>

	<?php require "common" . DIRECTORY_SEPARATOR . "properties.php"; ?>

</div>