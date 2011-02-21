<?php
/*
 * Page Contoller Class for TigerLaundry (2011 Recode Version)
 * 
 * This class provides all the functionality for retrieving information about
 * pages for the website. It digs the list of pages from the database and dumps
 * whatever data is required from that information. It is implemented using the
 * singleton pattern. 
 * 
 * @author Ben Russell (benrr101@csh.rit.edu)
 */
 
 class PageController {
 	// MEMBER VARIABLES -----------------------------------
 	
 	/* The instance of the PageController class */
 	private static $instance;
 	
 	/* The instance of the database connection */
 	private $dbConn;
 	
 	/* Array of the pages of the site */
 	private $pageInformation;
 	
 	// METHODS --------------------------------------------
 	
 	// SINGLETON METHODS ------------------------
 	
 	/**
 	 * Constructs a new instance of this class. It is private so it fits the
 	 * singleton pattern. Also queries the db for a list of all the pages. 
 	 */
 	private function __construct() {
 	 	// Grab an instance of the database connection class
 	 	$this->dbConn = MySQLConnection::getInstance();
 	 	
 	 	// Get all the pages of the website
 	 	$query = "SELECT * FROM pages";
 	 	$this->pageInformation = $this->dbConn->query($query);
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
 	 		self::$instance = new PageController();
 	 	}
 	 	
 	 	// Return it
 	 	return self::$instance;
 	 }
 	 
 	 // PAGE INFORMATION METHODS ----------------
 	 
 	 /**
 	  * Returns the title for the page id given
 	  * 
 	  * @param	string	id	the id of the page
 	  * 
 	  * @return	string	the title of the page if the page is valid
 	  * 				FALSE if the page is invalid
 	  */
 	 public function getPageTitle($id) {
 	 	// Get the page information array
 	 	$page = $this->isValidPage($id);
 	 	if($page == FALSE) {
 	 		return FALSE;
 	 	} else {
 	 		return $page['title'];
 	 	}
 	 }
 	 
 	 /**
 	  * Checks to see if the page id given is in the database
 	  * 
 	  * @param	string	id	the id of the page
 	  * 
 	  * @return	mixed	The array containing the page if valid
 	  * 				FALSE if the page is invalid
 	  */
 	 public function isValidPage($id) {
 	 	// Iterate over the rows in pageInformation
 	 	foreach($this->pageInformation as $value) {
 	 		// If the id matches, return true
 	 		if($value['id'] == $id) {
 	 			return $value;
 	 		}
 	 	}
 	 	
 	 	// We didn't find it
 	 	return FALSE;
 	 }
 }
?>
