<?php

require_once "./../../../../../wp-load.php";

$featured = $_GET['featured'];

$post_id = (int)$_GET['post_id'];

$response = array();

if( update_post_meta( $post_id, USE_PREFIX . 'featured', $featured) ) {

	$response['status'] = 'ok';
} else {

	$response['status'] = 'error';
}

header( "Content-Type: application/json; encoding=utf-8" );

echo json_encode($response);

?>