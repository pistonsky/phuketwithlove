<?php

require_once "property.class.php";

class PropertiesManager {

	private static $last_query_found_items_count = 0;
	
	public function __construct() {

	}

	public static function getTypesFiltered($params) {

		// Do not use self
		unset($params['type']);

		$params['all'] = true;

		$properties = self::getFiltered($params);

		$all_types = self::getAllTypes();
		$nulled = false;

		// No parameters to refilter the types. Return them all.
		if(empty($params)) {
			return $all_types;
		}

		foreach($all_types as $key => $value) {
			$all_types[$key]->count = 0;
		}

		foreach($properties as $property) {

			$types = wp_get_post_terms($property->getId(), 'properties-types');

			foreach($all_types as $key => &$type) {

				foreach($types as $local_key => $local_type) {

					if( $type->slug == $local_type->slug ) {
						$type->count += 1;
						break;
					}
				}
			}
		}

		foreach($all_types as $key => $value) {
			if($all_types[$key]->count == 0) {
				unset($all_types[$key]);
			}
		}

		return $all_types;
	}

	public static function getFeaturesFiltered($params) {

		// Do not use self
		unset($params['feature']);

		$params['all'] = true;

		$properties = self::getFiltered($params);

		$all_features = self::getAllFeatures();
		$nulled = false;

		if(empty($all_features)) {
			return $all_features;
		}

		foreach($all_features as $key => $feature) {
			$all_features[$key]->count = 0;
		}

		foreach($properties as $property) {

			$features = wp_get_post_terms($property->getId(), 'properties-features');

			foreach($all_features as $key => &$feature) {

				foreach($features as $local_key => $local_feature) {

					if( $feature->slug == $local_feature->slug ) {
						$feature->count += 1;
					}
				}
			}
		}

		foreach($all_features as $key => $value) {
			if($all_features[$key]->count == 0) {
				unset($all_features[$key]);
			}
		}

		return $all_features;
	}

	public static function getAllTypes() {

		// Display all property types no matter if they have properties or not (a WPML issue)
		$args = array('hide_empty' => 0);

		$types = get_terms('properties-types', $args);

		return $types;
	}

	public static function getAllFeatures() {

		// Display all property features no matter if they have properties or not (a WPML issue)
		$args = array('hide_empty' => 0);

		$features = get_terms('properties-features', $args );

		return $features;
	}

	public static function getPropertiesBounds() {

	}

	/**
	 *
	 */
	public static function getSqFeetMax() {

		$args = array();
		$args['post_status'] 	= 'publish';
		$args['post_type'] 		= 'properties';
		$args['posts_per_page']	= PHP_INT_MAX;

		$curr_lang = estetico_surpress_wpml_query_before();

		$properties = new WP_Query( $args );

		estetico_surpress_wpml_query_after($curr_lang);

		$sq_ft = 0;

		foreach( $properties->posts as $property ) {

			$p = new Property();
			$p->setData($property);

			$_sq_ft = (int)$p->getSqFt(null, true);

			if($_sq_ft > $sq_ft) {
				$sq_ft = $_sq_ft;
			}
		}

		return $sq_ft;
	}

	/**
	 *
	 */
	public static function getPriceMax() {

		$args = array();
		$args['post_status'] 	= 'publish';
		$args['post_type'] 		= 'properties';
		$args['posts_per_page']	= PHP_INT_MAX;

		$curr_lang = estetico_surpress_wpml_query_before();

		$properties = new WP_Query( $args );

		estetico_surpress_wpml_query_after($curr_lang);

		$price = 0;

		foreach( $properties->posts as $property ) {

			$p = new Property();
			$p->setData($property);

			$_price = $p->getPrice(null, true);

			if($_price > $price) {
				$price = $_price;
			}
		}

		return $price;
	}

