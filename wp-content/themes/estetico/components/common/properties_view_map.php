<?php if(!empty($properties )) : ?>

	<?php
	$properties_map_index = Registry::get('properties_map_index');
	if($properties_map_index == null) {
		$properties_map_index = 0;
	}
	$properties_map_index++;
	Registry::set('properties_map_index', $properties_map_index);
	$options = isset($options) ? $options : array();
	?>

	<script>
	var propertiesList = [];
	<?php foreach($properties as $property) : ?>
	propertiesList.push({
		lat : '<?php echo $property->getLatitude() ?>',
		lng : '<?php echo $property->getLongitude() ?>',
		address : '<?php echo esc_html( $property->getAddress() ) ?>',
		url : '<?php echo $property->getLink() ?>',
		photo : '<?php echo $property->getImage(array(100, 100)) ?>',
		rent : <?php echo $property->getForSaleRent() == 'rent' ? 'true' : 'false' ?>,
		title : '<?php echo esc_html( $property->getTitle() ) ?>'
	});
	<?php endforeach ?>
	</script>

	<?php estetico_load_component('common/properties_view_map_pack', array('properties_map_index' => $properties_map_index, 'options' => $options)) ?>

	<?php if($list_style != 'list_map') : ?>
	<script>
	google.maps.event.addDomListener(window, 'load', function() {

		var propertiesMap = new PropertiesMap('properties-map-<?php echo $properties_map_index ?>');
		propertiesMap.add(propertiesList);
		propertiesMap.localInitialize();
	});
	</script>
	<?php endif ?>

<?php endif ?>