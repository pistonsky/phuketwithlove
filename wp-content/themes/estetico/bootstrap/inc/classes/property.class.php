<?php

class Property {
	
	private $_data = array();

	private $meta = array();

	private $id = null;

	public function __construct( $id = null ) {

		$this->id = $id;
	}

	public static function getCurrent() {
		$post = get_post();
		$property = new Property($post->ID);
		return $property;
	}

	public function getId() {
		return $this->id;
	}

	public function setId( $id ) {
		$this->id = $id;
	}

	public function fetch() {

		$this->_data = get_post($this->id);
		$this->extractMeta();
	}

	public function setData( $data = array() ) {

		$this->_data = $data;

		if( $this->id === null ) {
			$this->setId( $this->_data->ID );
		}
	}

	public function getData( $key ) {

		return isset( $this->_data[ $key ] ) ? $this->_data[ $key] : $this->_data;
	}

	public function getLink() {

		return get_permalink( $this->getId() );
	}

	public function getTitle() {

		return $this->_data->post_title;
	}

	public function _getAddress() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'address'][0] ) ? $meta[USE_PREFIX . 'address'][0] : "";
	}

	/**
	 * @return string Property address in a custom format
	 */
	public function getAddress() {

		// Version 1.0 only supported one line address
		if( estetico_get_setting('use_property_address_field') == 'yes' ) {
			return $this->_getAddress();
		}

		$format = estetico_get_setting( 'address_format' );

		if( empty( $format ) ) {
			return "";
		}


		$format = preg_replace_callback('/\[if(.*?)\](.*?)\[\/if\]/', array($this, 'addressFormatHelper'), $format);

		$address = $format;

		preg_match_all('/%(.*?)%/', $address, $address_variables);

		if(isset($address_variables[1])) {

			foreach($address_variables[1] as $part) {

				$address = str_replace('%' . $part . '%', $this->addressPartToValue($part), $address);
			}
		}

		$address = nl2br($address);

		return $address;
	}

	public function addressFormatHelper($arg) {

		// Check if there is a variable
		preg_match('/%(.*?)%/', $arg[1], $matches);

		// No valid variable to check for existance
		if(empty($matches)) {
			return '#INVALID_FORMAT#';
		}

		$check = $matches[1];
		$value = $this->addressPartToValue($check);

		if(empty($value)) {
			return '';
		} else {
			return str_replace($matches[0], $value, $arg[2]);
		}
		
	}

	public function addressPartToValue($part) {

		$value = null;

		switch ($part) {
			case 'street_address':
				$value = $this->getStreetAddress();
			break;

			case 'street_number':
				$value = $this->getStreetNumber();
			break;

			case 'city':
				$value = $this->getCity();
			break;

			case 'country':
				$value = $this->getCountry();
			break;

			case 'county':
				$value = $this->getCounty();
			break;

			case 'state':
				$value = $this->getState();
			break;

			case 'floor':
				$value = $this->getFloor();
			break;

			case 'apartment_number':
				$value = $this->getApartmentNumber();
			break;

			case 'postal_code':
				$value = $this->getPostalCode();
			break;
			
		}

		return $value;
	}

	public function getStreetNumber() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'street_number'][0] ) ? $meta[USE_PREFIX . 'street_number'][0] : "";
	}

	public function getStreetAddress() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'street_address'][0] ) ? $meta[USE_PREFIX . 'street_address'][0] : "";
	}

	public function getCity() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'city'][0] ) ? $meta[USE_PREFIX . 'city'][0] : "";
	}

	public function getPostalCode() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'postal_code'][0] ) ? $meta[USE_PREFIX . 'postal_code'][0] : "";
	}

	public function getCountry() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'country'][0] ) ? $meta[USE_PREFIX . 'country'][0] : "";
	}

	public function getCounty() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'county'][0] ) ? $meta[USE_PREFIX . 'county'][0] : "";
	}

	public function getState() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'state'][0] ) ? $meta[USE_PREFIX . 'state'][0] : "";
	}

	public function getFloor() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'floor'][0] ) ? $meta[USE_PREFIX . 'floor'][0] : "";
	}

	public function getApartmentNumber() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'apartment_number'][0] ) ? $meta[USE_PREFIX . 'apartment_number'][0] : "";
	}

	public function getYearBuilt() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'year_built'][0] ) ? $meta[USE_PREFIX . 'year_built'][0] : "";
	}

	/**
	 * @param $format Boolean If set to true the return will be well formated string, either way only number
	 * @return Mixed Price of the property with level of priority:
	 * 1. Sell price
	 * 2. Rent price /month
	 * 3. Rent price /week
	 */
	public function getPrice($format = false, $asNumber = false) {

		$sell_price = $this->getSellPrice();
		$rent_month_price = $this->getRentMonthPrice();
		$rent_week_price = $this->getRentWeekPrice();

		$price = 0;
		$format_type = null;

		if($sell_price != null) {
			$price = $sell_price;
		} else if($rent_month_price != null) {
			$price = $rent_month_price;
			if($format) {
				$format_type = 'per_month';
			}
		} else if($rent_week_price != null) {
			$price = $rent_week_price;
			if($format) {
				$format_type = 'per_week';
			}
		}

		$price = $this->formatPrice($price, $format, $asNumber, $format_type);

		return $price;
	}

	private function formatPrice($price, $format, $asNumber, $format_type = null) {

		$price = preg_replace('/\s+/', '', $price);

		if($asNumber) {
			$price_number = (float)( preg_replace('/[^0-9]/', '', $price) );
			return $price_number;
		}

		if($format) {
			$price = estetico_price_format( $price );
		}

		if( $format && $format_type ) {

			switch($format_type) {
				case 'per_month':
					$price = sprintf( __( '%s PCM', THEME_NAME ), $price ) ;
				break;
				case 'per_week':
					$price = sprintf( __( '%s PW', THEME_NAME ), $price );
				break;
			}
		}

		return $price;
	}

	/**
	 * @return Number The price for selling property
	 */
	public function getSellPrice($format = false) {
		$meta = $this->getMeta();
		$price = isset( $meta[USE_PREFIX . 'price'][0] ) ? $meta[USE_PREFIX . 'price'][0] : null;

		if($format) {
			$price = $this->formatPrice($price, $format);
		}

		return $price;
	}

	/**
	 * @return Number The price per month for renting the property
	 */
	public function getRentMonthPrice($format = false) {
		$meta = $this->getMeta();
		$price = isset( $meta[USE_PREFIX . 'price_rent_month'][0] ) ? (float)$meta[USE_PREFIX . 'price_rent_month'][0] : null;

		if($format) {
			$price = $this->formatPrice($price, $format, null, 'per_month');
		}

		return $price;
	}

	/**
	 * @return Number The price per week for renting the property
	 */
	public function getRentWeekPrice($format = false) {
		$meta = $this->getMeta();
		$price = isset( $meta[USE_PREFIX . 'price_rent_week'][0] ) ? (float)$meta[USE_PREFIX . 'price_rent_week'][0] : null;

		if($format) {
			$price = $this->formatPrice($price, $format, null, 'per_week');
		}

		return $price;
	}

	public function getBeds() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'beds'][0] ) ? $meta[USE_PREFIX . 'beds'][0] : 0;
	}

	public function getBedrooms() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'bedrooms'][0] ) ? $meta[USE_PREFIX . 'bedrooms'][0] : 0;
	}

	public function getBathrooms() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'bathrooms'][0] ) ? $meta[USE_PREFIX . 'bathrooms'][0] : 0;
	}

	public function getSqFt() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'sq_ft'][0] ) ? $meta[USE_PREFIX . 'sq_ft'][0] : 0;
	}

	public function getLatitude() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'latitude'][0] ) ? $meta[USE_PREFIX . 'latitude'][0] : 0;
	}

	public function getLongitude() {

		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'longitude'][0] ) ? $meta[USE_PREFIX . 'longitude'][0] : 0;
	}

	public function getViewCount() {
		$meta = $this->getMeta();
		return isset( $meta[USE_PREFIX . 'view_count'][0] ) ? (int)$meta[USE_PREFIX . 'view_count'][0] : 0;
	}

	/**
	 * @param $count Number
	 */
	public function setViewCount($count) {

		update_post_meta($this->getId(), USE_PREFIX . 'view_count', $count);
	}

	public function getStreetViewPov() {
		$meta = $this->getMeta();
		$pov = $meta[USE_PREFIX . 'street_view_pov'][0];

		if(empty($pov)) {
			return json_encode(array('pitch' => 10, 'heading' => 10));
		} else {
			return $pov;
		}
	}

	public function getPetsAllowed() {
		$meta = $this->getMeta();
		return isset($meta[USE_PREFIX . 'pets_allowed'][0]) && $meta[USE_PREFIX . 'pets_allowed'][0] == 'on';
	}

	public function getPetTypes() {
		$meta = $this->getMeta();
		#$value = estetico_get_custom_metabox_field_option_value( 'properties', 'pet_types', isset($meta['pet_types']) ? $meta['pet_types'][0] : null );

		$pet_options = estetico_get_pet_options();

		if(isset($meta[USE_PREFIX . 'pet_types'])) {

			if( isset( $pet_options[ $meta[USE_PREFIX . 'pet_types'][0] ] ) ) {
				return $pet_options[$meta[USE_PREFIX . 'pet_types'][0]];
			}
		}

		return null;
	}

	public function getTypes() {

		$types	= wp_get_post_terms( $this->getId(), 'properties-types' );

		return $types;
	}

	public function getType() {

		$types = $this->getTypes();

		$property_type = "";
		if(isset($types[0])) {
			$property_type = $types[0]->name;
		}

		return $property_type;
	}

	public function getForSaleRent() {

		$meta = $this->getMeta();

		return isset( $meta[USE_PREFIX . 'for_sale_rent'] ) ? $meta[USE_PREFIX . 'for_sale_rent'][0] : 'neighter';
	}
    
    public function getPropertyStatus() {

		$meta = $this->getMeta();
		$value = isset( $meta[USE_PREFIX . 'property_status'][0] ) ? $meta[USE_PREFIX . 'property_status'][0] : "";

		if($value === "new") {
			$value = "";
		}

		return $value;
	}

    public function getVideoUrl() {

		$meta = $this->getMeta();

		return isset( $meta[USE_PREFIX . 'video'][0]) ? $meta[USE_PREFIX . 'video'][0] : '';
	}
    
    public function getVideoThumbnailUrl() {
    
        $meta = $this->getMeta();

		return isset( $meta[USE_PREFIX . 'video_thumb_url'][0]) ? $meta[USE_PREFIX . 'video_thumb_url'][0] : '';
    }
    
	private function extractMeta() {

		$this->meta = get_post_meta( $this->getId() );
	}

	public function getThumbnail() {

		$thumbnail = wp_get_attachment_image( get_post_thumbnail_id( $this->getId() ), 'properties-listing' );

		return $thumbnail;
	}

	public function getImage($size) {

		$thumbnail = get_the_post_thumbnail($this->getId(), $size);

		preg_match("/src=[\"'](.*?)[\"']/", $thumbnail, $matches );

		$src = null;
		if(isset($matches[1])) {
			$src = $matches[1];
		}

		return $src;
	}

	public function getMeta() {

		if( empty( $this->meta) ) {
			$this->extractMeta();
		}

		return $this->meta;
	}

	public function getFeatures() {

		$options = array();

		$terms = wp_get_post_terms( $this->getId(), 'properties-features', $options );

		return $terms;
	}

	public function isFeatured() {

		return isset( $this->meta[USE_PREFIX . 'featured'] ) ? ( $this->meta[USE_PREFIX . 'featured'][0] == 'on' ) : false;
	}

	public function vars() {
		$vars = array();

		$vars['property_type']	= $this->getType();
		$vars['thumbnail'] 		= $this->getImage('properties-listing');
		$vars['address']		= $this->getAddress();
		$vars['city']			= $this->getCity();
		$vars['state'] 			= $this->getState();
		$vars['street_address'] = $this->getStreetAddress();
		$vars['postal_code']	= $this->getPostalCode();
		$vars['bathrooms']		= $this->getBathrooms();
		$vars['bedrooms']		= $this->getBedrooms();
		$vars['sq_feet']		= $this->getSqFt();
		$vars['price']			= $this->getPrice(true);
		$vars['link']			= $this->getLink();
		$vars['longitude']		= $this->getLongitude();
		$vars['latitude']		= $this->getLatitude();
		$vars['title']			= $this->getTitle();
		$vars['for_sale_rent']	= $this->getForSaleRent();
        $vars['property_status'] = $this->getPropertyStatus();
        $vars['video']          = $this->getVideoUrl();
        $vars['video_thumb_url'] = $this->getVideoThumbnailUrl();

		return $vars;
	}

	public function getSchemaOrgMetaTags() {
		
		extract($this->vars());

		ob_start();
		?>
		<div itemscope itemtype="http://schema.org/Place">
			<meta itemprop="name" content="<?php echo esc_attr( $title ) ?>">
			<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
				<?php if( ! empty( $street_address ) ) : ?>
					<meta itemprop="streetAddress" content="<?php echo esc_attr( $street_address ) ?>">
				<?php endif ?>

				<?php if( ! empty( $city ) ) : ?>
					<meta itemprop="addressLocality" content="<?php echo esc_attr( $city ) ?>">
				<?php endif ?>

				<?php if( ! empty( $state ) ) : ?>
					<meta itemprop="addressRegion" content="<?php echo esc_attr( $state ) ?>">
				<?php endif ?>

				<?php if( ! empty( $postal_code ) ) : ?>
					<meta itemprop="postalCode" content="<?php echo esc_attr( $postal_code ) ?>">
				<?php endif ?>
			</div>
			<?php if( ! empty( $thumbnail ) ) : ?>
				<meta content="<?php echo $thumbnail ?>" alt="" itemprop="photo">	
			<?php endif ?>
			<meta content="<?php echo $link ?>" itemprop="url">
			<?php if($longitude && $latitude) :?>
				<div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
					<meta itemprop="latitude" content="<?php echo $latitude ?>" />
					<meta itemprop="longitude" content="<?php echo $longitude ?>" />
				</div>
			<?php endif ?>
		</div>

		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<meta itemprop="price" content="<?php echo esc_attr($price) ?>">
			<?php $currency_iso_code = estetico_get_setting('currency_iso_code');
			if( ! empty( $currency_iso_code ) ) : ?>
			<meta itemprop="priceCurrency" content="<?php echo estetico_get_setting('currency_iso_code') ?>">
		<?php endif ?>
			<meta itemprop="url" content="<?php echo esc_attr($link) ?>">
		</div>

		<?php 
		
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	/**
	 *
	 */
	public function increaseViewCount() {

		$view_count = $this->getViewCount();
		$this->setViewCount(++$view_count);
	}
}