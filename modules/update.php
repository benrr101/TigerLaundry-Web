<?
/*
* Tiger Laundry: Status Updating Script
*
* Design by Benjamin Russell (www.csh.rit.edu/~benrr101)
* Project supported by RIT Residence Halls Association (rha.rit.edu)
*
*/

// Include the logging script
include('./logger.php');

// Change error level to fatal only
error_reporting(E_ERROR);

function update($arduID, $location, $machineID, $change) {
	/*
	* This function runs queries to the mysql db to change the status of a
	* washer/dryer. First we check to see if the variables are set, then
	* we check to see if the data provided is valid. If any of those
	* conditions are not true, die. If the data is valid, then we post to
	* the database to make the changes.
	* Args -
	* $arduID	- int, ID number for the arduino. Not implemented yet,
	* 		but necessary for security later
	* $location	- str, short name of location of the arduion (eg:bcg)
	* $machineID	- str, id of the machine that is changed [relative to
	* 		global id list] (eg: 16)
	* $change	- int, new status of the machine
	* 		[0 = ready, 1 = inuse, 2 = broken]
	*/

	// Check to see if the variables are all set
	if( !isset($arduID) or !isset($location) or !isset($machineID) or !isset($change) ) {
		// If they aren't then log the error and return false
		echo("Failure: All parameters not set.");

		if(!isset($arduID))                         { $notset = 'arduID';   }
		if(!isset($location)  and !isset($notset))  { $notset = 'location'; }
		if(!isset($machineID) and !isset($notset))  { $notset = 'machineID';  }
		if(!isset($change)    and !isset($notset))  { $notset = 'change';   }

		log_entry('e', "Could not update status: {$notset} was not set");

		return(false);
	}

	// Connect to the database, and select tigerlaundry
	$db_handle = mysql_connect("localhost", "tl_ardu", "ardu101");
	$db_select = mysql_select_db("tigerlaundry", $db_handle);

	// Check that for errors
	if(!$db_handle) {
		echo("Failure: Database could not be connected to");
		log_entry('e', "Could not update status: could not connect to db: ".mysql_error());
		return(false);
	}
	if(!$db_select) {
		echo("Failure: TL database could not be selected");
		log_entry('e', "Could not update status: could not select the db: ".mysql_error());
		return(false);
	}

	// Sanitize the input
	$arduID   = mysql_real_escape_string($arduID, $db_handle);
	$location = mysql_real_escape_string($location, $db_handle);
	$machineID  = mysql_real_escape_string($machineID, $db_handle);
	$change   = mysql_real_escape_string($change, $db_handle);

	// Check to see if the values provided are legit ----------------------
	//   Step 1: Does the location exist?
	$locResult = mysql_query("SELECT * FROM location WHERE shortname = '{$location}'", $db_handle);
	if(mysql_affected_rows() != 1) {
		echo("Failure: The location does not exist");
		log_entry('e', "Could not update status: '{$location}' does not exist");
		mysql_close($db_handle);
		return(false);
	}
	//   Step 2: Does the machine exist in the location?
	mysql_query("SELECT * FROM status WHERE location = '{$location}' AND id = '{$machineID}'", $db_handle);
	if(mysql_affected_rows() != 1) {
		echo("Failure: The machine does not exist in the location");
		log_entry('e', "Could not update status: '{$machineID}' does not exist in location '{$location}'");
		mysql_close($db_handle);
		return(false);
	}
	// Great! The input is good.

	// Run the query
	if($change == 1) {
		$query = "UPDATE `status` SET `status` = 1, `time` = IF(`type`=1,36,60) WHERE `status`.`location` = '{$location}' AND `status`.`id` = '{$machineID}'";
	} else {
		$query = "UPDATE `status` SET `status` = {$status} WHERE `status`.`location` = '{$location}' AND `status`.`id` = '{$machineID}'";
	}
	$result = mysql_query($query);

	// Check for errors
	if(!result) {
		echo("Failure: The query failed");
		log_entry('e', "Could not update status: query failed");
		mysql_close($db_handle);
		return(false);
	}

	// ====================================================================
	// Usage stats
	// Update the usage statistics if we just started a load
	if($change == 1) {
		// Temporarily store the day and hour
		$hour = date('H');
		$day  = date('D');

		// Run the query to update the usage statistics
		$query = "UPDATE `usage` SET `{$hour}` = `{$hour}` + 1, `{$day}` = `{$day}` + 1 WHERE `usage`.`shortname` = '{$location}'";
		$result = mysql_query($query);

		// If something other than one row is affected, then there was an error
		if(mysql_affected_rows() != 1) {
			echo("Warning: Usage statistics could not be updated.  <br />\n");
			log_entry('e', "Could not update usage statistics for '{$location}': ".mysql_error());
		}
	}
	
	// ====================================================================
	// Update Location Status
	// Get the number of machines from the location
	$locRow = mysql_fetch_array($locResult);
	$machines = $locRow['machines'];
	
	// Get the rows for the location we updated that are in use
	mysql_query("SELECT * FROM `status` WHERE `location`='{$location}' AND (`status`=1 OR `status`=2)");
	$inUse = mysql_affected_rows();
	
	// If 80% or more are in use/broken, then it's red
	if($inUse * 100 / $machines >= 75) {
		mysql_query("UPDATE `location` SET `status`=2 WHERE `shortname`='{$location}'");
	} elseif($inUse * 100 / $machines >= 50) {
		mysql_query("UPDATE `location` SET `status`=1 WHERE `shortname`='{$location}'");
	} else {
		mysql_query("UPDATE `location` SET `status`=0 WHERE `shortname`='{$location}'");
	}
	
	if(mysql_errno() != 0) {
		echo("Warning: Location status could not be updated. <br />\n");
		log_entry('e', "Could not update location status for '{$location}': ".mysql_error());
	}

	// Done! Close it up and return true
	mysql_close($db_handle);
	echo("Success!");
	return(true);
}

// Call the function!
update($_GET['arduID'], $_GET['location'], $_GET['machineID'], $_GET['change']);
?>
