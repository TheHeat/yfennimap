<?php

	//file for submitting pin from form

	//Insert Post
	//standard WP info
	$post_title = (isset($_POST['postTitle']) ? $_POST['postTitle'] : null );
	$post_information = array(
		'post_title' => $post_title,
		'post_type' => 'pin',
		'post_status' => 'publish'
	);

	//Create Post
	$post_id = wp_insert_post($post_information);
	
	//Custom Fields
	//Only try to set it if it exists...
	if(isset($_POST['postContent'])){
		update_post_meta( $post_id, "description", esc_attr(strip_tags(isset($_POST['postContent']) ? $_POST['postContent'] : null )) );
	}
	if(isset($_POST['media'])){
		$media = $_POST['media'];
		update_post_meta( $post_id, "media_type", esc_attr(strip_tags(isset($_POST['media']) ? $_POST['media'] : null )) );
	}
	if(isset($_POST['link'])){
		update_post_meta( $post_id, "link",esc_attr(strip_tags(isset($_POST['link']) ? $_POST['link'] : null )) );
	}
	if(isset($_POST['year-created'])){
		update_post_meta( $post_id, "year-created",esc_attr(strip_tags(isset($_POST['year-created']) ? $_POST['year-created'] : null )) );
	}

	//Make sure that the category IDs are as integers rather than strings
	if(isset($_POST['pin_category'])){
		$pin_cats = array_map('intval', $_POST['pin_category']);
	}

	// echo 'type';


	//Set the categories
	if(isset($_POST['pin_category'])){
		
		$new_terms = wp_set_object_terms( $post_id, $pin_cats, 'pin_category', true);

	}

	//get users fb token and save against post
	add_post_meta( $post_id, 'user_fb_token', fb_get_token() );
	
	//get location info from pin placement from url string nd add to acf google map field
	$location['address'] = '';
	$location['lat'] = esc_attr(strip_tags(isset($_POST['lat']) ? $_POST['lat'] : null ));
	$location['lng'] = esc_attr(strip_tags(isset($_POST['lng']) ? $_POST['lng'] : null ));
	update_field( 'field_5362ae02910ff',  $location, $post_id);
	// update_field( 'field_53a9772057ffd',  $_POST['cat'], $post_id);


	$location_field = get_field( 'field_5362ae02910ff', $post_id);


	// if user has uploaded an image file or a video
	if ( $media == 'image' || $media == 'video'):

		//change media type if multiple images uploaded 
		if (count($_FILES['media_upload']['name']) > 1) $media = 'gallery'; 
		update_post_meta( $post_id, "media_type", $media );

		//get the media field to input files to
		$media_field = get_field('field_5362addb9bf86', $post_id);  //looks like this isn't used...


		//$_FILES is the result of the form submit with input type files
		if ( $_FILES ) {
			$files = $_FILES['media_upload'];

			foreach ($files['name'] as $key => $value) {
				if ($files['name'][$key]) {
					$file = array(
						'name'     => $files['name'][$key],
						'type'     => $files['type'][$key],
						'tmp_name' => $files['tmp_name'][$key],
						'error'    => $files['error'][$key],
						'size'     => $files['size'][$key]
					);

		 
					$_FILES = array("upload_attachment" => $file);
		 			
					foreach ($_FILES as $file => $array) {
						$new_upload[] = array('file' => insert_attachment($file,$post_id));						
					}
				}
			}
		}

		//add media to media upload array to acf repeater field
		update_field( 'field_5362addb9bf86',  $new_upload, $post_id);

	endif;


	?>