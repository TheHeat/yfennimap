<?php

require_once(get_template_directory() . '/src/Facebook/FacebookSession.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookRedirectLoginHelper.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookRequest.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookResponse.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookSDKException.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookRequestException.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookAuthorizationException.php' );
require_once(get_template_directory() . '/src/Facebook/GraphObject.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookPermissionException.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookClientException.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookOtherException.php' );
require_once(get_template_directory() . '/src/Facebook/GraphSessionInfo.php' );


use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\FacebookPermissionException;
use Facebook\FacebookClientException;
use Facebook\FacebookOtherException;
use Facebook\GraphSessionInfo;

//app ip, app secret, page token, page id
//The page token needs to be permanent. This page explains how to get it http://logicum.co/getting-a-facebook-page-permanent-access-token/
define('page_id', get_field('field_53970c9b6e9ff', 'option'));
define('app_id', get_field('field_53970cc16ea00', 'option'));
define('app_secret', get_field('field_53970cd36ea01', 'option'));
define('page_token', get_field('field_53970ce56ea02', 'option'));

// init app with app id and secret
FacebookSession::setDefaultApplication( app_id, app_secret );

require_once(get_template_directory() . '/inc/fb-get-session.php');
require_once(get_template_directory() . '/inc/fb-get-token.php');
require_once(get_template_directory() . '/inc/fb-login.php');
require_once(get_template_directory() . '/inc/fb-post-on-page.php');
require_once(get_template_directory() . '/inc/fb-media-class.php');
