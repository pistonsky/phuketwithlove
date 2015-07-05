<?php
/**
 * Initialize data post 1.3 update
 */

function after_init_13() {

	$option = get_option(USE_PREFIX . 'after_init_13');

	if($option === false) {

		// If a theme is installed previosly none of the posts will have "view_count" set to them and this will break search results
		estetico_init_meta_box_value('properties', USE_PREFIX . 'view_count', 0);

		update_option(USE_PREFIX . 'after_init_13', true);	
	}
}

after_init_13();

?>