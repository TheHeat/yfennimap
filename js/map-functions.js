

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
var filterCategory;
if(getQueryStringParams('pinCat')){
	filterCategory = getQueryStringParams('pinCat');
}

// Date sorting
var dateArray = [];
var dateRange = [];
var dateRangeMin;
var dateRangeMax;

var filteredDateRange = [];
var filterStartDate;
var filterEndDate;

// convert $pins from PHP to JSON obj`ect

	// console.log(pinsMap);

function initialize() {

	// flush the date range array just in case
	dateRange = [];

	geocoder = new google.maps.Geocoder();

	var latlng = new google.maps.LatLng(51.825366,-3.019423);

	var mapOptions = {
		center: latlng,
		zoom: 15,
		disableDefaultUI: true,
		zoomControl: true,
		zoomControlOptions: {
        	position: google.maps.ControlPosition.LEFT_TOP,
    	},	
	};

	map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
	
	// create the Bounds object
	bounds = new google.maps.LatLngBounds();

	for (var i in pinsMap) {

			var lat 	= pinsMap[i].lat;
			var lng 	= pinsMap[i].lng;
			var center 	= new google.maps.LatLng(lat, lng);
			var title 	= pinsMap[i].title;
			var wpid	= pinsMap[i].wpid;
			var fbURL	= pinsMap[i].fbURL;
			var icon 	= pinsMap[i].icon;
			var cats	= pinsMap[i].categories;
			var pinYear = pinsMap[i].year;

			// populate the main dateArray
			dateArray.push(pinYear);
			
			//destroy these at the start of the loop
			var inCategory = null;
			var inYearRange = null;

			//Test to see if the pin is in the selected category and
			//within the date range
		
			//Test that the pin is in the selected category using inArray
			if(!filterCategory || (cats.indexOf(filterCategory) > -1)){
				inCategory = true;
			}else{
				inCategory = false;
			}


			//Test that the pin's date is within the date range
			if( pinYear >= filterStartDate && pinYear <= filterEndDate ){
				inYearRange = true;
			}else{
				inYearRange = false;
			}


			if(inYearRange && inCategory){
				createMarker(wpid, center, title, icon, fbURL);
				// console.log(wpid + ':' + pinYear);

			}
 
			// extend the bounds to include this marker's position
			bounds.extend(center); 
			// resize the map
			map.fitBounds(bounds);
	}

	dateRangeMin = Math.min.apply(Math, dateArray);
	dateRangeMax = Math.max.apply(Math, dateArray);

	if(!filterStartDate){
		filterStartDate = dateRangeMin;
	}

	if(!filterEndDate){
		filterEndDate = dateRangeMax;
	}

	// create the date slider
	createDateSlider();


	//If we have a single pin in the query string, open it
	var displayPinId = getParameterByName('p');
	if(displayPinId !== ''){

		singlePinFB = pinsMap[displayPinId]['fbURL'];
		loadPin();
	}


}

function getQueryStringParams(sParam){
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');

	for (var i = 0; i < sURLVariables.length; i++){
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam){
			return sParameterName[1];
		}
	}

}

function createMarker(wpid, center, title, icon, fbURL) {

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
		singlePin = wpid;
		singlePinFB = fbURL;
		// console.log(singlePin);

		loadPin();
  
	});

	markers.push(marker);

  }

function loadPin(){

	var wpMeta;

	// Ajax Data
	var data = {
		action: 'pin_loader',
		pin_id: singlePin,
	};


	// the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
	jQuery.post(
		the_ajax_script.ajaxurl, 
		data,
		function(response){

			// console.log(response);

			var fbURL = singlePinFB;
			var fbPost = '<div class="fb-post" href="' + fbURL + '"></div>';
			var wpMeta = '<div class="wp-meta">' + response.join(' ') + '</div>'; 
			var content = fbPost + wpMeta;

			openModal(content, function(){
				FB.XFBML.parse(document, function(){
					jQuery('.fb_iframe_widget span').position({my: 'center top', at: 'center top', of: '.modal-content'});
				});
			});

		});
 
}

function openModal(content, callback){

	jQuery('.modal-content').empty();

	jQuery('.date').hide();
	jQuery('.toolbox, .filters').hide('slide', {direction: 'right'});

	jQuery('#modal-window').slideDown(function(){

		jQuery('.modal-content').append()

		jQuery('.modal-content-wrapper').position({my: 'center top', at: 'center top+32', of: '#modal-window'});
		jQuery('.modal-content').append(content);
	});


				
	setTimeout(function(){
		// make sure the callback is a function
		if (typeof callback == 'function') { 
			// brings the scope to the callback
			callback.call(this);
		}
	}, 2000);

	jQuery('.modal-close').click(function(){
		jQuery('#modal-window').slideUp(function(){
			jQuery('.modal-content').empty();
			jQuery('.toolbox, .filters').show('slide', {direction: 'right'});
			jQuery('.date').show();
		});
	});

}

// Create categories interface from activeCategories object
function createCategoryLinks(){

	var categoryLinks = [];

	for (var i = activeCategories.length - 1; i >= 0; i--) {
		categoryLinks.push('<span data-link="' + activeCategories[i].slug + '" class="category-link">'+ activeCategories[i].name +'</span>');
	};

	return categoryLinks;
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
		jQuery('.toolbox .handle').show('slide', {direction: 'left'});
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
		
			// console.log(results[0]);
			map.setCenter(results[0].geometry.location);
			
			if(results[0].geometry.bounds){
				map.fitBounds(results[0].geometry.bounds);
			}

		} else {
			// console.log("Geocode was not successful for the following reason: " + status);
		}
	});
}

