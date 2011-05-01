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
$query = "UPDATE locations" .
		"SET " .
		"    locations.inUse = (SELECT COUNT(id) as inUse FROM machines WHERE machines.location=locations.locationID AND machines.status='RED')," .
		"    locations.available = (SELECT COUNT(id) as available FROM machines WHERE machines.location=locations.locationID AND machines.status='GREEN')," .
		"    locations.broken = (SELECT COUNT(id) as broken FROM machines WHERE machines.location=locations.locationID AND machines.status='GREY')";
$dbConn->query($query);
if($dbConn->getLastError()) {
	die('error:Database:' . $dbConn->getLastError());
}

echo "success";

?>