	/**
	 *
	 */
	public static function getPriceMin() {

		$args = array();
		$args['post_status'] 	= 'publish';
		$args['post_type'] 		= 'properties';
		$args['posts_per_page']	= PHP_INT_MAX;

		$curr_lang = estetico_surpress_wpml_query_before();

		$properties = new WP_Query( $args );

		estetico_surpress_wpml_query_after($curr_lang);

		// Set price to unbelievable high number. Although with current US economy this might be a problem at some point :)
		$price = 9999999999;

		foreach( $properties->posts as $property ) {

			$p = new Property();
			$p->setData($property);

			$_price = $p->getPrice(null, true);

			if($_price < $price) {
				$price = $_price;
			}
		}

		return $price;
	}

	public static function getYearBuiltMin() {

		$args = array();
		$args['post_status'] 	= 'publish';
		$args['post_type'] 		= 'properties';
		$args['posts_per_page'] = PHP_INT_MAX;

		$curr_lang = estetico_surpress_wpml_query_before();

		$properties = new WP_Query( $args );

		estetico_surpress_wpml_query_after($curr_lang);

		$value = 9999999999;

		foreach( $properties->posts as $property ) {

			$p = new Property();
			$p->setData($property);

			$year_built = (int)$p->getYearBuilt();

			if($year_built < $value) {
				$value = $year_built;
			}
		}

		return $value;
	}

	public static function getBedroomsMax() {


	}

	public function getPropertyTypes() {

		// Display all property types no matter if they have properties or not (a WPML issue)
		$args = array('hide_empty' => 0);

		$types = get_terms( 'properties-types', $args );

		return $types;
	}

