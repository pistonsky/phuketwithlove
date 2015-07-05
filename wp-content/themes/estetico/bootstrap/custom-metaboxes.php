<?php

function estetico_get_custom_meta_boxes() {

	$pet_options = estetico_get_pet_options();

	$meta_boxes = array(

		'page' => array(
			array( 'id' => USE_PREFIX . 'galleries_per_row', 'title' => __('Galleries per row', THEME_NAME), 'callback_args' => array('type' => 'select', 'options' => array(2 => 2, 3 => 3, 4 => 4))),
			array( 'id' => USE_PREFIX . 'galleries_to_display', 'title' => __('Galleries to display', THEME_NAME), 'callback_args' => array('post_type' => 'property_gallery', 'multiple' => true)),	
		),

		'agencies' => array(
			array( 'id' => USE_PREFIX . 'default_agency', 'title' => __( 'Default agency',THEME_NAME), 'callback_args' => array( 'type' => 'checkbox' ), 'before_save' => 'estetico_unique_custom_field' ),
			array( 'id' => USE_PREFIX . 'address', 'title' => __('Address', THEME_NAME)),
			array( 'id' => USE_PREFIX . 'phone', 'title' => __('Phone', THEME_NAME)),
			array( 'id' => USE_PREFIX . 'email', 'title' => __('Email', THEME_NAME)),
		),

		'agents' => array(
			array( 'id' => USE_PREFIX . 'phone',		'title' => __( 'Phone', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'email',		'title' => __( 'Email', THEME_NAME) ),
			array( 'id' => USE_PREFIX . 'website',		'title' => __( 'Website', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'agency',		'title' => __( 'Agency', THEME_NAME ), 'callback_args' => array( 'post_type' => 'agencies', 'default_option' => __( 'Choose an agency', THEME_NAME ) ) ),
			array( 'id' => USE_PREFIX . 'twitter',		'title' => __( 'Twitter', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'facebook',		'title' => __( 'Facebook', THEME_NAME) ),
			array( 'id'	=> USE_PREFIX . 'google_plus', 	'title' => __( 'Google Plus', THEME_NAME) ),
			array( 'id' => USE_PREFIX . 'languages', 	'title' => __( 'Languages', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'default_agent', 'title' => __( 'Default agent', THEME_NAME ), 'callback_args' => array( 'type' => 'checkbox' ), 'before_save' => 'estetico_unique_custom_field' ),
			array( 'id' => USE_PREFIX . 'enable_direct_agents_contact', 'title' => __( 'Enable direct agent contact', THEME_NAME ), 'callback_args' => array('desc' => __('"Neighter" is when you don\'t want to sell or rent the property', THEME_NAME), 'type' => 'select', 'options' => array( 'use_global' => __('Use global option', THEME_NAME), 'yes' => __('Yes', THEME_NAME), 'no' => __('No', THEME_NAME) ) ) )
		),

		'properties' => array(
            array( 'id' => USE_PREFIX . 'video',			'title'	=> __( 'Video url (youtube or vimeo)', THEME_NAME), 'before_save' => 'estetico_get_video_url'),
            array( 'id' => USE_PREFIX . 'video_thumb_url',	'title'	=> __( 'A hidden field to hold the thumb image of the video url', THEME_NAME), 'callback_args' => array('type' => 'hidden'), 'readonly' => true),
			array( 'id' => USE_PREFIX . 'beds',				'title'	=> __( 'Number of beds', THEME_NAME)),
			array( 'id' => USE_PREFIX . 'year_built',		'title'	=> __( 'Year built', THEME_NAME)),
			array( 'id' => USE_PREFIX . 'for_sale_rent',	'title'	=> __( 'For sale or rent', THEME_NAME), 'callback_args' => array('type' => 'select', 'options' => array('sale' => __('Sale', THEME_NAME), 'rent' => __('Rent', THEME_NAME), 'both' => __('Both', THEME_NAME), 'neither' => __('Neither', THEME_NAME)))),
            array( 'id' => USE_PREFIX . 'property_status',	'title'	=> __( 'Property status', THEME_NAME), 'callback_args' => array('type' => 'select', 'options' => array('' => __('', THEME_NAME), 'sold' => __('Sold', THEME_NAME), 'rented' => __('Rented', THEME_NAME), 'let_agreed' => __('Let agreed', THEME_NAME), 'sale_agreed' => __('Sale agreed', THEME_NAME)))),
			array( 'id' => USE_PREFIX . 'agent',			'title' => __( 'Agent', THEME_NAME ), 'callback_args' => array( 'post_type' => 'agents', 'default_option' => __( 'Choose an agent', THEME_NAME ), 'selected' => estetico_get_default_agent_id(), 'multiple' => true ) ),
			array( 'id' => USE_PREFIX . 'price',			'title' => __( 'Price', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'price_rent_month', 'title' => __( 'Price rent (PCM)', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'price_rent_week', 	'title' => __( 'Price rent (PW)', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'pets_allowed', 	'title' => __( 'Pets allowed', THEME_NAME ), 'callback_args' => array('type' => 'checkbox', 'on_checked' => array('toggle' => 'pet_types')) ),
			array( 'id' => USE_PREFIX . 'pet_types', 		'title' => __( 'What pets are allowed', THEME_NAME ), 'callback_args' => array('type' => 'select', 'options' => $pet_options)),
			#array( 'id' => USE_PREFIX . 'shared_accommodation', 'title' => __( 'Share accommodation', THEME_NAME ), 'callback_args' => array('type' => 'checkbox') ),
			array( 'id' => USE_PREFIX . 'sq_ft',			'title' => __( 'Area (sq. ft or sq. meters)', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'bedrooms',			'title' => __( 'Bedrooms', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'bathrooms', 		'title' => __( 'Bathrooms', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'address',			'title' => __( 'Address', THEME_NAME ), 'callback_args' => array( 'use_geofinder' => true, 'desc' => __('Please use the other address fields to set the address so that you can take advantage of all available filtering options. This field is just for backward compatibility and will be removed in the near future.', THEME_NAME) ) ),
			array( 'id' => USE_PREFIX . 'city',				'title' => __( 'City', THEME_NAME ), 'callback_args' => array('autocomplete' => true) ),
			array( 'id' => USE_PREFIX . 'country',			'title' => __( 'Country', THEME_NAME ), 'callback_args' => array('autocomplete' => true) ),
			array( 'id' => USE_PREFIX . 'street_address',	'title' => __( 'Street address', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'street_number',	'title' => __( 'Street number', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'floor',			'title' => __( 'Floor', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'apartment_number',	'title' => __( 'Apartment number', THEME_NAME ) ),
			array( 'id' => USE_PREFIX . 'state',			'title' => __( 'State', THEME_NAME ), 'callback_args' => array('autocomplete' => true) ),
			array( 'id' => USE_PREFIX . 'county',			'title' => __( 'County', THEME_NAME ) , 'callback_args' => array('autocomplete' => true) ),
			array( 'id' => USE_PREFIX . 'postal_code',		'title' => __( 'Postal code', THEME_NAME ), 'callback_args' => array('autocomplete' => true) ),
			array( 'id' => USE_PREFIX . 'latitude',			'title' => __( 'Latitude', THEME_NAME ), 'callback_args' => array('use_geofinder' => true, 'desc' => __( 'Fill other address fields for better accuracy if you don\'t know the latitude.', THEME_NAME)) ),
			array( 'id' => USE_PREFIX . 'longitude', 		'title' => __( 'Longitude', THEME_NAME ), 'callback_args' => array('use_geofinder' => true, 'desc' => __( 'Fill other address fields for better accuracy if you don\'t know the longitude.', THEME_NAME)) ),
			array( 'id' => USE_PREFIX . 'street_view_pov', 	'title' => __('Adjust Street View point of view', THEME_NAME), 'callback_args' => array('type' => 'street_view_pov')),
			array( 'id' => USE_PREFIX . 'featured', 'title' => __( 'Featured', THEME_NAME ), 'callback_args' => array('type' => 'checkbox') ),
			array( 'id' => USE_PREFIX . 'view_count', 'title' => __( 'View count', THEME_NAME), 'callback_args' => array('type' => 'hidden'), 'readonly' => true)
			//array( 'id' => USE_PREFIX . 'property_status', 	'title' => __( 'Status', THEME_NAME ), 'callback_args' => array('type' => 'select', 'options' => array('new' => __('New', THEME_NAME), 'let_agreed' => __('Let agreed', THEME_NAME), 'closed' => __('Closed', THEME_NAME))) ),
		)
	);

	return $meta_boxes;
}