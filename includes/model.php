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
 
class Model {
	// ATTRIBUTES -------------------------------
	
	/* The singleton instance of the class */
	private static $instance;
	
	/* Instance of the mysqli database connection */
	private $mysql;
	
	// MEMBER FUNCTIONS -------------------------
	
	// 
	
	/**
	 * Constructs an instance of the model and connects it to the database. It
	 * is private to enable the singleton pattern.
	 * 
	 * @param array $config The configuration variables for use in connecting
	 * to the database
	 */
	private function __construct($config) {
		// Connect to the database
		$this->mysql = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
		if ($this->mysql->connect_errno) {
    		die('Connect Error: ' . $this->mysql->connect_errno);
		}
	}
	
	/**
	 * Returns the singleton instance of the model. Creates an instance if one
	 * does not already exist.
	 * 
	 * @param array $config The configuration variables for use in connecting
	 * to the database
	 *  
	 * @return Model the instance of the Model class
	 */
	public static function getInstance($config) {
		// If there isn't an instance set up, construct one, then return it
		if(!self::$instance) {
			self::$instance = new Model($config);
		}
		
		return self::$instance;
	}
} 
?>
