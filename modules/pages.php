<?php
/*
* Tiger Laundry: PHP Page Getter
*
* Design by Benjamin Russell (www.csh.rit.edu/~benrr101)
* Project supported by RIT Residence Halls Association (rha.rit.edu)
*
*/

/*
* Essentially, this file is like my old style pagelist.php files. Only this
* time we're working with functions to do it.
*/

// If no page is given, set it to the default
if(!isset($_GET['page'])) {
	$page = 'home';
} else {
	$page = $_GET['page'];
}

// List of pages and their locations
$pageList = array(
	"home" => "pages/home.php",
	"about" => "pages/about.php",
	"bldg" => "pages/bldg.php",
	"tester" => "pages/tester.php"
	);

// List of pages and their titles
$titleList = array(
	"home"  => "Tiger Laundry",
	"about" => "About - Tiger Laundry",
	"bldg"  => "Laundry Room Status - Tiger Laundry",
	"tester"=> "Style Tester"
	);

/**
* This function checks for a page in the pagelist array. If the page exists
* within the list, it is included. If it fails to be included, false is
* returned and and an error box is printed. If the page chosen does not exist
* then an error is printed as well.
* 
* @param	page		the page that the user has requested
* @param	pagelist	the list of pages => location
* @param	default		page to load when no page is given
* @param	locationRows	necessary for inclusion page to have access to
* 				this array
*
* @return	true if success, false if failure
*/
function getPage($page, $pagelist, $locationRows) {

	// Does the page exist in the array?
	if(array_key_exists($page, $pagelist)) {
		// Include the file or exit with an error message
		$include = include($pagelist[$page]);
		if(!$include) {
			// The page wasn't included, so fail
			echo("<div class='errorbox'>\n\t\t\t<p class='error_header'>Page Request Error -</p>\n\t\t\t<p class='error_content'>The requested page failed to load. Please try again.</p>\n\t\t</div>");
			return false;
		}
		return true;
	} else {
		// The page doesn't exist in the list, so fail
		echo("<div class='errorbox'>\n\t\t\t<p class='error_header'>Page Request Error -</p>\n\t\t\t<p class='error_content'>The requested page does not exist. Please try again.<br />\n\t\t\t Please <a href='index.php?page={$pagelist['default']}'>click here</a> to return to the home page.</p>\n\t\t</div>");
		return false;
	}
}

/**
* This function checks for a page in the titleList array. If the page exists in
* the list, then the title of that page is returned. If the page does not exist,
* then a 404 is returned.
*
* @param	page		the page that the user has requested
* @param	titleList	the list of pages => title
* @param	default		page to load when no page is given
*
* @return	the name of the page
*/
function getTitle($page, $titleList) {
	// Does it exist in the array?
	if(array_key_exists($page, $titleList)) {
		// set the title
		return $titleList[$page];
	} else {
		return "404 - Tiger Laundry";
	}
}

?>