<?php

$main_properties_page = estetico_get_properties_page_id();
$action = estetico_get_properties_page_url($main_properties_page);

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

<div class="box general quick-search-box">
	<h5 class="title"><?php echo __( 'Search for properties', THEME_NAME ) ?></h5>

	<?php echo $description ?>

	<form method="get" action="<?php echo estetico_get_properties_page_url() ?>" class="">

		<?php foreach($quick_search_items as $key => $value): ?>
			<?php if($value == 1) : ?>
			<div class="field">
				<?php echo estetico_get_quick_search_item($key) ?>
			</div>
			<?php endif ?>
		<?php endforeach ?>
		
		<div class="field">
			<input class="button full th-brown" value="<?php echo __( 'Search', THEME_NAME ) ?>" name="submit" type="submit">
		</div>
	</form>
</div>