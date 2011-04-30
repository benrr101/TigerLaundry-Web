<?php
/*
 * Location Class for TigerLaundry (2011 Recode Version)
 * 
 * This page generates an index of the locations that the are in the database.
 * It displays them in a nice table that is easy to navigate, and also shows
 * the status next to the location.
 * 
 * @author	Ben Russell (benrr101@csh.rit.edu)
 */

/**
 * Generates the code for each of the locations in our table of locations
 * 
 * @param	array	$array	array of locations
 * @return	string	code representing the list of locations
 */
function generateLocationTable($array) {
	// Initialize the table
	$table = "<table style='width:60%; float:center; margin-left:auto; margin-right:auto;'>\n";
	
	// Iterate over each location in the table
	$firstRow = true;
	foreach($array as $location) {
		// Set the widths if it's the first row
		if($firstRow) {
			$table .= "<tr><td style='width:80%'><a href='index.php?page=location&locationid={$location['locationID']}'>{$location['longName']}</a></td><td style='width:20%; text-align:center'>"
			. LocationController::translateIcon($location['status'])
			. "</td></tr>\n";
			$firstRow = false;
		} else {
			$table .= "<tr><td><a href='index.php?page=location&locationid={$location['locationID']}'>{$location['longName']}</a></td><td style='text-align:center'>"
			. LocationController::translateIcon($location['status'])
			. "</td></tr>\n";
		}
	}
	
	// Close out the table
	$table .= "</table>\n";
	
	return $table;
}

// Grab the list of rows
$locationRows = LocationController::getLocationArray();

// Draw it!
?>
<h1>Select a Laundry Location -</h1>
<?= generateLocationTable($locationRows) ?>
