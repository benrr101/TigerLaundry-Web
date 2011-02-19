<?php
/*
* Tiger Laundry: Laundry Location Page (version 2 OOP!)
*
* Design by Benjamin Russell (www.csh.rit.edu/~benrr101)
* Project supported by RIT Residence Halls Association (rha.rit.edu)
*
*/

class room {
	/*
	* This class defines a laundry room object. Each location creates its
	* own room object and contains its own machines.
	*/

	/**
	* Size of the room (in pixels
	*/
	private $height;
	private $width;

	/**
	* Shortname of the location
	*/
	private $name;

	/**
	* An array of the machines in the room
	*/
	private $machines = array();

	/**
	* String containing the code for the door
	*/
	private $door;

	// ====================================================================

	/**
	* This method constructs the object using the input provided. The info
	* not gained is read in from the database
	* @param 	name 	string, containing the shortname of the room
	* @param 	height 	int, height of the room in pixels
	* @param	width	int, width of the room in pixels
	*/
	public function __construct($name, $height, $width) {
		// Store the data
		$this->name   = $name;
		$this->height = $height;
		$this->width  = $width;
	}

	/**
	* This method creates a machine and inserts it into the machine array.
	* Each the id passed will be the index in the array
	* @param	type	string, type of machine (either 'wash' or 'dry')
	* @param	id	int, id # of the machine in the database
	* @param	placex	int, placement from the left (in multiples of 30)
	* @param	placey 	int, placement from bottom (in multiples of 30)
	* @param	topbot	bool, whether or not the machine is stacked.
	* 			if true (default) the machine is full size,
	* 			if false, the machine is half width.
	* @param	orient	bool, whether or not the machine is N/S or E/W
	* 			oriented. If true (default), it's N/S. If false,
	* 			it's E/W oriented.
	*/
	public function addMachine($type, $id, $placex, $placey, $topbot = true, $orient = true) {
		// Store the data
		$tempArray = array("id" => $id,
				   "type" => $type,
				   "placex" => $placex,
				   "placey" => $placey,
				   "topbot" => $topbot,
				   "orient" => $orient);

		// Append it to the object's machine array
		$this->machines[$id] = $tempArray;
	}

	/**
	* This method draws a door of the chosen orientation at the chosen location
	* @param	placex	int, placement from the left (in multiples of 30)
	* @param	placey	int, placement from the bottom (in multiples of 30)
	* @param	orient	int, orientation of baseline of door
	* 			1=left, 2=right, 3=top, 4=bottom
	*/
	public function addDoor($placex, $placey, $orient){
		$placex = $placex * 30;
		$placey = $placey * 30;

		switch($orient) {
		case 1:
			$this->door = "\t\t\t\t\t\t<div id='door' style='left:{$placex}px; bottom:{$placey}px; background-image:url(\"./images/doorL.png\");'>&nbsp;</div>\n";
			break;
		case 2:
			$this->door = "\t\t\t\t\t\t<div id='door' style='left:{$placex}px; bottom:{$placey}px; background-image:url(\"./images/doorR.png\");'>&nbsp;</div>\n";
			break;
		case 3:
			$this->door = "\t\t\t\t\t\t<div id='door' style='left:{$placex}px; bottom:{$placey}px; background-image:url(\"./images/doorT.png\");'>&nbsp;</div>\n";
			break;
		case 4:
			$this->door = "\t\t\t\t\t\t<div id='door' style='left:{$placex}px; bottom:{$placey}px; background-image:url(\"./images/doorB.png\");'>&nbsp;</div>\n";
			break;
		default:
			return false;
		}
	}

