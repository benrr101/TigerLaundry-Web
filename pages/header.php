<?php
/*
 * Header for TigerLaundry (2011 Recode Version)
 * 
 * This file is the header for the site. It contains the logo, a nav bar, and
 * a jQuery animated list of locations that the site supports.
 * 
 * @author Ben Russell (benrr101@csh.rit.edu) 
 */
?>

				<a href="index.php?id=index"><img id="headerLogo" src="./images/headerLogo.png" alt="Tiger Laundry" /></a>
				<div id="headerHome" class="headerNav"><a href="index.php?page=home"><img src="./images/headerHome.png" alt="Home" /></a></div>				
				<div id="headerDot1" class="headerNav"><img src="./images/headerDot.png" alt="&middot;" /></div>
				<div id="headerBldg" class="headerNav">
					<div id="headerBldgTrigger">
						<a href="index.php?page=locationindex"><img src="./images/headerBldg.png" alt="Buildings" /></a>
						<div id="headerBldgMenu">
							List of buildings goes here
						</div>
					</div>
				</div>
				<div id="headerDot2" class="headerNav"><img src="./images/headerDot.png" alt="&middot;" /></div>
				<div id="headerAbout" class="headerNav">
					<div id="headerAboutTrigger">
						<a href="index.php?page=about"><img src='./images/headerAbout.png' alt='About' /></a>
						<div id="headerAboutMenu">
							<p><a href="index.php?page=aboutgoals">Our Goals</a></p>
							<p><a href="index.php?page=aboutteam">The Team</a></p>
							<p><a href="index.php?page=aboutsource">The Source</a></p>
						</div>
					</div>
				</div>