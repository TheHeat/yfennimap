jQuery.noConflict();


function setFormFields(form, newPinMedia, callback){

	(function($){
		//Depending on the pin type that was selected, manipulate the form
		// console.log(newPinMedia);
		// Put the form contents into a temporary div
		$('.upload-form-workspace').html(form);

		//make sure everything is shown to start with
		$('.upload-form-workspace.title').show();
		$('.upload-form-workspace.content').show();
		$('.upload-form-workspace.link').show();
		$('.upload-form-workspace.file').show();

		//We usually want the long text to be called 'description'
		$('.upload-form-workspace.content label').html(stringTranslate.fromDescription);

		//conditionally remove some stuff
		switch(newPinMedia){
			case 'text':
				// console.log('works');
				//remove link and files
				$('.upload-form-workspace .link').remove();
				$('.upload-form-workspace .link input').prop('required',false);
				$('.upload-form-workspace .file').remove();
				$('.upload-form-workspace .file input').prop('required',false);
				break;
			case 'image':
				//remove title, link
				$('.upload-form-workspace .title').remove();
				$('.upload-form-workspace .link').remove();
				$('.upload-form-workspace .title input').prop('required',false);
				$('.upload-form-workspace .link input').prop('required',false);

				//Allow multiple files when uploading photos
				$('.upload-form-workspace .file input').prop('multiple', 'true');

				//Add some accepted file types
				$('.upload-form-workspace .file input').prop('accept', 'image/*');

				break;
			case 'video':
				//remove title
				$('.upload-form-workspace .title').remove();
				$('.upload-form-workspace .title input').prop('required',false);
				$('.upload-form-workspace .link input').prop('required',false);
				$('.upload-form-workspace .file input').prop('required',false);

				//Add some accepted file types
				$('.upload-form-workspace .file').remove();
				$('.upload-form-workspace .file input').prop('accept', 'video/*');
				$('.upload-form-workspace .file input').removeAttr('multiple');
				break;
			case 'link':
				//remove title, file chooser
				$('.upload-form-workspace .title').remove();
				$('.upload-form-workspace .file').remove();
				$('.upload-form-workspace .title input').prop('required',false);
				$('.upload-form-workspace .file input').prop('required',false);

				//Change the description field to 'message'

				$('.upload-form-workspace .content label').html('Message');
				break;
			default:
				//something
		}

	})(jQuery);
	// console.log(jQuery('.upload-form-workspace').html());

	// Call the callback once the manipulation is done
	typeof callback === 'function' && callback();
}

/* jQuery AJAX form hanlding thanks to  https://scotch.io/tutorials/submitting-ajax-forms-with-jquery */

var bindAjaxFormHandling = function(){
	(function($){
	    // process the form
	    $('.pin-form').submit(function(event) {

	 		// stop the form from submitting the normal way and refreshing the page
	 		event.preventDefault();

	 		// Create a formData
	 		var formData = new FormData();
	    	
	    	// Add data to it - medial file handling
	    	if( $('#media-hidden').val() === 'image'){
		    	var fileInputElement = document.getElementById("media_upload");
		    	formData.append("async-upload", fileInputElement.files[0]);
		    	formData.append("name", fileInputElement.files[0].name);
		    }

		    // Add the nonce
	    	formData.append("nonce", the_ajax_script.mediaNonce);

	    	// Add other data
	    	formData.append('content', $('#postContent').val());
	    	if(typeof $('#link').val() !== 'undefined') formData.append('link', $('#link').val());
	    	formData.append('year', $('#year-created').val());
	    	formData.append('mediaType', $('#media-hidden').val());
	    	formData.append('lat', $('#lat-hidden').val());
	    	formData.append('lng', $('#lng-hidden').val());

	        // Call out fbPost function to ship the data to Facebook
	        fbPost( FB.getAccessToken(), formData );
	    });
	})(jQuery);
};