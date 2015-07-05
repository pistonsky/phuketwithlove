<?php
/**
 * Initialize data post 1.3.3 update
 */

function after_init_133() {

	$option = get_option(USE_PREFIX . 'after_init_133');

	if($option === false) {

		// Get all properties 
		$args = array(
			'post_type' => 'properties',
			'posts_per_page' => PHP_INT_MAX
		);
		$properties = get_posts($args);

		foreach($properties as $property) {

			$meta = get_post_meta($property->ID, USE_PREFIX . 'agent');

			// Convert all non array agents to array
			if(isset($meta[0])) {
				if(!is_array($meta[0])) {
					$new_meta = array($meta[0]);
					update_post_meta($property->ID, USE_PREFIX . 'agent', $new_meta);
				}
			}
		}

		update_option(USE_PREFIX . 'after_init_133', true);	
	}
}

after_init_133();

?>