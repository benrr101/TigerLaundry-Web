<?php
/* Ticking Script for TigerLaundry (2011 Recode Version)
 * 
 * This script is called by cron every minute of every day to subtract a minute
 * from every machine that is running. It also changes the status of machines
 * when their time goes to zero.
 * 
 * @author	Ben Russell (benrr101@csh.rit.edu)
 */

// REQUIRED CLASSES ========================================================
require_once './includes/configVars.php';
require_once './includes/MySQLConnection.php';

// Conn the database
$dbConn = MySQLConnection::getInstance();

// Run the mighty query
$query = "UPDATE machines SET status=IF(timeRemaining-1=0,'GREEN', 'RED'), timeRemaining=timeRemaining-1 WHERE timeRemaining > 0";
$dbConn->query($query);
if($dbConn->getLastError()) {
	die('error:Database:' . $dbConn->getLastError());
}

// Now update the locations with the number of broken, open, and inuse machines
// MOST AWESOME QUERY EVER
$query = "UPDATE locations SET locations.inUse = ( SELECT COUNT( id ) AS inUse
 FROM machines
 WHERE machines.location = locations.locationID
 AND machines.status = 'RED' ) , locations.available = (
 SELECT COUNT( id ) AS available
 FROM machines
 WHERE machines.location = locations.locationID
 AND machines.status = 'GREEN' ) , locations.broken = (
 SELECT COUNT( id ) AS broken
 FROM machines)";
$dbConn->query($query);
if($dbConn->getLastError()) {
	die('error:Database:' . $dbConn->getLastError());
}

echo "success\n";

// HAX: Our failsafe contingency
// Generate random number between 1 and 15
$number = rand(1,15) % 15;
echo($number . "\n");
if($number == 8 || $number == 2) {
	$machine = rand(1,3) % 3;
	
	// Only start if off now
	$result = $dbConn->query("SELECT status FROM machines WHERE id=" . (28-$machine));
	if($result[0]['status'] == "GREEN" || $result[0]['status'] == "GREY") {
		$machine = rand(1,3);
		echo("Starting machine:$machine");
		$response = http_get("http://iota.csh.rit.edu/laundryview2/update.php?arduID=bagofdicks&machineID=". (28-$machine) . "&locationID=per&statusChange=START");	
	}
}

?>
