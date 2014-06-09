<?php

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

function fb_logout(){

	//If logout = true, then destroy the FB session
	if(isset($_GET['logout'])){
		unset($_SESSION['fb_session']);
	}
}