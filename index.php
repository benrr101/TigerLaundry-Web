<?php
/**
 * Index for TigerLaundry (2011 Recode Version)
 * 
 * This file is the index to the website. It loads all the pages and provides
 * all links to different pages in the website
 * 
 * @author Ben Russell (benrr101@csh.rit.edu) 
 */

// Require the configuration variables
require_once "./includes/config_vars.php";

// Require the necessary classes
require_once "./includes/MySQLConnection.php";
require_once "./includes/PageController.php";

// Get instances of the necessary classes
$pageController = PageController::getInstance();

// PARSE GET VALUES -----------------------------
$pageID = (empty($_GET['id'])) ? "index" : $_GET['id'];

// GENERATE PAGE VALUES -------------------------
try {
	$pageTitle = $pageController->getPageTitle($pageID);
	$pageLocation = "./pages/" . $pageController->getPageLocation($pageID);
} catch(Exception $e) {
	// The page is invalid
	$pageTitle = "Page Not Found";
	$pageLocation = "./pages/error.php";
	
	// TODO: Set up 'official' error handling stuff
}

// Check if the file even exists
if(!file_exists($pageLocation)) {
	$pageTitle = "Page Not Found";
	$pageLocation = "./pages/error.php";
	
	// TODO: Set up 'official' error handling stuff
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title><?= $pageTitle ?> - Tiger Laundry</title>
		
		<!-- MetaData -->
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		
		<!-- CSS -->
		<link href="./css/global2.css" rel="stylesheet" type="text/css" />
		
		<!-- JavaScript -->
		
	</head>
	<body>
		<!-- Header -->
		<div id="test"><?= $pageLocation ?></div>
	</body>
</html>