<?php

if(function_exists("register_field_group")){

	register_field_group(array (
		'id' => 'acf_map-pin',
		'title' => 'Map Pin',
		'fields' => array (
			array (
				'key' => 'field_537ccb9c9a81a',
				'label' => 'Existing Content URL',
				'name' => 'existing_content_url',
				'type' => 'text',
				'required' => 1,
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_537ccb499a819',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'placeholder' => '',
				// 'prepend' => 'http://',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5362ad319bf85',
				'label' => 'Media Type',
				'name' => 'media_type',
				'type' => 'select',
				'choices' => array (
					'image' => 'Image',
					'video' => 'Video',
					'text' => 'Text',
					'link' => 'Link',
					'gallery' => 'Gallery',
				),
				'default_value' => '',
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_5362ad1d9bf84',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_537ccb499a819',
							'operator' => '!=',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				),
			),
			array (
				'key' => 'field_5362addb9bf86',
				'label' => 'Media',
				'name' => 'media',
				'type' => 'repeater',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_537ccb499a819',
							'operator' => '!=',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				),
				'sub_fields' => array (
					array (
						'key' => 'field_5385e29261758',
						'label' => 'File',
						'name' => 'file',
						'type' => 'file',
						'column_width' => '',
						'save_format' => 'object',
						'library' => 'all',
					),
				),
				'row_min' => '',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Row',
			),
			array (
				'key' => 'field_5371eeb08096b',
				'label' => 'Link',
				'name' => 'link',
				'type' => 'text',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_537ccb499a819',
							'operator' => '!=',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'placeholder' => '',
				// 'prepend' => 'http://',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5362ae02910ff',
				'label' => 'Location',
				'name' => 'location',
				'type' => 'google_map',
				'center_lat' => 51.825,
				'center_lng' => -3.019,
				'zoom' => 14,
				'height' => 400,
			),
			array (
				'key' => 'field_5362ace99bf83',
				'label' => 'Year Created',
				'name' => 'year-created',
				'type' => 'number',
				'default_value' => date('Y'),
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '4',
			),
			array (
				'key' => 'field_5362af0591101',
				'label' => 'FB User ID',
				'name' => 'fb_user_id',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'pin',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));

	register_field_group(array (
		'id' => 'acf_options',
		'title' => 'Options',
		'fields' => array (
			array (
				'key' => 'field_53970c9b6e9ff',
				'label' => 'Page ID',
				'name' => 'page_id',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53970cc16ea00',
				'label' => 'App ID',
				'name' => 'app_id',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53970cd36ea01',
				'label' => 'App Secret',
				'name' => 'app_secret',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53970ce56ea02',
				'label' => 'Page Token',
				'name' => 'page_token',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options-facebook-settings',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));

	register_field_group(array (
		'id' => 'acf_messages',
		'title' => 'Messages',
		'fields' => array (
			array (
				'key' => 'field_53ac8c218fcb0',
				'label' => 'Success Message',
				'name' => 'success_message',
				'type' => 'wysiwyg',
				'instructions' => 'The message displayed on a successful submission. ',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'yes',
			),
			array (
				'key' => 'field_53ac8c218fcb9',
				'label' => 'Failure Message',
				'name' => 'failure_message',
				'type' => 'wysiwyg',
				'instructions' => 'The message displayed on a unsuccessful submission. ',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'yes',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'map-template.php',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));

	register_field_group(array (
	'key' => 'group_5420185eeb03a',
	'title' => 'Homepage Fields',
	'fields' => array (
		array (
			'key' => 'field_5420186b94467',
			'label' => 'Map Steps',
			'name' => 'map_steps',
			'prefix' => '',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'min' => '',
			'max' => 2,
			'layout' => 'table',
			'button_label' => 'Add Row',
			'sub_fields' => array (
				array (
					'key' => 'field_5420187d94468',
					'label' => 'Title',
					'name' => 'title',
					'prefix' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'column_width' => '',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_5420189994469',
					'label' => 'Text',
					'name' => 'text',
					'prefix' => '',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'column_width' => '',
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'basic',
					'media_upload' => 0,
				),
				array (
					'key' => 'field_542018be9446a',
					'label' => 'Image',
					'name' => 'image',
					'prefix' => '',
					'type' => 'image',
					'instructions' => 'Upload a square (1:1) image to represent this item.',
					'required' => 0,
					'conditional_logic' => 0,
					'column_width' => '',
					'return_format' => 'array',
					'preview_size' => 'thumbnail',
					'library' => 'all',
				),
			),
		),
		array (
			'key' => 'field_54213c48e096f',
			'label' => 'Map - Call To Action',
			'name' => 'map_cta',
			'prefix' => '',
			'type' => 'text',
			'instructions' => 'This is the text that links to the map application.',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => 'View the map',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_54213cb4e0970',
			'label' => 'Map - link',
			'name' => 'map_link',
			'prefix' => '',
			'type' => 'post_object',
			'instructions' => 'Which page houses the map?',
			'required' => 0,
			'conditional_logic' => 0,
			'post_type' => array (
				0 => 'page',
			),
			'taxonomy' => '',
			'allow_null' => 0,
			'multiple' => 0,
			'return_format' => 'object',
			'ui' => 1,
		),
		array (
			'key' => 'field_54201a329446b',
			'label' => 'Project Details',
			'name' => 'project_details',
			'prefix' => '',
			'type' => 'wysiwyg',
			'instructions' => 'Describe the project.',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 0,
		),
		array (
			'key' => 'field_54246b01a3294',
			'label' => 'Forgotten Abergavenny Details',
			'name' => 'fafb',
			'prefix' => '',
			'type' => 'wysiwyg',
			'instructions' => 'Describe the Forgotten Abergavenny Page.',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'basic',
			'media_upload' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page_type',
				'operator' => '==',
				'value' => 'front_page',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
));

}

?>