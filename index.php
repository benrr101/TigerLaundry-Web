<?php
/**
 * Configuration file for Tiger Laundry (2011 Recode Version)
 * 
 * This file is the index to the website. It loads all the pages and provides
 * all links to different pages in the website
 * 
 * @author Ben Russell (benrr101@csh.rit.edu) 
 */

// Require the configuration variables
require_once "./includes/config_vars.php";

// Require the model and create an instance of it
require_once "./includes/model.php";
$model = Model::getInstance($config);

// Require the header
require_once "./includes/header.php";

// Require the footer
require_once "./includes/footer.php";
?>