	public static function getFiltered( $params = array(), $mesh = array() ) {

		extract($mesh);

		if(isset($params['posts_per_page'])) {
			$posts_per_page = (int)$params['posts_per_page'];
		} else {
			$posts_per_page 	= (int)estetico_get_setting( 'properties_per_page' );
		}
		$start_page 			= isset($_GET['start_page']) ? (int) $_GET['start_page'] : 1;
		$offset 				= ( $start_page - 1 ) * $posts_per_page;
		$list_style 			= ! empty( $_GET['list_style'] ) ? $_GET['list_style'] : estetico_get_setting('properties_default_listing_type');

		$meta_query = array();
		$tax_query = array();
		$tax_query['relation'] = 'AND';

		// Visual composer set this options
		if(isset($params['bathrooms_min']) && isset($params['bathrooms_max'])) {
			$params['bathrooms'] = $params['bathrooms_min'] . ',' . $params['bathrooms_max'];
		}

		if( ! empty( $params['bathrooms'] ) ) {

			$bathrooms = explode( ',', $params['bathrooms'] );

			if( isset( $bathrooms[1] ) ) {
				
				$bathrooms_min = (int)$bathrooms[0];
				$bathrooms_max = (int)$bathrooms[1];

				$meta_query[] = array( 'key' => USE_PREFIX . 'bathrooms', 'value' => array( $bathrooms_min, $bathrooms_max ), 'compare' => 'BETWEEN', 'type' => 'NUMERIC');
			} else {

				$meta_query[] = array( 'key' => USE_PREFIX . 'bathrooms', 'value' => $params['bathrooms'], 'compare' => '>=', 'type' => 'NUMERIC' );
			}
		}

		// Visual composer set this options
		if(isset($params['bedrooms_min']) && isset($params['bedrooms_max'])) {
			$params['bedrooms'] = $params['bedrooms_min'] . ',' . $params['bedrooms_max'];
		}

		if( ! empty( $params['bedrooms'] ) ) {

			$bedrooms = explode( ',', $params['bedrooms'] );

			if( isset( $bedrooms[1] ) ) {
				
				$bedrooms_min = (int)$bedrooms[0];
				$bedrooms_max = (int)$bedrooms[1];

				$meta_query[] = array( 'key' => USE_PREFIX . 'bedrooms', 'value' => array( $bedrooms_min, $bedrooms_max ), 'compare' => 'BETWEEN', 'type' => 'NUMERIC');
			} else {

				$meta_query[] = array( 'key' => USE_PREFIX . 'bedrooms', 'value' => $params['bedrooms'], 'compare' => '>=', 'type' => 'NUMERIC' );
			}
		}

		if( ! empty( $params['sq_feet'] ) ) {

			$sq_feet = explode( ',', $params['sq_feet'] );

			if( isset( $sq_feet[1] ) ) {
				
				$sq_feet_min = (int)$sq_feet[0];
				$sq_feet_max = (int)$sq_feet[1];

				$meta_query[] = array( 'key' => USE_PREFIX . 'sq_ft', 'value' => array( $sq_feet_min, $sq_feet_max ), 'compare' => 'BETWEEN', 'type' => 'NUMERIC');
			} else {

				$meta_query[] = array( 'key' => USE_PREFIX . 'sq_ft', 'value' => $params['sq_feet'], 'compare' => '>=', 'type' => 'NUMERIC' );
			}
		}

		if( ! empty( $params['type'] ) ) {

			$types = explode(',', $params['type']);

			// Versions before 1.1 used ID based filtering per type.
			if( count($types) == 1 && preg_match('/([0-9]+)/', $types[0]) ) {
				
				$tax_query[] = array('taxonomy' => 'properties-types', 'field' => 'id', 'terms' => $types[0]);
			} else {

				$terms_filter = array();

				$terms_args = array('hide_empty' => 0);
				$terms = get_terms('properties-types', $terms_args);
				
				foreach($types as $type) {

					foreach($terms as $term) {
						if($term->slug == $type) {
							$terms_filter[] = $term->term_id;
						}
					}
				}

				if( isset($terms_filter[0] ) ) {
					$tax_query[] = array(
						'taxonomy' 	=> 'properties-types',
						'field' 	=> 'term_taxonomy_id',
						'terms'	 	=> $terms_filter,
						'operator'	=> 'IN',
						'include_children' => false
					);
				}
			}
		}

		// To be removed and replaced with feature_1 only
		if( ! empty( $params['feature'] ) ) {

			$features = explode(',', $params['feature']);

			$terms_filter = array();
			
			$terms_args = array('hide_empty' => 0);
			$terms = get_terms('properties-features', $terms_args);

			foreach($features as $feature) {

				foreach($terms as $term) {
					if($term->slug == $feature) {
						$terms_filter[] = $term->term_id;
					}
				}
			}

			if( isset($terms_filter[0] ) ) {
				$tax_query[] = array(
					'taxonomy' 	=> 'properties-features',
					'field' 	=> 'term_taxonomy_id',
					'terms'	 	=> $terms_filter,
					'operator'	=> 'IN',
					'include_children' => false
				);
			}
		}

		if( ! empty( $params['pets_allowed'] ) ) {

			$meta_query[] = array( 'key' => USE_PREFIX . 'pets_allowed', 'value' => $params['pets_allowed'] == 'yes' ? 'on' : 'off', 'compare' => '==' );
		}

		$_for_sale_rent = 'sale';

		if(isset($params['for_sale_rent']) && $params['for_sale_rent'] == 'rent') {
			$_for_sale_rent = 'rent';
		}

		// Price and Min price + Max Price should not co-exists
		if( ! empty( $params['price'] ) ) {

			$price = explode(',', $params['price']);
			$price_min = (int)$price[0];
			$price_max = (int)$price[1];

			if($_for_sale_rent == 'sale') {
				$meta_query[] = array('key' => USE_PREFIX . 'price', 'value' => array($price_min, $price_max), 'compare' => 'BETWEEN', 'type' => 'NUMERIC');
			} else {
				$meta_query[] = array('key' => USE_PREFIX . 'price_rent_month', 'value' => array($price_min, $price_max), 'compare' => 'BETWEEN', 'type' => 'NUMERIC');
				$meta_query[] = array('key' => USE_PREFIX . 'price_rent_week', 'value' => array($price_min, $price_max), 'compare' => 'BETWEEN', 'type' => 'NUMERIC');
			}
		} else {

			if( ! empty( $params['min_price'] ) ) {

				if($_for_sale_rent == 'sale') {
					$meta_query[] = array( 'key' => USE_PREFIX . 'price', 'value' => $params['min_price'], 'compare' => '>=', 'type' => 'NUMERIC' );
				} else {
					$meta_query[] = array( 'key' => USE_PREFIX . 'price_rent_month', 'value' => $params['min_price'], 'compare' => '>=', 'type' => 'NUMERIC' );
					$meta_query[] = array( 'key' => USE_PREFIX . 'price_rent_week', 'value' => $params['min_price'], 'compare' => '>=', 'type' => 'NUMERIC' );
				}
			}

			if( ! empty( $params['max_price'] ) ) {

				if($_for_sale_rent == 'sale') {
					$meta_query[] = array( 'key' => USE_PREFIX . 'price', 'value' => $params['max_price'], 'compare' => '<=', 'type' => 'NUMERIC' );
				} else {
					$meta_query[] = array( 'key' => USE_PREFIX . 'price_rent_month', 'value' => $params['max_price'], 'compare' => '<=', 'type' => 'NUMERIC' );
					$meta_query[] = array( 'key' => USE_PREFIX . 'price_rent_week', 'value' => $params['max_price'], 'compare' => '<=', 'type' => 'NUMERIC' );
				}
			}
		}

		if( ! empty( $params['year_built'] ) ) {
			$meta_query[] = array('key' => USE_PREFIX . 'year_built', 'value' => explode(',', $params['year_built']), 'compare' => 'BETWEEN', 'type' => 'NUMERIC');
		}

		if( ! empty( $params['city'] ) ) {

			$meta_query[] = array('key' => USE_PREFIX . 'city', 'value' => $params['city']);
		}

		if( ! empty( $params['for_sale_rent'] ) ) {

			if( in_array( $params['for_sale_rent'], array('rent', 'sale') ) ) {
				$meta_query[] = array('key' => USE_PREFIX . 'for_sale_rent', 'value' => $params['for_sale_rent']);
			}
		}
        
        if( ! empty( $params['property_status'] ) ) {

			$meta_query[] = array('key' => USE_PREFIX . 'property_status', 'value' => $params['property_status']);
		}

		if( ! empty( $params['beds'] ) ) {

			$beds = (int)$params['beds'];

			$meta_query[] = array('key' => USE_PREFIX . 'beds', 'value' => $beds, 'compare' => '>=', 'type' => 'NUMERIC');
		}

		$custom = array();

		if( ! empty( $params['lat'] ) && ! empty( $params['lng'] ) ) {

			$lat = (float)$params['lat'];
			$lng = (float)$params['lng'];

			$custom['lat'] = $lat;
			$custom['lng'] = $lng;
		}

		if( ! empty( $params['distance'] ) ) {
			$distance = (int)$params['distance'];

			$custom['distance'] = $distance;
		}

		if( ! empty( $params['agent'] ) ) {

			$meta_query[] = array('key' => USE_PREFIX . 'agent', 'value' => "\"" . $params['agent'] . "\"", 'compare' => 'LIKE');
		}

		$args = array(
			'meta_query' 	=> $meta_query,
			'tax_query' 	=> $tax_query,
			'offset' 		=> $offset,
			'custom' 		=> $custom,
			'post_type' 	=> 'properties',
			'post_status' 	=> 'publish'
		);

		// When map is used no need to paginate the data
		if($list_style != 'map') {
			$args['posts_per_page'] = $posts_per_page;
		}

		if( ! empty( $params['keywords'] ) ) {

			$args['s'] = $params['keywords'];
		}

		if(isset($params['sort_by'])) {

			$orderby = 'meta_value_num';
			
			switch($params['sort_by']) {
				case 'price_high_to_low':
				case 'price_low_to_high':
					$args['meta_key'] 	= USE_PREFIX . 'price';
				break;

				case 'view_count_high_to_low':
				case 'view_count_low_to_high':
					$args['meta_key'] 	= USE_PREFIX . 'view_count';
				break;

				case 'date_high_to_low':
				case 'date_low_to_high':
					$orderby = 'date';
				break;

				default:
					$args['meta_key'] 	= USE_PREFIX . 'price';		
			}

			switch ($params['sort_by']) {

				case 'price_high_to_low':
				case 'view_count_high_to_low':
				case 'date_high_to_low':
					$args['order'] = 'DESC';
					break;

				default:
					$args['order'] = 'ASC';
			}
			
			$args['orderby'] 	= $orderby;
		}

		$args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;

		if(isset($params['all'])) {
			$args['posts_per_page'] = PHP_INT_MAX; // Well, lets hope nobody will have more than 1 000 000 properties to sell :)
		}
		
		return self::searchProperties( $args );
	}

