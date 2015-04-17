jQuery.noConflict();


function setFormFields(newPinMedia){

	(function($){
		//Depending on the pin type that was selected, manipulate the form
		
		//make sure everything is shown to start with
		$('fieldset.title').show();
		$('fieldset.content').show();
		$('fieldset.link').show();
		$('fieldset.file').show();

		//We usually want the long text to be called 'description'
		$('fieldset.content label').html('Description');

		//conditionally hide some stuff
		switch(newPinMedia){
			case 'text':
				// console.log('works');
				//hide link and files
				$('fieldset.link').hide();
				$('fieldset.link input').prop('required',false);
				$('fieldset.file').hide();
				$('fieldset.file input').prop('required',false);
				break;
			case 'image':
				//hide title, link
				$('fieldset.title').hide();
				$('fieldset.link').hide();
				$('fieldset.title input').prop('required',false);
				$('fieldset.link input').prop('required',false);

				//Allow multiple files when uploading photos
				$('fieldset.file input').prop('multiple', 'true');

				//Add some accepted file types
				$('fieldset.file input').prop('accept', 'image/*');

				break;
			case 'video':
				//hide title
				$('fieldset.title').hide();
				$('fieldset.title input').prop('required',false);
				$('fieldset.link input').prop('required',false);
				$('fieldset.file input').prop('required',false);

				//Add some accepted file types
				$('fieldset.file').hide();
				$('fieldset.file input').prop('accept', 'video/*');
				$('fieldset.file input').removeAttr('multiple');
				break;
			case 'link':
				//hide title, file chooser
				$('fieldset.title').hide();
				$('fieldset.file').hide();
				$('fieldset.title input').prop('required',false);
				$('fieldset.file input').prop('required',false);

				//Change the description field to 'message'

				$('fieldset.content label').html('Message');
				break;
			default:
				//something
		}
		
	})(jQuery);
}

/* jQuery AJAX form hanlding thanks to  https://scotch.io/tutorials/submitting-ajax-forms-with-jquery */

var bindAjaxFormHandling = function(){
	(function($){
	    // process the form
	    $('.pin-form').submit(function(event) {

	        // get the form data
	        var body = {
	            'content' : $('#postContent').val(),
	            // 'media' : $('#media_upload').val(),
	            'link' : $('#link').val(),
	            'content' : $('#postContent').val(),
	            'year' : $('#year-created').val(),
	            // 'category' : $('#pin_category').val(),
	            'mediaType' : $('#media-hidden').val(),
	            'lat' : $('#lat-hidden').val(),
	            'lng' : $('#lng-hidden').val()
	        };

	        // Call out fbPost function to ship the data to Facebook
	        fbPost( FB.getAccessToken(), body );

	        // stop the form from submitting the normal way and refreshing the page
	        event.preventDefault();
	    });
	})(jQuery);
};