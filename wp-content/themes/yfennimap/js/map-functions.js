

// Create map, geocoder and marker in the global scope
var map;
var markers = [];
var geocoder;
var bounds;

// The pin to be displayed
var singlePin;
var singlePinFB;

// New Pins
// what media type are we adding to the map
var newPinMedia;
// where is the new pin
var newPinLatLng = [];
var saveNewPin;

// convert $pins from PHP to JSON object

	// console.log(pinsMap);

function initialize() {

	geocoder = new google.maps.Geocoder();

	var latlng = new google.maps.LatLng(51.825366,-3.019423);

	var mapOptions = {
		center: latlng,
		zoom: 12,
		disableDefaultUI: true,
		zoomControl: true,	
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
			// var wpid	= pinsMap[i].wpid;
			var fbURL	= pinsMap[i].fbURL;
			var icon 	= pinsMap[i].icon;


			createMarker(center, title, icon, fbURL);

			// extend the bounds to include this marker's position
			bounds.extend(center);  

			// resize the map
			map.fitBounds(bounds);

		}

	}
}

function createMarker(center, title, icon, fbURL) {

    var marker = new google.maps.Marker({
      position: center,
      icon: {
      	url: icon,
      	scaledSize: new google.maps.Size(45, 60),
      },
      title: title,
      map: map,
      // animation: google.maps.Animation.DROP
    });

    google.maps.event.addListener(marker, 'click', function () {
    	map.setCenter(marker.getPosition());
    	// singlePin = wpid;
    	singlePinFB = fbURL;
    	// console.log(singlePin);

    	jQuery(function($){

    		// var gotTools = $('toolbox:visible');
    		// console.log(gotTools);

    		if($('.toolbox').is(':visible')){
    			$('.toolbox').hide('slide', {direction: 'right'}, function(){
					loadPin();   			
    			});
    		}else{
    			loadPin();
    		}
    	});
  
    });

    markers.push(marker);

  }

function loadPin(){

	var fbURL = singlePinFB;
	var fbPost = '<div class="fb-post" data-href="' + fbURL + '" data-width="500"></div>';
	var fbComments = '<div class="fb-comments" data-href="' + fbURL + '" data-width="500"></div>';
	var content = fbPost;

	// console.log(fbURL);
	openModal(content);
}

function openModal(content){

	jQuery('#modal-window').slideDown(function(){
		
		jQuery('.modal-content').append(content);
		FB.XFBML.parse();
		jQuery('.modal-close').click(function(){
			jQuery('#modal-window').slideUp(function(){
		    	jQuery('.modal-content').empty();
		    	jQuery('.toolbox').show('slide', {direction: 'right'});
		    });
		});
	});

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

function cancelButton(){
	// The cancel button
	jQuery('.cancel').click(function(){
		jQuery('.actions').hide('slide', { direction: 'right' });
		
		newPinMedia = null;
		initialize();
	});
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


function resetContent(element, newContent){
	jQuery(element).empty();
	jQuery(element).prepend(newContent);
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
    	toolboxLinks(newPinLatLng);
    	cancelButton();
    });
}

function toolboxLinks(position){

	var saveQueryMedia = 'media=' + newPinMedia;
	var saveQueryLat = 'lat=' + position.k;
	var saveQueryLng = 'lng=' + position.A;
		
	var saveNewPin = '<a class="action save" href="upload-form/?' + saveQueryMedia + '&' + saveQueryLat + '&' + saveQueryLng + '">Add ' + newPinMediaLabel + '</a>';
	var cancelNewPin = '<span class="action cancel">Cancel</span>';

		
	resetContent('.toolbox .handle', 'Change Pin Type');

	// $('.toolbox .handle').empty();
		// $('.toolbox .handle').prepend(newPinMediaLabel);
		jQuery('.toolbox .actions').html(cancelNewPin + saveNewPin);
}

jQuery(document).ready(function($){


	// Login/Out

	$('.avatar-menu').hide('slide', {direction: 'right'});
	$('.avatar-wrapper').click(function(){
			$('.avatar-menu').toggle( 'slide', {direction: 'right'});
		});

	// Create the .modal-close and .modal-content
	var modalCloser = '<span class="modal-close">&times;</span>';
	var modalContent = '<div class="modal-content"></div>';

	$('#modal-window').prepend(modalCloser, modalContent);

	var addLabel = 'Add a pin';

	// Pass media type from the add toolbox link to the newMedia var
	$('.toolbox .tool').click(function(){
		clearMarkers();

		// Check whether the user is switching media types or starting from scratch
		var addingMessage = 'Drag the marker, hit the add button.';

		// Only show the message if the user is opening the 
		if(!newPinMedia){
			alert(addingMessage);
			addNewPin();
		}

		newPinMedia = $(this).data('media');
		console.log(newPinMedia);

		newPinMediaLabel = $(this).text();
		console.log(newPinMediaLabel);

		toolboxLinks(newPinLatLng);

		$('.toolbox .actions').show('slide', {direction: 'left'});

		cancelButton();

	});


});

google.maps.event.addDomListener(window, 'load', initialize);