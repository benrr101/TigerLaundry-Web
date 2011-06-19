<?php
/* AJAX Calls for the Location Page for Tiger Laundry (2011 Recode Version)
 * 
 * This page is primarily built to support updating the map in a live
 * setting. 
 * 
 * Plans for the Future:
 * TODO: Support marking as broken
 * TODO: Support text message alerting
 * 
 * @author	Ben Russell (benrr101@csh.rit.edu)
 */

// REQUIRED CLASSES ========================================================
// SE361
require_once "../includes/LogController.php";
require_once "../includes/MySQLConnection.php";
require_once "../includes/configVars.php";
require_once "../includes/LocationController.php";
require_once "../includes/Location.php";
require_once "../includes/Machine.php";

// PARAMETER PARSING =======================================================
// If we got one of these process calls, we're doing good
if(!in_array($_POST['process'], array('update'))) {
	echo("error:InvalidProcess");
	return;
}

// DO EACH COMMAND =========================================================
switch($_POST['process']) {
	case 'update':
		// We're going to generate data for updating the map
		
		// Verify and sanitize the location parameter
		if(empty($_POST['locationID'])) {
			echo("error:Argument:locationID");
			return;
		}
		
		// Get the location
		$location = LocationController::getLocation($_POST['locationID']);
		
		// Grab the JSON update code and return it
		echo($location->getUpdateCode());
		return;
		
		break; 
}
?>