	public static function searchProperties( $args = array() ) {

		$key = md5(serialize($args));

		if(!isset($args['ignore_sticky_posts'])) {
			$args['ignore_sticky_posts'] = true;
		}

		$_properties = wp_cache_get($key, THEME_NAME);
		$found_posts = 0;

		if( ! $_properties ) {

			$curr_lang = estetico_surpress_wpml_query_before();

			$properties = new WP_Query( $args );

			if(isset($args['debug'])) {
				var_dump($properties);
			}

			estetico_surpress_wpml_query_after($curr_lang);

			$found_posts = $properties->found_posts;

			$_properties = array();

			foreach( $properties->posts as $property ) {

				$p = new Property();
				$p->setData( $property );

				if( ! empty( $args['custom'] ) ) {

					if( ! empty( $args['custom']['lat'] ) && ! empty( $args['custom']['lng'] ) ) {

						$distance = estetico_distance($args['custom']['lat'], $args['custom']['lng'], (float)$p->getLatitude(), (float)$p->getLongitude());

						$desired_distance = estetico_get_setting('default_distance');
						if( isset($args['custom']['distance'])) {
							$desired_distance = $args['custom']['distance'];
						}

						if( $distance > $desired_distance) {
							$found_posts--;
							continue;
						}
					}
				}

				$_properties[] = $p;
			}

			wp_cache_set($key, $_properties, THEME_NAME);
		}

		self::$last_query_found_items_count = $found_posts;

		return $_properties;

	}

