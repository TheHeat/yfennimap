<?php get_header();

acf_form_head();

$options = array(	
	'post_id' 		=> 'new_pin',
	'field_groups' 	=> array( 7 ),	
	);

acf_form( $options );


get_footer()?>