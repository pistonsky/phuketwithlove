<?php

require_once "agent.class.php";

class AgentsManager {

	public function getAllByAgency($agency) {

		$options = array(
			'post_type' 	=> 'agents',
			'post_status' 	=> 'publish',
			'posts_per_page' => PHP_INT_MAX,
			'meta_key'		=> USE_PREFIX . 'agency',
			'meta_value'	=> $agency
		);

		$agents = get_posts($options);
		$_agents = array();

		foreach($agents as $agent) {
			
			$agent_tmp = new Agent($agent->ID);

			$_agents[] = $agent_tmp;
		}

		return $_agents;
	}

	public function getAll() {

		$options = array(
			'post_type' 	=> 'agents',
			'post_status' 	=> 'publish',
			'posts_per_page' => PHP_INT_MAX
		);

		$agents = get_posts($options);
		$_agents = array();

		foreach($agents as $agent) {
			
			$agent_tmp = new Agent($agent->ID);

			$_agents[] = $agent_tmp;
		}

		return $_agents;
	}

	public static function getDefaultAgent() {

		$meta_query = array();

		$meta_query[] = array('key' => USE_PREFIX . 'default_agent', 'value' => 'on', 'compare' => '=');

		$args = array(
			'post_type' => 'agents',
			'meta_query' => $meta_query
		);

		$agents = new WP_Query($args);

		return isset($agents->posts[0]) ? $agents->posts[0] : false;
	}
}