	public static function getLastQueryFoundItemsCount() {

		return self::$last_query_found_items_count;
	}

	public static function getProperties($type = 'newest', $order = 'default', $count = 10) {

		$args = array(
			'post_type' 		=> 'properties',
			'post_status' 		=> 'publish',
			'posts_per_page'	=> $count
		);

		if( $order == 'default' ) {
			$args['orderby'] = 'post_date';
			$args['order'] = 'DESC';
		} else if ( $order == 'random' ) {
			$args['orderby'] = 'rand';
		}

		// Prevent multiple instances of the same post if WPML is installed
		$args['suppress_filters'] = false;

		$meta_query = array();

		if($type == 'featured') {

			$meta_query[] = array('key' => USE_PREFIX . 'featured', 'value' => 'on');
		}

		$args['meta_query'] = $meta_query;

		$key = md5(serialize($args));

		$_properties = wp_cache_get($key, THEME_NAME);

		if( ! $_properties ) {

			$properties = get_posts( $args );
			$_properties = array();

			foreach( $properties as $property ) {

				$p = new Property();
				$p->setData( $property );

				$_properties[] = $p;
			}

			wp_cache_set($key, $_properties, THEME_NAME);
		}

		self::$last_query_found_items_count = count($_properties);

		return $_properties;
	}

	public function getNewest() {

		return $this->getProperties('newest');
	}

	/**
	 *
	 */
	public static function addToRecentlyViewed( $id ) {
		
		$cookie_name 		= USE_PREFIX . 'recently_viewed';
		$recently_viewed 	= isset( $_COOKIE[$cookie_name] ) ? $_COOKIE[$cookie_name] : '';

		$recently_viewed = explode(',', $recently_viewed);
		array_push($recently_viewed, $id);
		$recently_viewed = array_unique($recently_viewed);

		$value 	= implode(',', $recently_viewed);
		$expire	= time() + 24 * 60 * 60;

		setcookie($cookie_name, $value, $expire, '/', false, true);
	}

