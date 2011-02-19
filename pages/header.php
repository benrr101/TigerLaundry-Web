<?
/*
* Tiger Laundry Design 5: Header
*
* Design by Benjamin Russell (www.csh.rit.edu/~benrr101)
* Project supported by RIT Residence Halls Association (rha.rit.edu)
*
*/
?>
<div id="header">
				<a href="index.php?page=home"><img id="headerLogo" src="./images/headerLogo.png" alt="Tiger Laundry" /></a>
				<div id="headerHome" class="headerNav">
					<a href="index.php?page=home"><img src="./images/headerHome.png" alt="Home" /></a>
				</div>
				<div id="headerDot1" class="headerNav">
					<img src="./images/headerDot.png" alt="" />
				</div>
				<div id="headerBldg" class="headerNav">
					<div id="headerBldgTrigger">
						<a href="index.php?page=bldg"><img src='./images/headerBldg.png' alt='Buildings' /></a>
						<div id="headerBldgMenu">
<? // Print all the locations in this list
  foreach($locationRows as $location) {
	echo("\t\t\t\t\t\t\t<p><a href='index.php?page=bldg&amp;location={$location['shortname']}'>{$location['longname']}</a></p>\n");
  }
?>
						</div>
					</div>
				</div>
				<div id="headerDot2" class="headerNav">
					<img src="./images/headerDot.png" alt="" />
				</div>
				<div id="headerAbout" class="headerNav">
					<div id="headerAboutTrigger">
						<a href="index.php?page=about"><img src='./images/headerAbout.png' alt='About' /></a>
						<div id="headerAboutMenu">
							<p><a href="index.php?page=about&amp;about=goals">Our Goals</a></p>
							<p><a href="index.php?page=about&amp;about=team">The Team</a></p>
						</div>
					</div>
				</div>
			</div>
