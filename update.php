<?php
/* Adruino Update Page for TigerLaundry (2011 Recode Version)
 * 
 * @author	Ben Russell (benrr101@csh.rit.edu)
 */
 
// REQUIRED FILES ==========================================================
require_once './includes/configVars.php';
require_once './includes/LogController.php';
require_once './includes/Machine.php';
require_once './includes/MySQLConnection.php';
require_once './includes/LocationController.php';
require_once './includes/Location.php';

// CONSTANTS ===============================================================
// The array of possible requests to the machine
$STATUS_CHANGES = array('START', 'STOP', 'BREAK');

// VERIFY GET REQUEST ======================================================
// Did we get everything we need?
if(empty($_GET['arduID']) || empty($_GET['machineID']) || empty($_GET['locationID']) || empty($_GET['statusChange'])) {
	// Send a log message, then stop.
	LogController::newEntry("E", "Could not process update: All arguments not provided");
	die("error:Argument");
}

// Is the location valid?
if(!LocationController::isValidLocation($_GET['locationID'])) {
	// Send a log message, then stop
	LogController::newEntry("E", "'{$_GET['locationID']}' is not a valid location. Cannot update.");
	die("error:InvalidLocation");
}

// Grab the location
$location = LocationController::getLocation($_GET['locationID']);

// Is the machine valid for the location?
if(!$location->isValidMachine($_GET['machineID'])) {
	// Send a log message
	LogController::newEntry("E", "'{$_GET['machineID']}' is not a valid machine for location '{$_GET['locationID']}'");
	die("error:InvalidMachine");
}

// Verify the status change
if(!in_array($_GET['statusChange'], $STATUS_CHANGES)) {
	// Send a log message then die
	LogController::newEntry("E", "'{$_GET['statusChange']}' is not a valid status change");
	die("error:InvalidChange");
}

// Now grab an instance of the database connection and prepare for QUERY
$dbConn = MySQLConnection::getInstance();

// Sanitize the machineID
$_GET['machineID'] = $dbConn->sanitize($_GET['machineID']); 

// Build the query for each status change
// TODO: Do this with lookups on a table of machine types
switch($_GET['statusChange']) {
	case $STATUS_CHANGES[0]:
		// On start, start the machine
		$query = "UPDATE machines SET timeRemaining= CASE type WHEN 'WASH' THEN 38 ELSE 60 END, status='RED' WHERE id={$_GET['machineID']}";
		break;
	case $STATUS_CHANGES[1]:
		// On stop, stop the machine
		$query = "UPDATE machines SET timeRemaining=0, status='GREEN' WHERE id={$_GET['machineID']}";
		break;
	case $STATUS_CHANGES[2]:
		// On breakage, stop the machine and set the status
		$query = "UPDATE machines SET timeRemaining=0, status='GREY' WHERE id={$_GET['machineID']}";
		break;
}

// Run the query
$dbConn->query($query);
if($dbConn->getLastError()) {
	LogController::LogEntry("E", "Could not update! Error: ". $dbConn->getLastError());
	die("error:Database");
}

// Are we in debug mode?
if($_TLCONFIG['debugMode']) {
	LogController::LogEntry("N", "Updating: ardu={$_GET['arduID']}, location={$_GET['locationID']}, machine={$_GET['machineID']}, {$_GET['statusChange']}");
}

echo("success");
?>
