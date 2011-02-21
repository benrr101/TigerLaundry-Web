<?php
/*
 * Log Contoller Class for TigerLaundry (2011 Recode Version)
 * 
 * This file defines the LogController class that provides functionality for
 * logging. Errors should be logged through this class. This class is also
 * implemented as a singleton class.
 * 
 * @author Ben Russell (benrr101@csh.rit.edu)
 */
 
 class LogController {
 	// CONSTANTS ------------------------------------------
 	
 	/* Error entry type */
 	const ERROR_TYPE = "E";
 	
 	/* Warning entry type */
 	const WARN_TYPE = "W";
 	
 	/* Notic entry type */
 	const NOTICE_TYPE = "N";
 	
 	// MEMBER VARIABLES -----------------------------------
 	
 	/* The instance of the class */
 	private static $instance;
 	
 	/* The database connection */
 	private $dbConn;
 	
 	// METHODS --------------------------------------------
 	
 	// SINGLETON METHODS ------------------------
 	
 	/**
 	 * Constructs a new instance of the MySQLConnection class. So this can be
 	 * singleton, it is private. When called by getInstance, it creates a new
 	 * connection to the database.
 	 */
 	private function __construct() {
 	 	// Grab an instance of the database connection class
 	 	$this->dbConn = MySQLConnection::getInstance();
 	 }
 	 
 	 /**
 	  * Would create a clone of the instance, however for singleton, we need
 	  * to make it private. Plus we'll never use it, so it doesn't need a body
 	  */
 	private function __clone() {}
 	 
 	/**
 	 * Returns the only instance of the class. Creates one if it does not
 	 * already exist.
 	 * 
 	 * @return	the instance of the class
 	 */
 	public static function getInstance() {
 		// Do we need to create an instance?
 		if(!self::$instance) {
 			self::$instance = new LogController();
 		}
 		
 		// Return it
 		return self::$instance;
 	}
 	
 	// LOGGING METHODS --------------------------
 	
 	/**
 	 * Writes an log entry to the database of log entries
 	 * 
 	 * @param	string	message	The message of the entry (eg. what broke)
 	 * @param	string	type	The type of the error (use the constants)
 	 */
 	public function addEntry($message, $type) {
 		// Sanitize the input
 		$message = $this->dbConn->sanitize($message);
 		$type = $this->dbConn->sanitize($type);
 		
 		// Run the query
 		$result = $this->dbConn->query("INSERT INTO `log` (type, message, date) VALUES ('$type', '$message', NOW())");
 		
 		// Check for errors
 		if($result === FALSE) {
 			throw new Exception("Log entry failed due to failed database query: {$this->dbConn->getLastError()}");
 		}
 	}
 }
?>
