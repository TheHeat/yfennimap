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

		
	//declare a public property in case we need to get something else directly
	//public $graph_object;

	function __construct($pin_id){
		//Get the FB object ID from the WP post
		$fb_object_id = get_post_meta( $pin_id, 'new_fb_object', true); //AHS: probably not the right custom field key

		$this->media_type = get_field('media_type', $pin_id);

		//print_r($fb_object_id);

		//Construct the query
		$query = '/' . $fb_object_id;

		//call the parent constructor
		$request = parent::__construct($_SESSION['fb_session'], 'GET', $query);

		//print_r($_SESSION['fb_session']);

		$response = $request->execute();
	  	// get response
	  	$this->graph_object = $response->getGraphObject();

		//Our pin will have a media type = image if the FB object is either photo or album. 
		//Do a little check to see if we're in an album, and if so set media_type = album
		// if($graph_object->album_id) //This probably isn't right, but I'm offline now and don't have access to the actual property. Find a field that exists in the album object but not the photo object and test for it
		// {
		// 	$media_type = 'album';
		// }

	}
	public function get_url()
	{
		$graph_object = $this->graph_object;
		$fb_media_type = $this->media_type;

		switch ($fb_media_type) {
			
			case 'text':
			case 'link':
				$id_array = explode ( '_' , $graph_object->getProperty('id') );
				$fbid = $id_array[1];
				$userid = $id_array[0];

				return 'https://www.facebook.com/permalink.php?story_fbid='.$fbid.'&id='.$userid;
				break;
			
			case 'video':
				return $graph_object->getProperty('link');
				break;
			
			case 'image':
				return $graph_object->getProperty('link');
				break;
			
			case 'album':
				return $graph_object->getProperty('link');
				break;
			
			default:
				# nothing here...
				break;
		}
	}

	public function get_text()
	{
		$media_type = $this->media_type;
		$graph_object = $this->graph_object;


		switch ($media_type) {
			case 'link':
				return $graph_object->getProperty('message');
				break;
			
			case 'video':
				return $graph_object->getProperty('description');
				break;
			
			case 'image':
				return $graph_object->getProperty('name');
				break;
			
			case 'text':
				return $graph_object->getProperty('message');
				break;
			
			case 'album':
				return $graph_object->getProperty('name');
				break;
			
			default:
				# nothing here...
				break;
		}
	}

	public function get_long_text()
	{
		$media_type = $this->media_type;
		$graph_object = $this->graph_object;

		switch ($media_type) {
			case 'link':
				return null; //no appropriate field
				break;
			
			case 'video':
				return null; //no appropriate field
				break;
			
			case 'image':
				return null; //no appropriate field
				break;
			
			case 'text':
				return null; //no appropriate field
				break;
			
			case 'album':
				return $graph_object->getProperty('description');
				break;
			
			default:
				# nothing here...
				break;
		}
	}

	public function get_images()
	{

		$media_type = $this->media_type;
		$graph_object = $this->graph_object;

		switch ($media_type) {
			case 'link':
				return null; //no appropriate field
				break;
			
			case 'video':
				return null; //no appropriate field
				break;
			
			case 'image':
				return '<div class="fb-post" data-href="'.$graph_object->getProperty('link').'"></div>';
				
				break;
			
			case 'text':
				return null; //no appropriate field
				break;
			
			case 'album':
				//This will need a bit of experimentation as I've almost certainly not got this right while workign offline

				//Make a new API request for the photos that belong to this album
				//Modify the request string
				$query = $query . '/photos';

				$album_object = new FacebookRequest( $_SESSION['fb_session'], 'GET', $query );
				$album_object = $album_object->execute()->getGraphObject();

				//the array that will be returned
				$images = array();

				//loop through the returned object and return a simple array of image URLS

				//** Ive removed the next section because I couldn't get th esyntax right

				// foreach ($album_object['data'] as $image) {
				// 	array_push($images, $image['images'][1]->source) //960 long edge image
				// }

				return $images;
				break;
			
			default:
				# nothing here...
				break;
		}
	}

	public function get_video()
	{
		$media_type = $this->media_type;
		$graph_object = $this->graph_object;

		switch ($media_type) {
			case 'link':
				return null; //no appropriate field
				break;
			
			case 'video':
				return $graph_object->getProperty('embed_html');
				break;
			
			case 'image':
				return null; //no appropriate field
				break;
			
			case 'text':
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

		$media_type = $this->media_type;
		$graph_object = $this->graph_object;

		switch ($media_type) {

			case 'album':
				return null; //I can't remember if there are comments on an album. If there are, this whole switch block can be removed. If not, this switch case will cover that contingency
				break;
			case 'words':
			case 'link':
				$object_link = $graph_object->getProperty('actions')->getProperty('0')->getProperty('link');
				break;
			case 'image':
				$object_link = 'http://localhost/yfennimap/pin/458/';
			default:
				//return $object_link;
				break;
			return '<div class="fb-comments" data-href="' . $object_link . '" data-numposts="5" data-colorscheme="light"></div>';

		}
	}

	public function get_link(){

		$media_type = $this->media_type;
		$graph_object = $this->graph_object;

		switch ($media_type) {
			case 'link':
				return $graph_object->getProperty('link');
				break;
			
			case 'video':
				//might need to build something here in the case that the WP media type is video but it's actually on FB as a link
				return null; //no appropriate field
				break;
			
			case 'image':
				return null; //no appropriate field
				break;
			
			case 'text':
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