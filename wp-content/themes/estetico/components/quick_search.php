<?php

$main_properties_page = estetico_get_properties_page_id();
$action = estetico_get_properties_page_url_wpml($main_properties_page);

$default_quick_search_items = array('type' => 1, 'bedrooms' => 1, 'bathrooms' => 1, 'min_price' => 1, 'max_price' => 1);
$quick_search_items = estetico_get_setting( 'quick_search_items' );

if( ! is_array($quick_search_items) || $quick_search_items == '' ) {
	$quick_search_items = $default_quick_search_items;
}

$at_least_one = false;
$quick_search_items_selected = 0;
foreach($quick_search_items as $key => $value) {
	if($value == 1) {
		$at_least_one = true;
		$quick_search_items_selected++;
	}
}

if( ! $at_least_one ) {
	$quick_search_items = $default_quick_search_items;
	$quick_search_items_selected = count($default_quick_search_items);
}

?>
<div class="quick-search">
	<form method="get" action="<?php echo $action ?>" role="search">

		<div class="clearfix"></div>
		<div class="train-railing tmobile">
			<div class="railing"></div>
			<h3 class="train"><?php echo estetico_get_setting('quick_search_heading') ?></h3>
			<div class="railing"></div>
		</div>

		<div class="table">
			<div class="row">
				<div class="cell cell-1">

		<ul class="filters filters-<?php echo $quick_search_items_selected ?>">
			<?php foreach($quick_search_items as $key => $value): ?>
				<?php if($value == 1) : ?>
				<li>
					<?php echo estetico_get_quick_search_item($key) ?>
				</li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
	</div>
	<div class="cell cell-2">
		<div class="submit-button">
			<input type="submit" value="<?php echo __( 'Search', THEME_NAME ) ?>" class="submit button th-brown" title="<?php if(current_user_can('manage_options') && $main_properties_page == false): ?><?php echo __('Admin only: Check Theme settings -> Properties for how to set up the properties page', THEME_NAME) ?><?php endif ?>">
			<?php if($main_properties_page != false) : ?>
			<input type="hidden" name="page_id" value="<?php echo $main_properties_page ?>">
			<?php endif ?>
		</div>
	</div>
</div>
</div>

		<div class="clearfix"></div>

	</form>

</div>
<div class="quick-search-shadow"></div>