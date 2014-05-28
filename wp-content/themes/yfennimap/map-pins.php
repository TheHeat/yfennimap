<?php

// Query the pin post-type
// populate the arrays with the *title*, *location*, *media_type* and *facebook-object*

$pins = array();

// echo '<pre>';
// 	print_r($session);
// echo '</pre>';

// The pins
$pin_args = array('post_type' => 'pin','posts_per_page'=>-1, 'orderby' => 'title', 'order' => 'ASC');
$pin_query = new WP_query($pin_args);

if($pin_query->have_posts()):

	while($pin_query->have_posts()):

		// Create a container array for pin information
		$pin = array();

		$pin_query->the_post();

		// Location from ACF Google Maps field
		$location 		= get_field('location');
		$pin['lat']		= $location['lat'];
		$pin['lng'] 	= $location['lng'];
		$pin['title'] 	= get_the_title();
		$pin['wpid']	= get_the_id();

		// Push to the $pins object
		$pins[ get_the_ID() ] = $pin;

	endwhile;

endif;

?>

<script>

	// Create map, geocoder and marker in the global scope
	var map;
	var markers = [];
	var geocoder;
	var bounds;	
	var singlePin;
	// what media type are we adding to the map
	var newPinMedia;
	var newPinLatLng = [];

	// convert $pins from PHP to JSON object
	var pinsMap =  <?php echo json_encode( $pins ); ?>;

	console.log(pinsMap);

function initialize() {

	geocoder = new google.maps.Geocoder();

	var latlng = new google.maps.LatLng(51.825366,-3.019423);

	var mapOptions = {
		center: latlng,
		zoom: 6,
		disableDefaultUI: true,
	};

	map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
	
  	// create the Bounds object
  	bounds = new google.maps.LatLngBounds();

	for (var i in pinsMap) {

		if(!singlePin || singlePin === pinsMap[i].wpid){

			var lat 	= pinsMap[i].lat;
			var lng 	= pinsMap[i].lng;
			var center 	= new google.maps.LatLng(lat, lng);
			var title 	= pinsMap[i].title;
			var wpid	= pinsMap[i].wpid;


			createMarker(center, title, wpid);

			// extend the bounds to include this marker's position
			bounds.extend(center);  

			// resize the map
			map.fitBounds(bounds);

		}

	}
}

function createMarker(center, title, wpid) {

    var marker = new google.maps.Marker({
      position: center,
      title: title,
      map: map,
      // animation: google.maps.Animation.DROP
    });

    google.maps.event.addListener(marker, 'click', function () {
    	map.setCenter(marker.getPosition());
    	singlePin = wpid;
    	console.log(singlePin);

    	jQuery(function($){

    		$('.toolbox').hide('slide', {direction: 'right'}, function(){
    			$('#media-modal').slideDown(function(){
    				$('.modal-content').text(singlePin);
    				$('.modal-close').click(function(){
		    			$('#media-modal').slideUp(function(){
    						$('.modal-content').empty();
    						$('.toolbox').show('slide', {direction: 'right'});
    					});
    				});
    			});
    		});
    		
    	});
  
    });

    markers.push(marker);

  }

// Sets the map on all markers in the array.
function setAllMap(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setAllMap(null);
}

// Shows any markers currently in the array.
function showMarkers() {
  setAllMap(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  markers = [];
}



// Function for searching the map by text string
// TODO limit results to Abergavenny bounds https://developers.google.com/maps/documentation/javascript/reference#Geocoder
function showAddress(addressString) {

	var address = addressString;

	geocoder.geocode( { 'address': address}, function(results, status) {

		if (status == google.maps.GeocoderStatus.OK) {
      	
      		console.log(results[0]);
      		map.setCenter(results[0].geometry.location);
      		
      		if(results[0].geometry.bounds){
      			map.fitBounds(results[0].geometry.bounds);
      		}

      	} else {
      		console.log("Geocode was not successful for the following reason: " + status);
      	}
    });
}

// Create a draggable marker in the centre of the current view and pass its LatLng to a var

function addNewPin(){

	var newMarker = new google.maps.Marker({
	 	position: map.getCenter(),
    	map: map,
    	draggable: true,
    });

	// Define var newPinLatLng as this starting position in case the user doesn't sweat the small stuff!
    newPinLatLng = newMarker.position;
    console.log(newPinLatLng);

    // Pass position of draggable marker to newPinLatLng
    google.maps.event.addListener(newMarker, 'dragend', function () {

    	newPinLatLng = newMarker.getPosition();
    	console.log(newPinLatLng);
    });
}



jQuery(document).ready(function($){

	// Create the .modal-close and .modal-content
	var modalCloser = '<span class="modal-close">&times;</span>';
	var modalContent = '<div class="modal-content"></div>';

	$('#media-modal').prepend(modalCloser, modalContent);

	// Pass media type from the add toolbox link to the newMedia var
	$('.toolbox .tool').click(function(){
		clearMarkers();

		if(!newPinMedia){
			addNewPin();
		}

		newPinMedia = $(this).data('media');
		console.log(newPinMedia);


		// additional toolbox actions
		var siteRoot = '<?php echo esc_url( home_url( '/' ) ); ?>';
		var saveQueryMedia = 'media=' + newPinMedia;
		var saveQueryLat = 'lat=' + newPinLatLng.A;
		var saveQueryLng = 'lng=' + newPinLatLng.k;
		
		var saveNewPin = '<a class="action save" href="' + siteRoot + '/upload-form/?' + saveQueryMedia + '&' + saveQueryLat + '&' + saveQueryLng + '">Save Pin</a>';
		var cancelNewPin = '<span class="action cancel">Cancel</span>';

		$('.toolbox .handle').empty();
		$('.toolbox .handle').prepend('Media Type');
		$('.toolbox .actions').html(cancelNewPin + saveNewPin);
		$('.toolbox .actions').show('slide', {direction: 'left'});
		$('.toolbox .actions .cancel').click(function(){
			$('.toolbox .actions').hide('slide', {direction: 'left'});
			initialize();
		});

	});
});

google.maps.event.addDomListener(window, 'load', initialize);

</script>