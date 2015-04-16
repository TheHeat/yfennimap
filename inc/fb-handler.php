<?php

/* AJAX Request Handling */

add_action('wp_ajax_post_to_facebook', 'post_to_facebook');
add_action('wp_ajax_nopriv_post_to_facebook', 'post_to_facebook');

function post_to_facebook(){


	$output = array(
		'error' => 'No way!'
	);

	wp_send_json( $output );
	
	die();
}