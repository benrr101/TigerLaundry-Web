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

// GENERATE PAGE VALUES -------------------------
$title = $pageController->getPageTitle($_GET['id']);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title><?= $title ?></title>
		
		<!-- MetaData -->
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		
		<!-- CSS -->
		<link href="./css/global2.css" rel="stylesheet" type="text/css" />
		
		<!-- JavaScript -->
		
	</head>
	<body>
		<!-- Header -->
		<div id="test">Fuck Bitches!</div>
	</body>
</html>