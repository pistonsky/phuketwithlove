<?php

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                     													 :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'M' is statute miles                                   :*/
/*::                  'K' is kilometers (default)                            :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::  are available at http://www.geodatasource.com                          :*/
/*::                                                                         :*/
/*::  For enquiries, please contact sales@geodatasource.com                  :*/
/*::                                                                         :*/
/*::  Official Web site: http://www.geodatasource.com                        :*/
/*::                                                                         :*/
/*::         GeoDataSource.com (C) All Rights Reserved 2014		   		     :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
function estetico_distance($lat1, $lon1, $lat2, $lon2, $unit = null) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;

  // Use Theme option of measuring units if no specefic unit is requested
  if($unit == null) {

  	$default_unit = estetico_get_setting('measuring_units');

  	if($default_unit == 'metric') {
  		$unit = 'K';
  	} else if($default_unit == 'us') {
  		$unit = 'M';
  	}
  }

  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
    return ($miles * 0.8684);
  } else {
  	return $miles;
  }
}

/**
 *
 */
function estetico_get_client_ip() {
     $ipaddress = '';
     if (getenv('HTTP_CLIENT_IP'))
         $ipaddress = getenv('HTTP_CLIENT_IP');
     else if(getenv('HTTP_X_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
     else if(getenv('HTTP_X_FORWARDED'))
         $ipaddress = getenv('HTTP_X_FORWARDED');
     else if(getenv('HTTP_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_FORWARDED_FOR');
     else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
     else if(getenv('REMOTE_ADDR'))
         $ipaddress = getenv('REMOTE_ADDR');
     else
         $ipaddress = 'UNKNOWN';

     return $ipaddress; 
}

/**
 * @param $address string Free text address to look for
 * @return array of well structured data from Google API or false on failure
 */
function estetico_get_address_data_from_google($address) {

	$address = rawurlencode($address);

	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "&sensor=true";
	$args = array();

	$response = wp_remote_get($url, $args);

	if ( is_wp_error( $response ) ) {
   		return false;
   	}

   	$data = json_decode( $response['body'] );

   	// Address was not found
   	if($data->status == 'ZERO_RESULTS') {
   		return false;
   	}

   	return $data;
}

/**
 * Use Google Maps API to find location lat/lng
 *
 * @param $address string - An address to look for
 * @param $cache_result boolean - Cache results for next call
 */
function estetico_get_latlng_by_address($address, $cache_result = false) {

	static $latlng = array();

	$address_md5 = md5($address);

	if(isset($latlng[$address_md5])) {

		return $latlng[$address_md5];
	}

	$data = estetico_get_address_data_from_google($address);

	if($data === false) {
		return false;
	}

	$return = $data->results[0]->geometry->location;

	if($cache_result) {

		$latlng[$address_md5] = $return;
	}

	return $return;
}

/**
 * Generate an absolute link to various Social media websites
 * 
 * @param $id string - The social media ID
 * @param $vendor string - For which social media to generate the link
 *
 * @return string An absolute link
 */
function estetico_social_media_link($id = null, $vendor = null) {

	if($id == null || empty($id)) {
		return '#';
	}

	if($vendor == null || empty($vendor)) {
		return '#';
	}

	$output = "";

	switch($vendor) {

		case 'pinterest':
			$output = 'https://www.youtube.com/' . $id . '/';
		break;

		case 'youtube':
			$output = 'https://www.youtube.com/user/' . $id;
		break;

		case 'google_plus':
			$output = 'https://plus.google.com/' . $id;
		break;

		case 'twitter':
			$output = 'https://twitter.com/' . $id;
		break;

		case 'facebook':
			$output = 'https://facebook.com/' . $id;
		break;

		case 'linkedin':
			$output = $id;
		break;
	}

	return $output;
}

/**
 * Load different components
 */
function estetico_load_component( $component_name ) {

	$args = func_get_args();

	if(isset($args[1]) && is_array($args[1])) {
		extract($args[1]);
	}

	$component_path = COMPONENTS_PATH . DIRECTORY_SEPARATOR . $component_name . '.php';

	if( file_exists( $component_path) ) {

		include $component_path;
	} else {
		
		echo 'Cannot find a component with path: <strong>' . $component_path . '</strong>.';
	}
}

/**
 * Converts a number to a specific price format based on configurations made by administrator.
 *
 * @param $number number Number to be converted
 *
 * @return string Well formated price
 */
function estetico_price_format( $number ) {

	$currency					= estetico_get_setting( 'currency' );
	$decimal_point				= estetico_get_setting( 'decimal_point' );
	$thousands_separator		= estetico_get_setting( 'thousands_separator' );
	$currency_position			= estetico_get_setting( 'currency_position' );
	$currency_number_separator	= estetico_get_setting( 'currency_number_separator' );

	$currency_number_separator	= $currency_number_separator == 's' ? ' ' : $currency_number_separator;
	$decimal_point				= $decimal_point == 's' ? ' ' : $decimal_point;
	$thousands_separator		= $thousands_separator == 's' ? ' ' : $thousands_separator;

	$decimals = 2;

	if( estetico_get_setting( 'show_zeros_natural_numbers' ) == 'no' && (float)$number == (int)$number ) {

		$decimals = 0;
	}

	if($number == "") {
		
		$number = 0;
	}

	$number = (float)$number;

	$number_format = number_format( $number, $decimals, $decimal_point, $thousands_separator );

	if( $currency_position == 'front' ) {

		$number_format = $currency . $currency_number_separator . $number_format;
	} else {

		$number_format = $number_format . $currency_number_separator . $currency;
	}

	return $number_format;
}

/**
 * Looks for a specific setting and returns if exists. This is a simple wrapper but will allow easy change of the settings provider if necessary
 *
 * @param $setting_name string Setting name to look for
 *
 * @return mixed Setting value if exists or NULL if setting is not found
 */
function estetico_get_setting( $setting_name, $default = null ) {

	global $smof_data, $of_options;

	if( ! empty( $smof_data[ $setting_name ] ) ) {

		$return = estetico_extend_setting( $smof_data[ $setting_name ] );

		if("" == $return || $default != null) {
			return $default;
		}

		return $return;
	}

	if( ! isset( $of_options ) ) {
		return $default;
	}

	foreach( $of_options as $option ) {

		if( isset( $option['id'] ) && ! empty( $option['std'] ) ) {
			
			if( $option['id'] == $setting_name ) {

				$return = estetico_extend_setting( $option['std'] );

				if( "" == $return || $default != null) {
					return $default;
				}

				return $return;
			}
		}
	}

	return $default;
}

/**
 * Some settings might have additional parameters/variables within them which needs special processing.
 * 
 * @return setting value with replaced matched variables
 */
function estetico_extend_setting( $value ) {

	if( ! is_string($value)) {
		return $value;
	}

	// Try to find bloginfo replaces
	preg_match('/%bloginfo_([^%]+)%/', $value, $matches);

	if( ! empty( $matches ) ) {

		$wp_bloginfo_value = get_bloginfo($matches[1]);
		
		if( $wp_bloginfo_value != null ) {

			$value = str_replace($matches[0], $wp_bloginfo_value, $value);
		}
	}

	return $value;
}

/**
 * @return 
 */
function estetico_get_of_option_name( $id, $key ) {
	global $of_options;

	foreach( $of_options as $option ) {

		if(isset($option['id']) && $option['id'] == $id ) {

			return $option['options'][$key];
		}
	}
}

/**
 * @param $group
 * @param $name
 * @param $key
 * @return mixed
 */
function estetico_get_custom_metabox_field_option_value( $group, $name, $key ) {

	$meta_boxes = estetico_get_custom_meta_boxes();

	foreach( $meta_boxes[$group] as $box ) {

		if( $box['id'] == $name ) {

			$options = $box['callback_args']['options'];

			return $options[$key];
		}
	}

	return null;
}

function estetico_get_custom_metabox_field_title( $group, $name ) {

	$meta_boxes = estetico_get_custom_meta_boxes();

	foreach( $meta_boxes[$group] as $box ) {

		if( $box['id'] == $name ) {

			return $box['title'];
		}
	}
}

/**
 * @param $unit_name The name of the unit needed
 * @param $format "full" or "short"
 * 
 * @return The name of the unit in the correct system (us or metric)
 */
function estetico_get_unit($unit_name, $format = 'full') {

	$unit_base = estetico_get_setting('measuring_units');

	if($unit_base == '') {
		$unit_base = 'metric';
	}

	$units = array(
		'area' => array(
			'us' 		=> array('full' => 'Square feet', 'short' => 'Sq. ft.'),
			'metric' 	=> array('full' => 'Square meters', 'short' => 'Sq. met.')
		)
	);

	return isset($units[$unit_name][$unit_base][$format]) ? $units[$unit_name][$unit_base][$format] : null; 
}

/**
 * Get the current URL and returns it. If @params are passed on the function will look for their key -> value pair and replace the old values with the new ones.
 *
 * @param $preserve_args array An array of key-value pairs to replace old with new ones
 *
 * @return string Same URL or new one
 */
function estetico_preserve_url( $params ) {
	
	if(isset($_SERVER['REQUEST_SCHEME'])) {
        $scheme = $_SERVER['REQUEST_SCHEME'];
    } elseif (isset($_SERVER['SERVER_PROTOCOL'])) {
        $scheme = strtolower($_SERVER['SERVER_PROTOCOL']);
        $scheme = substr($scheme, 0, strpos($scheme, '/'));
        $scheme .= (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') ? 's' : '';
    }

	// Build the current url
	$url = $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	foreach( $params as $key => $value ) {

		preg_match( '/([?&])' . $key . '=([^&])*/', $url, $matches );

		if( isset( $matches[0] ) ) {

			$url = str_replace( $matches[0], $matches[1] . $key . '=' . $value, $url );
		} else {
			
			$url .= ( strpos( $url, '?' ) <= 0 ? '?' : '&amp;' ) . $key . '=' . $value;
		}
	}

	return $url;
}