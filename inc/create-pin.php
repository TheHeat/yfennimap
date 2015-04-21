<?php
	
function create_pin( $body, $image_id = null ){

	// Creates a new post in WordPress

	//Insert Post
	//standard WP info
	$post_title = (isset($body['title']) ? $body['title'] : substr($body['content'], 0, 15) );
	$post_information = array(
		'post_title' => $post_title,
		'post_type' => 'pin',
		'post_status' => 'pending' // Set to 'publish' once we're done all this jazz
	);

	//Create Post
	$post_id = wp_insert_post($post_information);
	
	//Custom Fields
	//Only try to set it if it exists...
	if(isset($body['content'])){
		update_post_meta( $post_id, "description", strip_tags(isset($body['content']) ? $body['content'] : null ));
	}
	if(isset($body['mediaType'])){
		$media = $body['mediaType'];
		update_post_meta( $post_id, "media_type", strip_tags($media) );
		// update_post_meta( $post_id, "media_type", 'text' );
	}
	if(isset($body['link'])){
		update_post_meta( $post_id, "link",esc_attr(strip_tags(isset($body['link']) ? $body['link'] : null )) );
	}
	if(isset($body['year'])){
		update_post_meta( $post_id, "year-created",esc_attr(strip_tags(isset($body['year']) ? $body['year'] : null )) );
	}

	//Make sure that the category IDs are as integers rather than strings
	if(isset($body['category'])){
		$pin_cats = array_map('intval', $body['category']);
	}

	// echo 'type';


	//Set the categories
	if(isset($body['category'])){
		
		$new_terms = wp_set_object_terms( $post_id, $pin_cats, 'pin_category', true);

	}

	//get users fb token and save against post
	// add_post_meta( $post_id, 'user_fb_token', fb_get_token() );
	
	//get location info from pin placement from url string nd add to acf google map field
	$location['address'] = '';
	$location['lat'] = esc_attr(strip_tags(isset($body['lat']) ? $body['lat'] : null ));
	$location['lng'] = esc_attr(strip_tags(isset($body['lng']) ? $body['lng'] : null ));
	update_field( 'field_5362ae02910ff',  $location, $post_id);
	// update_field( 'field_53a9772057ffd',  $_POST['cat'], $post_id);


	$location_field = get_field( 'field_5362ae02910ff', $post_id);


	// if user has uploaded an image file
	if ( $media == 'image' && $image_id ):

		// Add image as featured image
		// add_post_meta($post_id, '_thumbnail_id', $attachment_id);
		set_post_thumbnail( $post_id, $image_id );

	endif;

	change_post_status( $post_id ,'publish' );

	return $post_id;
}		
?>