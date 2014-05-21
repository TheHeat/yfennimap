<?php
	
	global $post;
	global $wpdb;
	$post_id = $post->ID;

	//echo '<pre>'; print_r(get_post($post_id)); echo'</pre>';
	//echo '<pre>'; print_r(get_post_meta($post_id)); echo'</pre>';


	// WP Variables
	$media = get_post_meta($post_id, 'media_type', true);
	$content = get_post_meta($post_id, 'description', true);

	//FB variables
	$token = 'CAADWmmqwixABAMCTlPK1FrjU1u4ZBZAZB96QVy11bZBCYx7JDU91RwEVPZAQZCwJx9VBIEj9sm1mGePcZAWeilZBNUzRk5bDjZBkbd9EHAUDrd2VHKpwcc3nq1fgFjzykEKnMbzjFjfpUPAHSSKaVf39RMJHGibEvsXnEtnzR6BzDa78Io2MutZA7VaMBmpgPE3F4ZD';
	$edge = 'feed';

	$fb_object = fb_post_on_page($token, $edge, $content);

	return $fb_object;

	if($fb_object) update_post_meta( $post_id, 'description', 'bummed', true );


	//echo '<pre>'; print_r(get_post_custom($post_id)); echo'</pre>';
?>