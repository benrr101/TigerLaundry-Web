// Set up the update script to run every 30 sec
$(document).ready( function() {
	// Update every 60 seconds
	var updateTimer = setInterval("update()",60000);
});

/**
 * This function calls the ajax script to update the page
 */
function update() {
	// Create the spinner box
	spinner = document.createElement("div");
	spinner.setAttribute("id", "spinner");
	spinner.innerHTML = "Updating<img src='./images/spinner.gif' alt='spinny' />";
	document.getElementById('location').appendChild(spinner);
	
	// Grab what location ID this is
	locationValue = document.getElementById("locationID").value;
	
	// Now run the post request
	$.post("./js/location_ajax.php", {process: "update", locationID: locationValue},
		function(data) {
			// Check for errors
			if(data.substring(0, "error:".length) === "error:") {
				// Alert the user and destroy the updateTimer
				alert("Could not automatically update the page!\nSubsequent updates have been disabled.\n" + data);
				clearInterval(updateTimer)
			}
		
			// Process it into a JSON object
			var updateObj = eval(data);
						
			// Iterate over the machines
			for(i = 0; i < updateObj.length; i++) {
				// Grab the machine div ----
				machine = document.getElementById("m" + updateObj[i].id);
				
				// Change the color --------
				classList = machine.className.split(/\s+/);
				for(j = 0; j < classList.length; j++) {
					if(classList[j].match(/^machine[a-zA-Z]*/)) { 
						continue;
					} else {
						// Remove the non-necessary class (ie status)
						$('#' + machine.id).removeClass(classList[j]);
					}
				}
				$('#' + machine.id).addClass(updateObj[i].status);
				
				// Change the time remaining ----
				// Do we have a field for time remaining?
				timeRemaining = document.getElementById(machine.id+'tr') != undefined;
				//alert(machine.getElementsByTagName("h5"));
				if(timeRemaining) {
					// Should we hide the time remaining (machine is now open)
					if(updateObj[i].status != 'RED') {
						// Remove it
						try {
						machine.removeChild(document.getElementById(machine.id + 'tr'));
						} catch(err) { alert(err);}
					} else {
						// Update the time
						document.getElementById(machine.id + 'tr').innerHTML = updateObj[i].timeRemaining + ' min';
					}
				} else {
					// Should we show the time remaining?
					if(updateObj[i].status == 'RED') {
						// Yes, there is time remaining
						tr = document.createElement('h5');
						tr.setAttribute('id', machine.id + 'tr');
						tr.innerHTML = updateObj[i].timeRemaining + ' min';
						machine.appendChild(tr);
					}
					// Otherwise, there's no change
				}
			}
		
			// Destroy the spinner
			locationDiv = document.getElementById('location');
			locationDiv.removeChild(document.getElementById('spinner'));
	});
}