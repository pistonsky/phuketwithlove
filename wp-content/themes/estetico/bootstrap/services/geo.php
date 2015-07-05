<?php

require_once "./../../../../../wp-load.php";

if( isset( $_GET['ref'] ) ) {

	switch($_GET['ref']) {
		// Format data to be readable by jQuery UI 
		case 'filter':

			$google_data = estetico_get_address_data_from_google($_GET['term']);

			$data = array();

			foreach($google_data->results as $result) {

				$address_components = array();

				foreach( $result->address_components as $component ) {
					$address_components[] = $component->long_name;
				}

				$data[] = array(
					'id' => join(', ', $address_components),
					'value' => join(', ', $address_components),
					'label' => join(', ', $address_components),
					'geometry' => $result->geometry
				);
			}
			
		break;
	}
} else {

	
	$data = estetico_get_address_data_from_google($_GET['address']);

}

header( "Content-Type: application/json; encoding=utf-8" );

echo json_encode($data);

exit;

?>