	/**
	* This method draws the room using the parameters given. It also creates
	* all the fancy stuff for jQuery and database connections
	*/
	public function drawRoom() {
		// ============================================================
		// Variable & Database Work

		// Counters and such
		$inuse = 0;
		$broken = 0;
		$open = 0;

		// Register the global for locationRows
		global $locationRows;

		// Get access to the database
		$db_handle = mysqli_client::instance();
		$db_handle->connect();
		if(!$db_handle) {
			echo("<div class='errorbox'>\n\t\t\t<p class='error_header'>Database Error -</p>\n\t\t\t<p class='error_content'>A connection to the database could not be made.</p>\n\t\t</div>");
			exit();
		}

		// Get all the machines at this location
		$machineRows = $db_handle->selectQuery("SELECT * FROM `status` where `location` = '{$this->name}'");
		if(!$machineRows) {
			echo("<div class='errorbox'>\n\t\t\t<p class='error_header'>Database Error -</p>\n\t\t\t<p class='error_content'>Querying the database failed. Please try again.</p>\n\t\t</div>");
		}

		// Get the row for the room we're drawing
		$locationRow = false;
		foreach($locationRows as $location) {
			if($location['shortname'] == $this->name) {
				$locationRow = $location;
			}
		}

		//=============================================================
		// Draw the room
		echo("<h1>{$locationRow['longname']} Laundry Room -</h1>\n");
		echo("\t\t\t\t<hr class='headerRule' />\n");

		echo("\t\t\t\t<div id='roomContainer'>\n");
		echo("\t\t\t\t\t<div id='room' style='width:{$this->width}px; height:{$this->height}px;'>\n");
		echo($this->door);

		// Iterate over the machines in the machine array
		foreach($this->machines as $id => $machine) {
			// Generate the values
			$left = $machine['placex'] * 30;
			$bottom = $machine['placey'] * 30;
			// Height and Width based on topbot/orient
			if($machine['topbot'] and $machine['orient']) {
				// Full size
				$machineType = 'machine';
			}
			if(!$machine['topbot'] and $machine['orient']) {
				// Half width N/S
				$machineType = 'machineNS';
			}
			if(!$machine['topbot'] and !$machine['orient']) {
				// Half width E/W
				$machineType = 'machineEW';
			}

			// ==========================================
			// Get information from the database
			foreach($machineRows as $machinea) {
				if($machinea['id'] == $id) {
					// Get time remaining
					$timeRem = $machinea['time'];					

					// Background color based on status of the machine
					if($machinea['status'] == 0) {
						$backgroundColor = "#1BFA4E";	// Open (green)
						$open++;
					}
					if($machinea['status'] == 1) {
						$backgroundColor = "#F73C48";	// In Use (red)
						$inuse++;
					}
					if($machinea['status'] == 2) {
						$backgroundColor = "#B9B9B9";	// Broken (grey)
						$broken++;
					}

					// Local ID
					$localID = $machinea['localid'];

					// Type of machine
					if($machinea['type'] == '1' and $machine['topbot']) {
						$type = 'Washer';
					} elseif($machinea['type'] == '2' and $machine['topbot']) {
						$type = 'Dryer';
					} else {
						$type = '&nbsp;';
					}
				}
			}

			// Put together the style
			$style = "left:{$left}px; bottom:{$bottom}px; background-color:{$backgroundColor};";

			echo("\t\t\t\t\t\t<div id='machine{$id}' class='{$machineType}' style='{$style}'><h2>{$type}</h2><h3>{$localID}</h3>");
			if($timeRem > 0) {
				echo("<h4>". $timeRem . " Min</h4></div>\n");
			} else {
				echo("</div>\n");
			} 
		}

		echo("\t\t\t\t\t</div>\n");
		
		
		// ============================================================
		// Output the Legend
		echo("\t\t\t\t</div>\n");

		// ============================================================
		// Output the statistics
		echo("\t\t\t\t<h2>General Statistics -</h2>\n");
		echo("\t\t\t\t<hr class='header2Rule' />\n");
		echo("\t\t\t\t<div class='tableDiv' style='width:60%'>\n");
		echo("\t\t\t\t\t<table>\n");
		echo("\t\t\t\t\t\t<tr>\n");
		echo("\t\t\t\t\t\t\t<td class='header' style='width:50%'>Total Machines:</td>\n");
		echo("\t\t\t\t\t\t\t<td style='width:50%'>{$locationRow['machines']}</td>\n");
		echo("\t\t\t\t\t\t</tr>\n");
		echo("\t\t\t\t\t\t<tr>\n");
		echo("\t\t\t\t\t\t\t<td class='header'>Machines in Use:</td>\n");
		echo("\t\t\t\t\t\t\t<td>{$inuse}</td>\n");
		echo("\t\t\t\t\t\t</tr>\n");
		echo("\t\t\t\t\t\t<tr>\n");
		echo("\t\t\t\t\t\t\t<td class='header'>Machines Available:</td>\n");
		echo("\t\t\t\t\t\t\t<td>{$open}</td>\n");
		echo("\t\t\t\t\t\t</tr>\n");
		echo("\t\t\t\t\t\t<tr>\n");
		echo("\t\t\t\t\t\t\t<td class='header'>Broken Machines:</td>\n");
		echo("\t\t\t\t\t\t\t<td>{$broken}</td>");
		echo("\t\t\t\t\t\t</tr>\n");
		echo("\t\t\t\t\t</table>\n");
		echo("\t\t\t\t</div>\n");
	}
}

