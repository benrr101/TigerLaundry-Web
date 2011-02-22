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
 	 * Generates the code to create the navigation bar.
 	 * 
 	 * @return 	string	The code for the navbar
 	 */
 	public function getNavCode() {
 		// The resulting code
 		$result = "";
 		
 		// Iterate over the pages
 		for($i = 0; $i < count($this->pageInformation); $i++) {
 			// If it's a parent, output it and find it's children
 			if($this->pageInformation[$i]['parent'] == "none") {
 				// Find the children
 				$children = array();
 				foreach($this->pageInformation as $key2 => $value2) {
 					if($value2['parent'] == $this->pageInformation[$i]['id']) {
 						array_push($children, $key2);
 					}
 				}
 				
				// If there are children, output the trigger box and the children inside it
				if(count($children) > 0) {
 					$result .= "<span id='trigger'>";
 					$result .= "<a href='index.php?id={$this->pageInformation[$i]['id']}'>{$this->pageInformation[$i]['title']}</a>";
 					$result .= "<span id='headerMenu'>";
 					foreach($children as $index) {
 						$result .= "<a href='index.php?id={$this->pageInformation[$index]['id']}' class='subMenu'>{$this->pageInformation[$index]['title']}</a>";
 					}
					$result .="</span></span>";
					
					// Output the dot if there's another
 					if($i + count($children) + 1 < count($this->pageInformation)) {
 						$result .= " &middot; ";
 					}
					
				} else {
					// Otherwise, just output the link
					$result .= "<a href='index.php?id={$this->pageInformation[$i]['id']}'>{$this->pageInformation[$i]['title']}</a>";
					
					// Output the dot if there's another
 					if($i + 1 < count($this->pageInformation)) {
 						$result .= " &middot; ";
 					}
				}
 			}
 		}
 		
 		// Return the result
 		return $result;
 	}
 	
 	/**
 	 * Retrieves the row of information for the given page. Does checking to
 	 * see if the page is valid. Throws an exception if the page is invalid.
 	 * 
 	 * @param	string	id	The id of the page to retrieve
 	 * @throws	Exception	thrown when the page requested is not valid
 	 * 
 	 * @return array	An array of the page's information
 	 */
 	private function getPageArray($id) {
 		// Attempt to grab the index
 		$index = $this->isValidPage($id);
 		
 		// If it's invalid, throw an exception
 		if($this->isValidPage($id) === FALSE) {
 			throw new Exception("Attempting to get information for an invalid page id: {$id}");
 		}
 		
 		// Since it was valid, now we return it
 		return $this->pageInformation[$index];
 	}
 	
 	/**
 	 * Retrieves the location of the page id given. 
 	 * 
 	 * @param	string	id	The id of the page to get the location of
 	 * 
 	 * @return	string	The name of the page's file
 	 */
 	public function getPageLocation($id) {
 		$page = $this->getPageArray($id);
 		return $page['location'];
 	}
 	
 	/**
 	 * Returns the title for the page id given.
 	 * 
 	 * @param	string	id	the id of the page
 	 * 
 	 * @return	string	the Title of the page
 	 */
 	public function getPageTitle($id) {
 		$page = $this->getPageArray($id);
 		return $page['title'];
 	}
 	
 	/**
 	 * Checks to see if the page id given is in the database
 	 * 
 	 * @param	string	id	the id of the page
 	 * 
 	 * @return	int		The index of the page's information array 
 	 *	 				FALSE if the page is invalid
 	 */
 	public function isValidPage($id) {
 		// Iterate over the rows in pageInformation
 		foreach($this->pageInformation as $index => $value) {
 			// If the id matches, return true
 			if($value['id'] == $id) {
 				return $index;
 			}
 		}
 		
 		// We didn't find it
 		return FALSE;
 	}
 }
?>
