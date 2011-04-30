<?php
/*
 * Location Contoller Class for TigerLaundry (2011 Recode Version)
 * 
 * The location controller class provides functionality for accessing the
 * database to retrieve information about the locations that are connected to
 * the system. When requested, it generates Location classes to match the
 * information stored on the database. It also provides an array of locations
 * for easy generating of links to the locations. This class is basically
 * implemented as a static class even though PHP doesn't formally allow
 * such things.
 * 
 * @author Ben Russell (benrr101@csh.rit.edu)
 */

class LocationController {
	// MEMBER VARIABLES =========================
	
	/* array
	 * Storage for locations to prevent unecessary database querying
	 */
	private static $locationArray;
	
	// METHODS ==================================
	
	/**
	 * Queries the database for the information needed to construct a Location
	 * object. Once this has been procured, another query is sent to grab all
	 * the machines that this location contains. Then this fully crafted object
	 * is returned to the caller.
	 * 
	 * @throws	Exception	Thrown when the locationID is invalid
	 * 						Thrown when the database is not connected
	 * 
	 * @param	$locationID	string	The ID of the location to grab
	 * 
	 * @return	Location	The location identified by the given LocationID
	 */
	public static function getLocation($locationID) {
		// Grab a connection to the database
		$dbConn = MySQLConnection::getInstance();
		
		// If we're not conncted, blow the fuck up!
		if(!$dbConn->isConnected()) {
			throw new Exception("Database not connected: " . $dbconn->getLastError());
		}
		
		// Sanitize first.
		$locationID = $dbConn->sanitize($locationID);
		
		// Verify the ID
		if(!self::isValidLocation($locationID)) { throw new Exception("'$locationID' is an invalid location!"); }
		
		// It's the real deal, so continue.
		// Build and execute the query to grab the location
		$query = "SELECT * FROM locations WHERE locationID='{$locationID}' LIMIT 1";
		$result = $dbConn->query($query);
		$row = $result[0];
		
		// Build the location
		$location = new Location(
			$row['locationID'],
			$row['longName'],
			$row['status'],
			$row['available'],
			$row['inUse'],
			$row['broken'],
			$row['height'],
			$row['width'],
			($row['doorO'] == Location::DOOR_NONE) ? FALSE : $row['doorX'],
			($row['doorO'] == Location::DOOR_NONE) ? FALSE : $row['doorY'],
			($row['doorO'] == Location::DOOR_NONE) ? FALSE : $row['doorO']
			);
			
		// Add the machines
		$query = "SELECT * FROM machines WHERE location='{$locationID}'";
		$result = $dbConn->query($query);
		foreach($result as $machine) {
			$location->addMachine(
				$machine['id'],
				$machine['localID'],
				$machine['type'],
				$machine['status'],
				$machine['timeRemaining'],
				$machine['x'],
				$machine['y'],
				$machine['halfWidth']
				);
		}	
		
		return $location;
	}
	
	/**
	 * Retreives an array of locations if it has not already been retrieved
	 * from the database. Stores it and returns it.
	 * 
	 * @throws 	Exception	Thrown when the database is not connected
	 * 
	 * @return	array	2D array of the locations
	 * 					[locationID, longName, status]
	 */
	public static function getLocationArray() {
		// Have we already retrieved it?
		if(empty(self::$locationArray)) {
			// Get an instance of the database
			$dbConn = MySQLConnection::getInstance();
			
			// If we're not connected blow the fuck up!
			if(!$dbConn->isConnected()) {
				throw new Exception("Database not connected: " . $dbconn->getLastError());
			}
			
			// Query the database
			$query = "SELECT locationID, longName, status " .
					"FROM locations";
			self::$locationArray = $dbConn->query($query);
		}
		
		// Return the array
		return self::$locationArray;
	}
	
	/**
	 * Searches the array of locations for the given locationID.
	 * 
	 * @param	$locationID	string	The ID of the location to verify
	 * 
	 * @return	bool	TRUE if the locationID was found
	 * 					FALSE if the location was not found
	 */
	public static function isValidLocation($locationID) {
		// Grab the array of the locations
		$rows = self::getLocationArray();
		
		// Iterate over each and try to find the desired locationID
		foreach($rows as $row) {
			if($row['locationID'] == $locationID) {
				return TRUE;
			}
		}  
		
		// If we made it this far, we'll never find it
		return FALSE;
	}
	
	/**
	 * Takes in a status and returns a fully formed image tag pointing
	 * at the specified status icon.
	 * 
	 * @param	string	$status	The status of the location
	 * @return	string	An image tag of the status icon
	 */
	public static function translateIcon($status) {
		// Base for all icons
		$icon = "<img src='./images/icon_{$status}.png' alt='{$status}' />";
		return $icon;
	}
	
	// PRIVATE CONSTRUCTOR/DECONSTRUCTOR/CLONER -
	final private function __construct() {}
	final private function __destruct() {}
	final private function __clone() {}
}

?>
