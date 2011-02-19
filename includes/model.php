<?php
/**
 * Model Class for TigerLaundry (2011 Recode Version)
 * 
 * The model class provides all the functionality required to access the
 * database, grab the information to generate a status map, update the
 * statuses of machines, grab pages, and write log entried. This class is
 * implemented in singleton
 * design pattern.
 * 
 * @author Ben Russell (benrr101@csh.rit.edu) 
 */

// Require the information for connecting to the database
require_once("config_vars.php");
 
class Model {
	// Member Variables ---------------
	
	/* The singleton instance of the class */
	private static $instance;
	
	/* Instance of the mysqli database connection */
	private $mysql;
	
	// Member Functions ---------------
	
	/**
	 * Constructs an instance of the model and connects it to the database. It
	 * is private to enable the singleton pattern.
	 */
	private function __construct() {
		// Connect to the database
		$mysql = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
	}
	
	/**
	 * Returns the singleton instance of the model. Creates an instance if one
	 * does not already exist.
	 * 
	 * @return Model the instance of the Model class
	 */
	public static function getInstance() {
		// If there isn't an instance set up, construct one, then return it
		if(!self::$instance) {
			self::$instance = new Model();
		}
		
		return self::$instance;
	}
} 
?>
