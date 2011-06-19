<?php
/*
 * Location Class for TigerLaundry (2011 Recode Version)
 * 
 * The location class provides a container for machine objects and information
 * specific to a given location. This also contains functionality for drawing
 * the map of the location. Functionality for retreiving statistics is also
 * included. If power consumption is built into the system, there will be
 * functionality for that in here.
 * 
 * @author Ben Russell (benrr101@csh.rit.edu)
 */

class Location {
	// CONSTANTS ==========================================
	
	/* string
	 * Different orientations of the door.
	 * The direction is which side the door is 'attached' to
	 */
	const DOOR_ORIENT_LEFT = "LEFT";
	const DOOR_ORIENT_RIGHT = "RIGHT";
	const DOOR_ORIENT_TOP = "TOP";
	const DOOR_ORIENT_BOTTOM = "BOTTOM";
	const DOOR_NONE = "NONE";
	
	/* string
	 * The different statuses of the location given by it's color
	 */
	const STATUS_RED = "RED";
	const STATUS_YELLOW = "YELLOW";
	const STATUS_GREEN = "GREEN";
	const STATUS_GREY = "GREY";
	
	// MEMBER VARIABLES ===================================
	
	/* int
	 * The x coordinate of the door in pixels
	 */
	private $doorX;
	
	/* int
	 * The y coordinate of the door in pixels
	 */
	private $doorY;
	
	/* string from enum
	 * The orientation of the door
	 */
	private $doorO;
	
	/* int
	 * The height of the location in pixels 
	 */
	private $height;
	
	/* string
	 * The unique identifier for the location
	 */
	private $locationID;
	
	/* string
	 * The expanded name of the location
	 */
	private $longName;
	
	/* int
	 * The total number of machines that are part of this location
	 */
	private $machines;
	
	/* array<Machine>
	 * Storage for the machines that this location owns
	 */
	private $machinesArray;
	
	/* int
	 * The number of machines that are broken at this location
	 */
	private $machinesBroken;
	
	/* int
	 * The number of machines in use at this location
	 */
	private $machinesInUse;
	
	/* int
	 * The number of machines that are open at this location
	 */
	private $machinesOpen;
	
	/* string from enum
	 * The status of the location given by its color
	 */
	private $status;
	
	/* int
	 * The width of the location in pixels
	 */
	private $width;
	
	// METHODS ============================================
	
	// CONSTRUCTOR ----------------------------------------
	
	/**
	 * Constructor for the location class. Takes in a shit-ton of information
	 * and stores it. We also initialize the list of machines.
	 */
	public function __construct($id, $longName, $status, $available, $inUse, $broken, $height, $width, $doorX=FALSE, $doorY=FALSE, $doorO=FALSE) {
		// Store it
		$this->locationID = $id;
		$this->longName   = $longName;
		$this->status     = $status;
		$this->machinesInUse = $inUse;
		$this->machinesOpen  = $available;
		$this->machinesBroken= $broken;
		$this->height = $height;
		$this->width  = $width;
		$this->doorX  = $doorX;
		$this->doorY  = $doorY;
		$this->doorO  = $doorO;
		
		// Initialize it
		$this->machinesArray = array();
	}
	
	// MACHINE MANIPULATION ===============================
	
	/**
	 * Calls the constructor for a new machine. Once it has been constructed,
	 * it is added to the array of machines.
	 * @params	The information from the database
	 */
	public function addMachine($gid, $lid, $type, $status, $timeRemaining, $x, $y, $half) {
		$machine = new Machine($gid, $lid, $type, $status, $timeRemaining, $x, $y, $half);
		array_push($this->machinesArray, $machine);
	}
	
	/**
	 * Checks that the given global id for a machine is contained within this
	 * location. Returns a boolean saying so.
	 * 
	 * @param	int	$gid	The global id of the machine
	 * @return	bool		True if the machine is in this location
	 * 						False otherwise
	 */
	public function isValidMachine($gid) {
		// Search the machine array for the given id
		foreach($this->machinesArray as $machine) {
			if($machine->getGlobalID() == $gid) {
				return true;
			}
		}
		
		// If we make it this far, the machine isn't in this location
		return false;
	}
	
	// DRAW IT MOTHER FUCKER ==============================
	// Note to self: don't listen to gangsta rap while coding
	
	public function getDrawCode() {
		// Start off by initializing the output
		$code = "";
		
		// Draw the box for the location
		$code .= "<div id='location' style='height:{$this->height}px; width:{$this->width}px;'>\n";
		
		// Iterate over all the machines and draw them
		foreach($this->machinesArray as $machine) {
			$code .= "\t" . $machine->getDrawCode() . "\n";
		}
		
		// Draw the door, if there is one
		if($this->doorO != self::DOOR_NONE) {
			$code .= "\t<div id='door' class='door' style='bottom:{$this->doorY}px; left:{$this->doorX}px'><img src='./images/door_{$this->doorO}.png' alt='door' /></div>\n";
		}
		
		$code .= "</div>\n"; 
		
		return $code;
	}
	
	/**
	 * Generates a JSON object representing each machine in the location. This
	 * enables the caller to update the status of the machines.
	 * 
	 * @return	JSONString	A JSON object with each machine in the location.
	 */
	public function getUpdateCode() {
		// JSON
		$code = "[\n";
		
		// JSON for each machine
		$machineData = array();
		foreach($this->machinesArray as $machine) {
			/*$machineIDs[] = $machine->getGlobalID();
			$status[] = $machine->getStatus();
			$timeRemaining[] = $machine->getTimeRemaining();*/
			$data = "{ id: {$machine->getGlobalID()},";
			$data .= " status: \"{$machine->getStatus()}\",";
			$data .= " timeRemaining: {$machine->getTimeRemaining()} }";
			$machineData[] = $data;
		}
		
		// Add the data to the json
		$code .= implode(",\n", $machineData);
		
		// Close it up and return it
		$code .= "]";
		
		return $code;
	}
	
	// GETTERS ============================================
	
	// We'll do these in one crazy huge batch. No comments. You get what's happening
	public function getLocationID()     { return $this->locationID; }
	public function getLongName()       { return $this->longName; }
	public function getMachines()       { return $this->machines; }
	public function getMachinesBroken() { return $this->machinesBroken; }
	public function getMachinesInUse()  { return $this->machinesInUse; }
	public function getMachinesOpen()   { return $this->machinesOpen; }
	public function getStatus()         { return $this->status; }
}

?>
