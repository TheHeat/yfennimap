<?php

if(function_exists("register_field_group")){

	register_field_group(array (
		'id' => 'acf_map-pin',
		'title' => 'Map Pin',
		'fields' => array (
			array (
				'key' => 'field_537ccb499a819',
				'label' => 'Existing Content?',
				'name' => 'existing_content',
				'type' => 'true_false',
				'message' => '',
				'default_value' => 0,
			),
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
		),
		'location' => array (
			array (
				array (
					'param' => 'page_type',
					'operator' => '==',
					'value' => 'front_page',
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
}

?>