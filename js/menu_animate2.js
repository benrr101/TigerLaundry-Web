function slideUnderMenu(parentClass, triggerClass, menuClass) {
	// May need to wrap this in another function (for who knows why...)

	// Define style variables
	var distance = 10;
	var time = 150;
	var hideDelay = 100;

	// Define the tracker
	var beingShown = false;
	var shown = false;
	var hideDelayTimer = null;

	// Set the css
	menuClass.css('opacity', 0);

	// Select the parent and do the following function to it
	$(parentClass).each(function() {
		// Set mouseover behavior
		$(triggerClass, menuClass).mouseover(function() {
			// If the we're moving from the trigger to the popup, don't hide it
			if(hideDelayTimer) {
				clearTimeout(hideDelayTimer);
			}

			// If the menu is being shown or is visible, don't animate again
			if(beingShown || shown) {
				return;
			} else {
				// We are now showing it.
				beingShown = true;

				// Reset the position of the menu and bring it into view
				// Then animate that element
				menuClass.css( {top:35, left:5, display:'block'} )
					 .animate( {top: '-=' + distance + 'px', opacity:1}, time, 'swing',
					 	// When that's done, set the trackers to reflect the changes
					 	function() {
					 		beingShown = false;
					 		shown = true;
					 	}
					);
			}
		});

		// Set mouseout behavior
		$(triggerClass, menuClass).mouseout(function() {
			// If we're already getting ready to hide, don't start hiding again
			if(hideDelayTimer) {
				clearTimeout(hideDelayTimer);
			}

			// Store the timer so it can be cleared by the mouseover
			hideDelayTimer = setTimeout(function() {
				hideDelayTimer = null;

				// Animate the menu
				menuClass.animate( {top: '-=' + distance + 'px', opacity:0}, time, 'swing',
					// When that's done, now we're not showing the menu
					function() {
						shown = false;
						menuClass.css('display', 'none');
					}
				);
			}, hideDelay);
		});
	});
}

// ============================================================================

// When the document is ready, run the functions.

$(document).ready( function() {
	slideUnderMenu( $('#headerBldg', this), $('#headerBldgTrigger', this), $('#headerBldgMenu', this) );
	slideUnderMenu( $('#headerAbout', this), $('#headerAboutTrigger', this), $('#headerAboutMenu', this) );
} );