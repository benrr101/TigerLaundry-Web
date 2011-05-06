<?php
/*
 * Standard Function for TigerLaundry (2011 Recode Version)
 * 
 * This file contains all sorts of handy functions that can be used globally
 * in the site.
 * 
 * @author	Ben Russell (benrr101@csh.rit.edu)
 */
 
/**
 * Draws an error box following the styles of the website. It DOES utilize
 * echo's, so wherever this is called, there will be code written!
 * 
 * @param	$errorTitle		string	The title of the error
 * @param	$errorMessage	string	The message of the error
 */
function drawError($errorTitle, $errorMessage) {
	// Build the errorBox code
	$code = "<div class='errorBox'>" .
			"<table><tr>" .
				"<td class='errorLeft'><img src='./images/error_icon.png' alt='Error!' /></td>" .
				"<td class='errorRight'><h2>" . $errorTitle . ":</h2><p>" . $errorMessage . "</p></td>" .
			"</tr></table>" .
			"</div>";
	
	// Print it!
	echo $code;
}
?>
