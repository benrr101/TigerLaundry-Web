<?php
/*
 * Page Contoller Class for TigerLaundry (2011 Recode Version)
 * 
 * This class allows for validation of pageID's passed from _GET requests. It
 * also generates the corresponding pageInfo objects. This class stores an
 * array containing all the data for the pages in the site. It's a kinda silly
 * way to do it, but the site doesn't need the flexibility of a database driven
 * page controller. The class cannot be static just because PHP hasn't 
 * implemented it yet, however, the constructors, cloners, and deconstructors
 * are private and all methods are static, so /basically/ it is a static class.
 * 
 * @author Ben Russell (benrr101@csh.rit.edu)
 */
class PageController {
	// PAGE DATA ================================
	
	private static $pageData;
	
	// METHODS ==================================
	
	// PUBLIC USEFUL METHODS --------------------
	
	/**
	 * Generates a PageInfo object from the given ID. First checks if the class
	 * is initialzed, then throws an exception if the ID is invalid. Then
	 * creates a new PageInfo class and returns it.
	 * 
	 * @throws	Exception	When the ID given is invalid
	 * 
	 * @param	$id		string	The ID of the page to return
	 * 
	 * @return	PageInfo	A PageInfo object representing the page
	 */
	public static function getPageInfo($id) {
		// Check that class is initialized, initialize if it isn't
		if(!self::isInitialized()) { self::initialize(); }
		
		// Blow up if it doesn't exist
		if(!self::isValidPage($id)) { throw new Exception("Invalid ID: $id"); }
		
		// Create the PageInfo object
		$result = new PageInfo($id, self::$pageData[$id]['title'], self::$pageData[$id]['location']);		
		
		return $result;
	}
	
	/**
	 * Checks that the given ID is a valid page
	 * 
	 * @param	$id		string	The ID of the page to check
	 * 
	 * @return	TRUE if the page is valid
	 * 			FALSE if the page is invalid
	 */
	public static function isValidPage($id) {
		// Check that class is initialized, initialize if it isn't
		if(!self::isInitialized()) { self::initialize(); }
		
		// Check that the ID is valid
		return !empty(self::$pageData[$id]); 
	}
	
	/**
	 * Checks that the page pointed to by the given ID actually exists.
	 * 
	 * @param	$id		string	The ID of the page to check
	 * 
	 * @return	TRUE if the page's file exists
	 * 			FAlSE if the page's file does not exist
	 */
	public static function pageExists($id) {
		// Check that the class is initialized, initialize if it isn't
		if(!self::isInitialized()) { self::initialize(); }
		
		// Blow up if it doesn't exist
		if(!self::isValidPage($id)) { throw new Exception("Invalid ID: $id"); }
		
		// Check the page exists
		// TODO: Magic String!
		return file_exists("../pages/" . self::$pageData[$id]['location']);
	}
	
	// PRIVATE METHODS --------------------------
	
	/**
	 * Initializes the static class since variables cannot be initialized in
	 * their declaration in PHP. This method basically stores all the info on
	 * the pages into the pageData variable. To be called when isInitialized
	 * returns false.
	 */
	private static function initialize() {
		// Create the array
		self::$pageData = array();
		
		// Store the information for each page, indexing by it's ID
		self::$pageData['home'] = array('title' => 'Home', 'location' => 'home.php');
		
		self::$pageData['locationindex'] = array('title' => 'Available Locations', 'location' => 'locationIndex.php');
		self::$pageData['location'] = array('title' => '***TBD***', 'location' => 'location.php');
		
		self::$pageData['aboutgoals'] = array('title' => 'Our Goals', 'location' => 'aboutGoals.php');
		self::$pageData['aboutteam'] = array('title' => 'The Team', 'location' => 'aboutTeam.php');
		self::$pageData['aboutsource'] = array('title' => 'Open Source and Licensing', 'location' => 'aboutSource.php');
		self::$pageData['about'] = self::$pageData['aboutgoals']; 		// Alias to aboutgoals
	}
	
	/**
	 * Checks to see if the class has been initialized. Does so by checking if
	 * the array of pageData is empty
	 * 
	 * @return	TRUE if the pageData is not empty
	 * 			FALSE if the pageData is empty
	 */
	private static function isInitialized() {
		return !empty(self::$pageData);
	}
	
	// PRIVATE CONSTRUCTOR/DECONSTRUCTOR/CLONER -
	final private function __construct() {}
	final private function __destruct() {}
	final private function __clone() {}
}
 
?>