function load_location($lid, $locationArray) {
	/*
	* This function finds the page for the location we're going to load. If
	* the choice is invalid (by checking with the locations table) throw an
	* error.
	* Args -
	* $lid		- str, the short name of the location to load
	* $locationArray- array, all the rows in the table location
	*/

	// =====================================
	// See if the location is valid

	$validLocation = false;
	foreach($locationArray as $location) {
		// If the location is vaid, set the value to true and break
		if($location['shortname'] == $lid) {
			$validLocation = true;
			break;
		}
	}

	// Return false if it's not valid
	if(!$validLocation) {
		echo("<div class='errorbox'>\n\t\t\t<p class='error_header'>Invalid Location -</p>\n\t\t\t<p class='error_content'>The location selected is invalid. Please <a href='index.php?page=bldg'>Click Here</a> to select a valid location.</p>\n\t\t</div>");
		log_entry('e', "An invalid location '{$lid}' was attempted to be loaded");
		return false;
	}

	// ======================================
	// See if a valid file exists

	if(!file_exists("images/locations/{$lid}.php")) {
		echo("<div class='errorbox'>\n\t\t\t<p class='error_header'>Internal Error -</p>\n\t\t\t<p class='error_content'>There is no map file available for this location.</p>\n\t\t</div>");
		log_entry('e', "A map file for '{$lid}' could not be found.");
		return false;
	}

	// ======================================
	// Include the file

	$include = include("images/locations/{$lid}.php");

	// Check to see if the include failed
	if(!$include) {
		echo("<div class='errorbox'>\n\t\t\t<p class='error_header'>Page Load Error -</p>\n\t\t\t<p class='error_content'>The page failed to be loaded. Please try again.</p>\n\t\t</div>");
		log_entry('e', "'{$lid}' could not be loaded by bldg.php");
		return false;
	}

	// ======================================
	// Done! Return.
	return true;

}


// ============================================================================
// See if a location is given
$lid = $_GET['location'];
if(!isset($lid)) {
	// No location was sent, so print the locations in a bulleted list
	echo("\t\t\t\t<div id='leftSide' style='position:relative; float:left; width:60%'>\n");
	echo("\t\t\t\t\t<h1>Select a Laundry Location -</h1>\n");
	echo("\t\t\t\t\t<hr class='headerRule' />\n");
	echo("\t\t\t\t\t<ul>\n");
	foreach($locationRows as $location) {
		echo("\t\t\t\t\t\t<li><a href='index.php?page=bldg&amp;location={$location['shortname']}'>{$location['longname']}</a></li>\n");
	}
	echo("\t\t\t\t\t</ul>\n");
	echo("\t\t\t\t</div>\n");
	/*
	echo("\t\t\t\t<div id='rightSide' style='position:relative; float:right; width:40%'>\n");
	echo("\t\t\t\t\t<img src='./images/mapGreek.png' alt='Greek Laundry Map' />\n");
	echo("\t\t\t\t</div>\n");
	*/
} else {
	// A location was given, so load it up!
	load_location($lid, $locationRows);
}
