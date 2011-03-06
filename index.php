<?php
/**
 * Index for TigerLaundry (2011 Recode Version)
 * 
 * This file is the index to the website. It loads all the pages and provides
 * all links to different pages in the website
 * 
 * @author Ben Russell (benrr101@csh.rit.edu) 
 */

// REQUIRE GLOBALS ------------------------------
require_once "./includes/configVars.php";
require_once "./includes/stdFunctions.php";

// REQUIRE CLASSES ------------------------------
require_once "./includes/PageController.php";
require_once "./includes/PageInfo.php";
require_once "./includes/LogController.php";
require_once "./includes/MySQLConnection.php";

// PARSE THE PAGE ID ----------------------------
$pageID = (empty($_GET['page'])) ? "home" : $_GET['page'];

// If the page is invalid or does not exist, error it up!
if(!PageController::isValidPage($pageID)) {
	// This is an error
	$error = TRUE;
	$errorTitle = "Invalid Page Selection";
	$errorMessage = "'{$pageID}' is not a valid page ID. Please select a page from the navigation bar at the top or bottom of the page.";
	
	// Log the error
	LogController::newEntry(LogController::TYPE_WARN, "'{$pageID}' was requested, but is an invalid page.");
	
	// Set the pageTitle
	$pageTitle = "404 Error";
} else {
	// Grab the PageInfo
	$page = PageController::getPageInfo($pageID);
	
	// Check that the page exists
	if(!PageController::pageExists($pageID)) {
		// This is an error
		$error = TRUE;
		$errorTitle = "Fatal Error: Page Does Not Exist";
		$errorMessage = "The requested page cannot be found.";
		
		// Log the error
		LogController::newEntry(LogController::TYPE_ERROR, "'{$pageID}' is valid, but {$page->getLocation()} does not exist!");
		
		// Set the pageTitle
		$pageTitle = "404 Error";
	} else {
		// Set the correct information
		$pageTitle = $page->getTitle();
		$pageLocation = "./pages/" . $page->getLocation();
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title><?= $pageTitle ?> - Tiger Laundry</title>
		
		<!-- MetaData -->
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		
		<!-- CSS -->
		<link href="./style/global.css" rel="stylesheet" type="text/css" />
		
		<!-- JavaScript -->
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/menu_animate.js"></script> 
	</head>
	<body>
		<div id="container">
			<!-- Header -->
			<div id="header">
				<? require_once "./pages/header.php"; ?>
			</div>
			
			<!-- Content -->
			<div id="content">
				<?
				// Output the error message if there is an error
				if(isset($error) && $error) {
					drawError($errorTitle, $errorMessage);
				} else {
					require_once $pageLocation;
				} 
				?>
			</div>
		
			<!-- Footer -->
			<div id="footer">
				<? require("./pages/footer.php"); ?>
			</div>
		</div>
	</body>
</html>