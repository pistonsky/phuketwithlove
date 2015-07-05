<?php

require_once "./../../../../../wp-load.php";

$type = wp_kses( $_GET['type'], array() );

$meta_query = array();
$meta_query[] = array('key' => USE_PREFIX . $type, 'value' => $_GET['q'], 'compare' => 'LIKE');

$args = array(
	'meta_query' => $meta_query,
	'post_type' => 'properties',
	'ignore_sticky_posts' => 1
);

$query = new WP_Query($args);
$posts = $query->posts;

if( ! empty( $posts ) ) {
	$suggest = array();
	foreach($posts as $post) {
		$meta = get_post_meta($post->ID);
		if( isset( $meta[ USE_PREFIX . $type] ) ) {
			$suggest[] = $meta[USE_PREFIX . $type][0];
		}
	}

	$suggest = array_unique($suggest);

	foreach ($suggest as $option) {
		echo $option;
		echo "\n";
	}
}

?>