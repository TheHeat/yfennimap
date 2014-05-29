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

//app ip, app secret, page token, page id
define('page_id', '276813939159864');
define('app_id', '235958703262480');
define('app_secret', 'f608ec2687f60c051396c4d0fabaae06');
define('page_token', 'CAADWmmqwixABAMCTlPK1FrjU1u4ZBZAZB96QVy11bZBCYx7JDU91RwEVPZAQZCwJx9VBIEj9sm1mGePcZAWeilZBNUzRk5bDjZBkbd9EHAUDrd2VHKpwcc3nq1fgFjzykEKnMbzjFjfpUPAHSSKaVf39RMJHGibEvsXnEtnzR6BzDa78Io2MutZA7VaMBmpgPE3F4ZD');


/**
* Takes a pin ID, queries the FB API and returns an object with the appropriate data
*
**/
class FB_Media extends FacebookRequest
/**
   * FB_Media - Extends the SDK Facebook request and executes the query on 
   *   instance contstruction, savings making additional method calls.
   *	Takes the returned object and maps certain properties of that object
   *	to properties of this class so that they can be used in a standardised
   *	way
   *
   * @param string $pin_id The WP post ID that we want to query
   *
   * @return FacebookRequest
   */

{


	function __construct($pin_id){
		//Get the FB object ID from the WP post
		$fb_object_id = get_post_meta( $pin_id, 'fb_object_id'); //AHS: probably not the right custom field key

		//Get the media type so that we know how to handle our returned object. It's more obvious if we get this from WP rather than FB
		$media_type = get_field('media_type', $pin_id);

		//Construct the query
		$query = '/' . $fb_object_id;

		//call the parent constructor
		parent::__construct($_SESSION['fb_session'], 'GET', $query);

		//declare a public property in case we need to get something else directly
		public $graph_object;

		//Execute the request
		$graph_object = $this->Execute()->getGraphObject();

		//Our pin will have a media type = pictures if the FB object is either photo or album. 
		//Do a little check to see if we're in an album, and if so set media_type = album
		if($graph_object->album_id) //This probably isn't right, but I'm offline now and don't have access to the actual property. Find a field that exists in the album object but not the photo object and test for it
		{
			$media_type = 'album';
		}

	}

	public function get_text()
	{
		switch ($media_type) {
			case 'link':
				return $graph_object->message;
				break;
			
			case 'video':
				return $graph_object->description;
				break;
			
			case 'pictures':
				return $graph_object->name;
				break;
			
			case 'words':
				return $graph_object->message;
				break;
			
			case 'album':
				return $graph_object->name;
				break;
			
			default:
				# nothing here...
				break;
		}
	}

	public function get_long_text()
	{
		switch ($media_type) {
			case 'link':
				return null; //no appropriate field
				break;
			
			case 'video':
				return null; //no appropriate field
				break;
			
			case 'pictures':
				return null; //no appropriate field
				break;
			
			case 'words':
				return null; //no appropriate field
				break;
			
			case 'album':
				return $graph_object->description;
				break;
			
			default:
				# nothing here...
				break;
		}
	}

	public function get_images()
	{
		switch ($media_type) {
			case 'link':
				return null; //no appropriate field
				break;
			
			case 'video':
				return null; //no appropriate field
				break;
			
			case 'pictures':
				return $graph_object->source;
				break;
			
			case 'words':
				return null; //no appropriate field
				break;
			
			case 'album':
				//This will need a bit of experimentation as I've almost certainly not got this right while workign offline

				//Make a new API request for the photos that belong to this album
				//Modify the request string
				$query = $query . '/photos';

				$album_object = new FacebookRequest( $_SESSION['fb_session'], 'GET', $query )->execute()->getGraphObject();

				//the array that will be returned
				$images = array();

				//loop through the returned object and return a simple array of image URLS
				foreach ($album_object['data'] as $image) {
					array_push($images, $image['images'][1]->source; //960 long edge image
				}

				return $images;
				break;
			
			default:
				# nothing here...
				break;
		}
	}

	public function get_video()
	{
		switch ($media_type) {
			case 'link':
				return null; //no appropriate field
				break;
			
			case 'video':
				return $graph_object->embed_html;
				break;
			
			case 'pictures':
				return null; //no appropriate field
				break;
			
			case 'words':
				return null; //no appropriate field
				break;
			
			case 'album':
				return null; //no appropriate field
				break;
			
			default:
				# nothing here...
				break;
		}
	}

	public function get_comments(){
		switch ($media_type) {
			
			case 'album':
				return null; //I can't remember if there are comments on an album. If there are, this whole switch block can be removed. If not, this switch case will cover that contingency
				break;
			
			default:
				return $graph_object->comments['data'];
				break;
		}
	}

	public function get_link(){
		switch ($media_type) {
			case 'link':
				return $graph_object->link;
				break;
			
			case 'video':
				//might need to build something here in the case that the WP media type is video but it's actually on FB as a link
				return null; //no appropriate field
				break;
			
			case 'pictures':
				return null; //no appropriate field
				break;
			
			case 'words':
				return null; //no appropriate field
				break;
			
			case 'album':
				return null; //no appropriate field
				break;
			
			default:
				# nothing here...
				break;
		}
	}
	
	public function get_like_button(){
		//need to add like button on to this. A tool on developers.facebook.com exists wher eyou can put in a URL and get it to produce an iframe with a like button in it; however, the button doesn't actually appear to like the page, so this needs some more testing
	}
	
}