function createDateSlider(){

	var filteredDateRange = [filterStartDate, filterEndDate];

		jQuery( '#date-range' ).slider({
			range: true,
			min: dateRangeMin - 1,
			max: dateRangeMax + 1,
			values: filteredDateRange,
			slide: function( event, ui ) { 
				jQuery( '#date-label' ).val( ui.values[ 0 ] + ' - ' + ui.values[ 1 ] );
			},
			stop: function( event, ui){
				filterStartDate = Number(ui.values[ 0 ]*1);
				filterEndDate = Number(ui.values[ 1 ]*1);
				initialize();
				// console.log(filterStartDate, filterEndDate);
			},
		});

		jQuery( '#date-label' ).val( jQuery( '#date-range' ).slider( 'values', 0 ) + ' - ' + jQuery( '#date-range' ).slider( 'values', 1 ) );

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
	// console.log(newPinLatLng.k);
	// console.log(newPinLatLng.B);

	// Pass position of draggable marker to newPinLatLng
	google.maps.event.addListener(newMarker, 'dragend', function () {

		newPinLatLng = newMarker.getPosition();
		//console.log(newPinLatLng);
		toolboxLinks(newPinLatLng);
		cancelButton();
	});

}

function toolboxLinks(position){

	var saveQueryMedia = 'media=' + newPinMedia;
	var saveQueryLat = 'lat=' + position.k;
	var saveQueryLng = 'lng=' + position.D;

	var saveNewPin = '<span class="action save">save</span>'
	var cancelNewPin = '<span class="action cancel">Cancel</span>';

		
	resetContent('.toolbox .handle', 'Change Pin Type');

	// $('.toolbox .handle').empty();
		// $('.toolbox .handle').prepend(newPinMediaLabel);
		jQuery('.toolbox .actions').html(cancelNewPin + saveNewPin);


		//Open the upload content form
		jQuery('.action.save').click(function(){

			// Set the visible form fields
			setFormFields(uploadForm, newPinMedia, function(){

				// console.log(jQuery('.upload-form-workspace').html());
				// Inject the form content and open the modal
				openModal( jQuery('.upload-form-workspace').html(), function(){

					// Clear the temporary div
					jQuery('.upload-form-workspace').html('');

					// // Set the visible fields
					// setFormFields(newPinMedia);
					
					//bind form handling
					bindAjaxFormHandling();

					//populate the media type form field
					jQuery('.media-hidden').each(function(){
						jQuery(this).val(newPinMedia);
						// console.log(jQuery(this).val());
					});

					//populate the lat form field
					jQuery('.lat-hidden').each(function(){
						jQuery(this).val(newPinLatLng.k);
						// console.log(jQuery(this).val());
					});

					//populate the lng form field
					jQuery('.lng-hidden').each(function(){
						jQuery(this).val(newPinLatLng.D);
						// console.log(jQuery(this).val());
					});

				});
			});
			
		});
}

jQuery(document).ready(function($){

	if(filterCategory){
		$('.tool.categories').hide();
		$('.tool.clear-categories').show(function(){
			$(this).click(function(){
				$(location).attr('href', './');
			});
		});

	}else{
		$('.tool.clear-categories').hide();
	}


	// Create Category Links
	$('.tool.categories').click(function(){
		openModal(createCategoryLinks(), function(){
			$('.category-link').each(function(){
				// var catQuery = '//' + window.location.host + '?pinCat=' + $(this).data('link');
				var catQuery = './' + '?pinCat=' + $(this).data('link');
				$(this).click(function(){
					$(location).attr('href', catQuery);
				});
			});
		});
	});

	// Open info panel on click
	$('.tool.info').click(function(){
		openModal($('.info-window').html());
	});


	// Login/Out

	$('.avatar-menu').hide('slide', {direction: 'right'});
	$('.avatar-wrapper').click(function(){
			$('.avatar-menu').toggle( 'slide', {direction: 'right'});
		});

	// Create the .modal-close and .modal-content
	var modalContentWrapper = '<div class="modal-content-wrapper"><span class="modal-close">&times;</span><div class="modal-content"></div></div>';

	$('#modal-window').prepend(modalContentWrapper);

	// Pass media type from the add toolbox link to the newMedia var
	$('.toolbox .tool').click(function(){
		clearMarkers();

		// Hide the + button
		$('.toolbox .handle').hide('slide', {direction: 'left'});

		// Check whether the user is switching media types or starting from scratch
		var addingMessage = stringTranslate.markerMessage;

		// Only show the message if the user is opening the 
		if(!newPinMedia){
			alert(addingMessage);
			addNewPin();
		}

		newPinMedia = $(this).data('media');

		// console.log(newPinMedia);
		newPinMediaLabel = $(this).text();
		// console.log(newPinMediaLabel);

		toolboxLinks(newPinLatLng);

		$('.toolbox .actions').show('slide', {direction: 'left'});

		cancelButton();
	});

	// Open modal by default if we're not logged in
	// if($('.avatar').length == 0){
	// 	openModal($('.info-window').html());
	// }

});

google.maps.event.addDomListener(window, 'load', initialize);


