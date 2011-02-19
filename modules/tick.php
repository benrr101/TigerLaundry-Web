<?php
/**
* This script decrements all the machines that have time remaining. Then it
* changes the status of all machines to open that have 0 time remaining.
* @author	Benjamin Russell (iota.csh.rit.edu)
*/

// Connect to the database
$db_handle = mysql_connect("localhost", "tl_ardu", "ardu101");

// Select the db
mysql_select_db("tigerlaundry", $db_handle);

// Query it.
mysql_query("UPDATE `status` SET `time` = `time` - 1 WHERE `time` > 0") or die(mysql_error());
mysql_query("UPDATE `status` SET `status` = 0 WHERE `time` = 0 AND `status` = 1") or die(mysql_error());

// Do we need to change the location's status?
$locResult = mysql_query("SELECT * FROM `location`");
while($locRow = mysql_fetch_array($locResult)) {
	$inUseResult = mysql_query("SELECT * FROM `status` WHERE `location`='{$locRow['shortname']}' AND (`status`=1 OR `status`=2)");
	$inUse = mysql_affected_rows();
	
	if($inUse * 100 / $locRow['machines'] >= 75) {
		mysql_query("UPDATE `location` SET `status`=2 WHERE `shortname`='{$location}'");
	} elseif($inUse * 100 / $locRow['machines'] >= 50) {
		mysql_query("UPDATE `location` SET `status`=1 WHERE `shortname`='{$location}'");
	} else {
		mysql_query("UPDATE `location` SET `status`=0 WHERE `shortname`='{$location}'");
	}
}
?>

 
