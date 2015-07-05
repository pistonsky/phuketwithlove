<?php

require_once "agency.class.php";

class AgenciesManager {

	public function getAll() {

		$options = array(
			'post_type' 	=> 'agencies',
			'post_status' 	=> 'publish',
			'posts_per_page' => PHP_INT_MAX
		);

		$agencies = get_posts($options);
		$_agencies = array();

		foreach($agencies as $agency) {
			
			$agency_tmp = new Agency($agency->ID);

			$_agencies[] = $agency_tmp;
		}

		return $_agencies;
	}
}