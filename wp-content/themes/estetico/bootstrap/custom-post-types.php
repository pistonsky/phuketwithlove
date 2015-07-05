<?php

if( ! function_exists( 'estetico_create_post_type_faq' ) ) {
	function estetico_create_post_type_faq() {
		register_post_type( 'faq',
			array(

				'labels' => array(
					'name'			=> __( 'FAQ', THEME_NAME ),
					'singular_name' => __( 'FAQ', THEME_NAME ),
					'all_items'		=> __( 'All FAQ', THEME_NAME )
				),
				'public' => true,
				'show_in_menu' => false,
				'supports' => array('title', 'editor', 'thumbnail'),
				'exclude_from_search' => true
			)
		);

		flush_rewrite_rules();
	}
}

if( ! function_exists( 'estetico_create_post_type_agents' ) ) {
	function estetico_create_post_type_agents() {
		register_post_type( 'agents',
			array(

				'labels' => array(
					'name'			=> __( 'Agents', THEME_NAME ),
					'singular_name' => __( 'Agent', THEME_NAME ),
					'all_items'		=> __( 'All agents', THEME_NAME )
				),
				'public' => true,
				'show_in_menu' => false,
				'supports' => array('title', 'editor', 'thumbnail')
			)
		);

		flush_rewrite_rules();
	}
}

if( ! function_exists( 'estetico_create_post_type_agencies' ) ) {
	function estetico_create_post_type_agencies() {
		register_post_type( 'agencies',
			array(

				'labels' => array(
					'name'			=> __( 'Agencies', THEME_NAME ),
					'singular_name' => __( 'Agency', THEME_NAME ),
					'all_items'		=> __( 'All agencies', THEME_NAME )
				),
				'public' => true,
				'show_in_menu' => false,
				'supports' => array('title', 'editor', 'thumbnail')
			)
		);

		flush_rewrite_rules();
	}
}

if( ! function_exists( 'estetico_create_post_type_properties' ) ) {
	function estetico_create_post_type_properties() {
		register_post_type( 'properties',
			array(

				'labels' => array(
					'name'			=> __( 'Properties', THEME_NAME ),
					'singular_name' => __( 'Property', THEME_NAME ),
					'all_items'		=> __( 'All properties', THEME_NAME )
				),
				'public'		=> true,
				'show_in_menu'	=> true,
				'supports'		=> array('title', 'editor', 'thumbnail'),
				'taxonomies'	=> array('agencies', 'agentes')
			)
		);

		flush_rewrite_rules();
	}
}

if( ! function_exists( 'estetico_create_post_type_testimonials' ) ) {
	function estetico_create_post_type_testimonials() {
		register_post_type( 'testimonials',
			array(

				'labels' => array(
					'name'			=> __( 'Testimonials', THEME_NAME ),
					'singular_name' => __( 'Testimonial', THEME_NAME ),
					'all_items'		=> __( 'All testimonials', THEME_NAME )
				),
				'public'		=> true,
				'show_in_menu'	=> false,
				'supports'		=> array( 'title', 'editor', 'thumbnail' ),
				'exclude_from_search' => true
			)
		);

		flush_rewrite_rules();
	}
}

if( ! function_exists('estetico_create_post_type_property_gallery') ) {

	function estetico_create_post_type_property_gallery() {
		register_post_type('property_gallery',
			array(

				'labels' => array(
					'name'			=> __( 'Gallery', THEME_NAME ),
					'singular_name' => __( 'Gallery', THEME_NAME ),
					'all_items'		=> __( 'All Galleries', THEME_NAME )
				),
				'public'		=> true,
				'show_in_menu'	=> true,
				'supports'		=> array( 'title', 'editor', 'thumbnail' ),
				'exclude_from_search' => true
			)
		);

		flush_rewrite_rules();
	}
}

if( ! function_exists( 'estetico_register_custom_post_types_batch' ) ) {
	function estetico_register_custom_post_types_batch() {
		estetico_create_post_type_properties();
		estetico_create_post_type_agencies();
		estetico_create_post_type_agents();
		estetico_create_post_type_faq();
		estetico_create_post_type_testimonials();
		estetico_create_post_type_property_gallery();
	}
}

add_action( 'init', 'estetico_register_custom_post_types_batch' );

?>