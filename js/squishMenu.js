
/*
* squishMenu 0.1
*
* Copyright 2015, Marc Heatley @marcheatley http://properdesign.rs
* jQuery plugin which adds state classes to the targetted element
*
* Depends on the targetted element containing only .menu-item(s)
* Includes the toggling of .is-open state by clicking a .menu-toggle element that can be anywhere in the DOM
* 
*/

;(function( $ ){


	$.fn.squishMenu = function() {

		container = this;
		var itemsWidth = getItemsWidth();

		// Add up the widths of all the .menu-items
		// We only do it once in the default state
		// because they're apt not to change width when the container is .too-small
		function getItemsWidth(){
			var sum = 0;
			container.find('.menu-item').each( function(){
				var elementWidth = $(this).outerWidth(true);
				sum += elementWidth;
			});

			// Add on some for the #site-branding
			sum += $('#site-branding').outerWidth(true);

			// Add on some for the FB-handling bits
			sum += 240;

			return sum;
		};

		// Set appropriate classes
		function setStates(){

			containerWidth = $(window).width();

			if (itemsWidth <= containerWidth) {
				container.removeClass('too-small');
				container.removeClass('is-open');
			}

			if (itemsWidth > containerWidth) {
				container.addClass('too-small');
			}
		}

		setStates();

		$(window).resize(function() {
			setStates();
		});

		// Click the .menu-toggle to open the menu. Obvs.
		$( '.menu-toggle').click(function(){
			container.toggleClass('is-open');
		});
	}

// Requires jQuery
})( window.jQuery );

// Bind Squishmenu on document ready
(function($){
	$(document).ready(function(){
		$('.site-navigation').squishMenu();
	});
})(jQuery);