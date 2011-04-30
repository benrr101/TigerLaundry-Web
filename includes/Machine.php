<?php
/*
 * @author	Ben Russell (benrr101@csh.rit.edu)
 */
 
class Machine {
	
	// CONSTANTS ==========================================
	
	/* string
	 * Different values for the half-width field for the machine 
	 */
	const HALF_FALSE = "FALSE";				// Machine shape is a square
	const HALF_NORTH = "TRUE_NORTH";		// Wide sides are on the north and south sides
	const HALF_EAST  = "TRUE_EAST";			// Wide sides are on the east and west sides
	
	const COLOR_GREEN = "#1BFA4E";
	const COLOR_RED   = "#F73C48";
	const COLOR_GREY  = "#B9B9B9";
	
	// MEMBER VARIABLES ===================================
	
	private $globalID;
	
	private $localID;
	
	private $type;
	
	private $status;
	
	private $timeRemaining;
	
	private $xPos;
	
	private $yPos;
	
	private $half;
	
	// METHODS ============================================
	
	// CONSTRUCTOR ----------------------------------------
	
	public function __construct($gid, $lid, $type, $status, $timeRemaining, $x, $y, $half) {
		$this->globalID = $gid;
		$this->localID = $lid;
		$this->type = $type;
		$this->status = $status;
		$this->timeRemaining = $timeRemaining;
		$this->xPos = $x;
		$this->yPos = $y;
		$this->half = $half;
	}
	
	// DRAW CODE ------------------------------------------
	
	public function getDrawCode() {
		// Build a div with the global id
		$code = "<div id='m{$this->globalID}' ";
		
		// Switch on the different HalfWidth values
		switch($this->half) {
			case self::HALF_FALSE:
				// Bring in the full width class
				$code .= " class='machineFullWidth ";
				break;
			case self::HALF_NORTH:
				// Bring in the North-South half width class
				$code .= " class='machineHalfNorth ";
				break;
			case self::HALF_EAST:
				// Bring in the East-West half width class
				$code .= " class='machineHalfEast ";
		}
		
		// Add in the color as from the status
		$code .= $this->status . "' ";
		
		// Put in the positional styling
		$code .= " style='bottom:{$this->yPos}px; left:{$this->xPos}px;'>";
		
		// Switch on the different types of machines
		// TODO: Make this based on a table/array
		switch($this->type) {
			case "WASH":
				$code .= "<h3>Washer</h3>";
				break;
			case "DRY":
				$code .= "<h3>Dryer</h3>";
				break;
		}
		
		// Put the local ID and close up the 
		$code .= "<h4>{$this->localID}</h4>";
		
		// Add the time remaining
		$code .= ($this->status == Location::STATUS_RED) ? "<h5>{$this->timeRemaining} min</h5>" : "";
		
		// Close up the div
		$code .= "</div>";
		
		// Return it!
		return $code;
	}
	
	// GETTERS --------------------------------------------
	// You know how this goes
	public function getGlobalID() { return $this->globalID; }
	public function getLocalID() { return $this->localID; }
	public function getTypeField() { return $this->type; } 		// getType is a reserverd method.
	public function getStatus() { return $this->status; }
	public function getTimeRemaining() { return $this->timeRemaining; }
}
?>
