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
$query = "UPDATE machines SET timeRemaining=timeRemaining-1, status= CASE timeRemaining-1 WHEN 0 THEN 'GREEN' ELSE 'RED' END WHERE timeRemaining > 0";
$dbConn->query($query);
if($dbConn->getLastError()) {
	die('error:Database:' . $dbConn->getLastError());
}

echo "success";

?>
