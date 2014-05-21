<?php

// Query the pin post-type
// populate the arrays with the *title*, *location*, *media_type* and *facebook-object*

$pins = array();

// The pins
$pin_args = array('post_type' => 'pin','posts_per_page'=>-1, 'orderby' => 'title', 'order' => 'ASC');
$pin_query = new WP_query($pin_args);

if($pin_query->have_posts()):

	while($pin_query->have_posts()):

		// Create a container array for pin information
		$pin = array();

		$pin_query->the_post();

		// Location from ACF Google Maps field
		$location = get_field('location');
		$pin['lat']	= $location['lat'];
		$pin['lng'] = $location['lng'];
		$pin['title'] 	= get_the_title();

		// Push to the $pins object
		$pins[ get_the_ID() ] = $pin;

	endwhile;

endif;

?>

<script>

	// Create map, geocodera and marker in the global scope
	var map;
	var geocoder;
	var bounds;	

	// convert $pins from PHP to JSON object
	var pinsMap =  <?php echo json_encode( $pins ); ?>;

	console.log(pinsMap);

function initialize() {

	geocoder = new google.maps.Geocoder();

	var latlng = new google.maps.LatLng(51.825366,-3.019423);

	var mapOptions = {
		center: latlng,
		zoom: 6,
		// disableDefaultUI: true,
	};

	map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
	
  	// create the Bounds object
  	bounds = new google.maps.LatLngBounds();

	for (var i in pinsMap) {

		var lat = pinsMap[i].lat;
		var lng = pinsMap[i].lng;
		var center = new google.maps.LatLng(lat, lng);
		var title = pinsMap[i].title;

		createMarker(center, title);

		function createMarker(center, title) {

			var marker = new google.maps.Marker({
				position: center,
				title: title,
				map: map,
			});
			
		}

	   // extend the bounds to include this marker's position
	   bounds.extend(center);
	   
	   // resize the map
	   map.fitBounds(bounds);

	}
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



google.maps.event.addDomListener(window, 'load', initialize);

</script>