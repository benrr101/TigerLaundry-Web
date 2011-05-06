<?php
/*
 * Footer for TigerLaundry (2011 Recode Version)
 * 
 * This file is the header for the site. It contains the logo, and a nav bar
 * 
 * @author Ben Russell (benrr101@csh.rit.edu) 
 */
?>

<div id="footer">
	<div id="footerTitle">
		<a href="index.php?page=home"><img id="footerImage" src='./images/footerTitle.png' alt="Tiger Laundry" /></a>
	</div>
	<p id="footerNav">
		<a href="index.php?page=home">Home</a> | <a href="index.php?page=aboutGoals">Goals</a> | <a href="index.php?page=aboutTeam">The Team</a> | <a href="index.php?page=aboutSource">Source Code</a><br />
	<?
	// Output the locations
	$locationArray = LocationController::getLocationArray();
	for($i = 0; $i < count($locationArray); $i++) {
		echo "<a href='index.php?page=location&locationid={$locationArray[$i]['locationID']}'>";
		echo $locationArray[$i]['longName'];
		echo "</a>";
		if($i + 1 < count($locationArray)) { echo " | "; }
	} 
	?>
	</p>
</div>
