// Define map in the Global scope
var geocoder = new google.maps.Geocoder();

// Initialize the map
function initialize() {
	
	var mapOptions = {
		center: new google.maps.LatLng(51.825366,-3.019423),
		zoom: 6,
		disableDefaultUI: true,
    };
    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
}


// Funstion to convert a location address to a geocoded object, centre the map and fill the viewport with the location
function codeAddress(addressString) {
	geocoder.geocode( { 'address': addressString}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			console.log(results[0]);

			map.setCenter(results[0].geometry.location);
			map.fitBounds(results[0].geometry.viewport);

    	} else {
    		alert('Geocode was not successful: ' + status);
    	}
  });
}

google.maps.event.addDomListener(window, 'load', initialize);