	public static function hasRecentlyViewed($options) {

		$cookie_name 		= USE_PREFIX . 'recently_viewed';
		$recently_viewed 	= isset( $_COOKIE[$cookie_name] ) ? $_COOKIE[$cookie_name] : null;

		if($recently_viewed == null) {
			return false;
		}

		$recently_viewed 	= explode(',', $recently_viewed);

		$posts = array();
		for($i = 0; $i < count($recently_viewed); $i++) {
			if($recently_viewed[$i] != $options['exclude']) {
				$posts[] = $recently_viewed[$i];
			}
		}

		if(count($posts) == 0) {
			return false;
		}

		return isset($posts[0]);
	}

	public static function getRecentlyViewedHTML($options) {

		$cookie_name 		= USE_PREFIX . 'recently_viewed';
		$recently_viewed 	= isset( $_COOKIE[$cookie_name] ) ? $_COOKIE[$cookie_name] : '';
		$recently_viewed 	= explode(',', $recently_viewed);

		$posts = array();
		for($i = 0; $i < count($recently_viewed); $i++) {
			if($recently_viewed[$i] != $options['exclude']) {
				$posts[] = $recently_viewed[$i];
			}
		}

		if(count($posts) == 0) {
			return false;
		}

		$options = array(
			'post__in' 		=> $posts,
			'post_type' 	=> 'properties',
			'post_status' 	=> 'publish',
			'properties_per_page' => 10000
		);

		$properties = PropertiesManager::searchProperties($options);

	    ob_start();

	    $list_style = 'carousel';

	    require COMPONENTS_PATH . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "properties.php";

	    $content .= ob_get_contents();

	    ob_end_clean();

	    return $content;

	}

	public static function getOtherPeopleAlsoViewedHTML($post, $options = array()) {

		$session_id = Registry::get('session_id');

		// No session id found - terminate the function.
		if($session_id === null) {
			return false;
		}

		$other_people_also_viewed = get_option(USE_PREFIX . 'other_people_also_viewed');

		if(false !== $other_people_also_viewed) {

			$other_people_also_viewed = unserialize($other_people_also_viewed);
			
		} else {

			$other_people_also_viewed = array();
		}

		$seen_properties = array();

		// Loop through all sessions
		foreach($other_people_also_viewed as $_session_id => $_properties) {
			
			// Loop though each session's properties seen
			foreach($_properties as $_property_id) {

				// If any of the properties match the current property
				if($_property_id == $post->ID) {

					$seen_properties = array_merge($seen_properties, $_properties);
				}
			}
		}

		// Remove any dublications
		$seen_properties = array_unique($seen_properties);

		// Remove current property list
		$seen_properties = array_diff($seen_properties, array($post->ID));

		// Create a session ID for this customer
		if(!isset($other_people_also_viewed[$session_id])) {
			$other_people_also_viewed[$session_id] = array();
		}

		$other_people_also_viewed[$session_id][] = $post->ID;

		$other_people_also_viewed[$session_id] = array_unique($other_people_also_viewed[$session_id]);

		$other_people_also_viewed = serialize($other_people_also_viewed);

		$result = update_option(USE_PREFIX . 'other_people_also_viewed', $other_people_also_viewed);

		$options = array(
			'post__in' 		=> $seen_properties,
			'post_type' 	=> 'properties',
			'post_status' 	=> 'publish',
			'properties_per_page' => 10000,
		);

		$properties = PropertiesManager::searchProperties($options);

	    ob_start();

	    $list_style = 'carousel';

	    require COMPONENTS_PATH . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "properties.php";

	    $content .= ob_get_contents();

	    ob_end_clean();

	    return $content;
	}
}