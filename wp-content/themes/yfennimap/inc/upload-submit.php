<?php

	//file for submitting pin from form

	//potential form validation
	// if(trim($_POST['postContent']) === '') {
	// 	$post_description_error = 'Please enter some information about your upload.';
	// 	$hasError = true;
	// } else {
	// 	$postContent = trim($_POST['postContent']);
	// }
	
	//Insert Post
	//standard WP info
	$post_title = (isset($_POST['postTitle']) ? $_POST['postTitle'] : null );
	$post_information = array(
		'post_title' => $post_title,
		'post_type' => 'pin',
		'post_status' => 'pending'
	);

	//Create Post
	$post_id = wp_insert_post($post_information);

	//Custom Fields
	update_post_meta( $post_id, "description", esc_attr(strip_tags(isset($_POST['postContent']) ? $_POST['postContent'] : null )) );
	update_post_meta( $post_id, "media_type", $_GET['media'] );
	update_post_meta( $post_id, "link",esc_attr(strip_tags(isset($_POST['link']) ? $_POST['link'] : null )) );
	//get users fb token and save against post
	add_post_meta( $post_id, 'user_fb_token', fb_get_token() );
	
	//get location info from pin placement from url string nd add to acf google map field
	$location['address'] = '';
	$location['lng'] = $_GET['lat'];
	$location['lat'] = $_GET['lng'];
	update_field( 'field_5362ae02910ff',  $location, $post_id);

	$location_field = get_field( 'field_5362ae02910ff', $post_id);

	// if user has uploaded an image file
	if ( $media == 'pictures' || $media == 'video'):

		//change media type if multiple images uploaded 
		if (count($_FILES['media_upload']['name']) > 1) $media = 'gallery'; 
		update_post_meta( $post_id, "media_type", $media );

		//get the media field to input files to
		$media_field = get_field('field_5362addb9bf86', $post_id);

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
	//Message if succesful
	if($post_id) _e('<p> Your Post has been submitted for moderation </p>' ); 

	exit;

	?>