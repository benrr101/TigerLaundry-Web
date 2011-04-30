<?php
/*
 * Location Class for TigerLaundry (2011 Recode Version)
 * 
 * The location page seeks out the information for a location and generates in
 * a user-friendly map by calling into the Location class provided by the
 * LocationController class. The statistics that have been collected are also
 * displayed on this page.
 * 
 * PLANS FOR FUTURE:
 * AJAX CALLS. I've done it before, so let's do it again
 * 
 * @author Ben Russell (benrr101@csh.rit.edu)
 */

// Check to make sure that we have a location
if(empty($_GET['locationid'])) {
	$error = "No location was provided!";
}

// Check that the given location is valid
if(LocationController::isValidLocation($_GET['locationid'])) {
	$error = "The location '{$_GET['locationid']}' is not a valid location.";
}

// Attempt to grab the location
$location = LocationController::getLocation($_GET['locationid']);
?>
<h1><?= $location->getLongName() ?> -<h1>
<?= $location->getDrawCode() ?>

