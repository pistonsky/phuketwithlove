<?php

$propMgr = new PropertiesManager();
$properties = $propMgr->getProperties(null, null, 1000000);
$properties_map_index = 1;

?>

<div class="train-railing separator">
	<div class="railing"></div>
	<h3 class="train"><?php echo $title ?></h3>
	<div class="railing"></div>
</div>

<?php estetico_load_component('common/properties_view_map', array(
	'list_style'			=> 'map',
	'properties' 			=> $properties, 
	'properties_map_index' 	=> $properties_map_index, 
	'options' 				=> array('show_legend' => false, 'show_places' => false, 'show_fullscreen' => false
	)
)) ?>