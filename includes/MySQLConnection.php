<?php
/*
 * MySQL Connection Class for TigerLaundry (2011 Recode Version)
 * 
 * This class provides all the functionality for connecting to a MySQL database
 * and performing standard tasks with it. This class is implemented in the
 * singleton pattern, so only one connection will exist per script. 
 * 
 * Plans for the Future:
 * - Implement a databaseConnection interface to allow for other databases
 * - Write with support for MySQL Native Driver
 * 
 * @author Ben Russell (benrr101@csh.rit.edu)
 */
 
 class MySQLConnection {
 	// MEMBER VARIABLES -----------------------------------
 	
 	/* The instance of the class */
 	private static $instance;
 	
 	/* The database connection */
 	private $dbConn;
 	
 	/* The last error message received */
 	private $lastError;
 	
 	// METHODS --------------------------------------------
 	
 	// SINGLETON METHODS ------------------------
 	
 	/**
 	 * Constructs a new instance of the MySQLConnection class. So this can be
 	 * singleton, it is private. When called by getInstance, it creates a new
 	 * connection to the database.
 	 */
 	private function __construct() {
 		// Call in the config variables
 		global $_TLCONFIG;
 		
 		// Conn the database into connecting
 		$this->dbConn = new mysqli($_TLCONFIG['db_host'], $_TLCONFIG['db_user'], $_TLCONFIG['db_pass'], $_TLCONFIG['db_name']);
 		
 		// TODO: Blow up if that didn't work
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
 			self::$instance = new MySQLConnection();
 		}
 	 	
 		// Return it
 		return self::$instance;
 	}
 	 
 	// DATABASE METHODS ------------------------
 	 
 	/**
 	 * Returns the last error that occurred
 	 * 
 	 * @return	string	The error message of the last error
 	 * 				NULL if no errors have occurred
 	 */
 	public function getLastError() {
 		return $this->lastError;
 	} 
 	 
 	/**
 	 * Queries the database and returns an array of the records that were 
 	 * returned, if any. Returns TRUE if no records were returned, but the
 	 * query succeeded. Returns FALSE if an error was encountered. 
 	 * 
 	 * @param string 	query 	The query to run through the database
 	 * 
 	 * @return an array containing the records if a SELECT/SHOW/EXPLAIN query 
 	 * 		was successful
 	 * 		TRUE if any other query succeeded
 	 * 		FALSE if the the query failed
 	 */
	public function query($query) {
 	 	// Run the query
 	 	$result = $this->dbConn->query($query);
 	 	
 	 	// Return true/false if we have success or failure
 	 	if($result === TRUE || $result == FALSE) {
 	 		// Store the last error
 	 		if($result == FALSE) {
 	 			$this->lastError = $this->dbConn->error;
 	 		}
 	 		
 	 		return $result;
 	 	}
 	 	
 	 	// Since we have a result set, translate it into an array
 	 	// TODO: If we have Native Driver support, use it!
 	 	$resultSet = array();
 	 	while($row = $result->fetch_assoc()) {
			array_push($resultSet, $row);
		}
 	 	
 	 	// Release the results
 	 	$result->close();
 	 	
 	 	// Return the result set
 	 	return $resultSet;
 	}
 	
 	/**
 	 * Sanitize the provided string using the real_escape_string method
 	 * 
 	 * @param	string	string	The string to sanitize
 	 * 
 	 * @return	string	The sanitized string
 	 */
 	public function sanitize($string) {
 		return $this->dbConn->real_escape_string($string);
 	}
 	
 	// DECONSTRUCTION METHOD --------------------
 	
 	/**
 	 * Deconstructs the object by closing the connection to the db
 	 */
 	public function __destruct() {
 		// Close the dbConn
 		$this->dbConn->close();
 	}
 }
?>
