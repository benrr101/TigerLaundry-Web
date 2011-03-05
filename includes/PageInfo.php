<?php
/*
 * Page Information Class for TigerLaundry (2011 Recode Version)
 * 
 * This class stores information for easy passing of page information between
 * PageController and index. It stores the page's title and location along with
 * it's id. These are values are retrieved by getters and stored during
 * construction of the object.
 * 
 * @author Ben Russell (benrr101@csh.rit.edu)
 */
class PageInfo {
	// ATTRIBUTES ===============================
	
	/* string
	 * ID of the page for _GET requests
	 */
	private $id;
	
	/* string
	 * Location of the file for inclusion by the index, excluding the folders
	 */
	private $location;
	
	/* string
	 * Title of the page for the title bar of the browser
	 */
	private $title;
	
	// METHODS ==================================
	
	// CONSTRUCTOR ------------------------------
	
	/**
	 * Creates a new PageInfo object using the information provided.
	 * 
	 * @param	$id			string	ID of the page
	 * @param	$title		string	Browserbar title of the page
	 * @param	$location	string	Location of the inclusion file
	 */
	public function __construct($id, $title, $location) {
		// Store the information
		$this->id = $id;
		$this->title = $title;
		$this->location = $location;
	}
	
	// GETTERS ----------------------------------
	
	/**
	 * Retrieve the ID of the page
	 * 
	 * @return	string	The ID of the page
	 */
	public function getID() {
		return $this->id;
	}
	
	/**
	 * Retrieve the location of the page's file
	 * 
	 * @return	string	The location of the page's file
	 */
	public function getLocation() {
		return $this->location;
	}
	
	/**
	 * Retrieve the title of the page
	 * 
	 * @return	string	The title of the page
	 */
	public function getTitle() {
		return $this->title;
	}
}
 
?>
