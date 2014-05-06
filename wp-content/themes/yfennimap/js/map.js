// Define map in the Global scope
var map;

function initialize() {
	Geocoder = new google.maps.Geocoder();
	var mapOptions = {
		center: new google.maps.LatLng(51.825366,-3.019423),
		zoom: 12
    };
    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
}

function coordAddress(addressString) {
	Geocoder.geocode( { 'address': addressString}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
				console.log(results[0].geometry.location);
    	}
  });
}


function codeAddress(addressString) {
	Geocoder.geocode( { 'address': addressString}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);

			var marker = new google.maps.Marker({
				map: map,
				position: results[0].geometry.location
			});
    	} else {
    		alert('Geocode was not successful: ' + status);
    	}
  });
}


google.maps.event.addDomListener(window, 'load', initialize);