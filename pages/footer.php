<?
/*
* Tiger Laundry Design 5: Footer
*
* Design by Benjamin Russell (www.csh.rit.edu/~benrr101)
* Project supported by RIT Residence Halls Association (rha.rit.edu)
*
*/
?>
<div id="footer">
				<div id="footerTitle">
					<a href="index.php?page=home"><img src='./images/footerTitle.png' alt="Tiger Laundry" /></a>
				</div>
				<div id="footerNav">
					<a href="index.php?page=home">Home</a> | <a href="index.php?page=about">About</a> <br />
					|
					<?
					  // Print out all the locations
					  foreach($locationRows as $location) {
						echo("<a href='index.php?page=bldg&amp;location={$location['shortname']}'>{$location['longname']}</a> | ");
					  }
					  echo("\n");
					?>
				</div>
				<div id="footerLogoBar">
					<a href="http://rha.rit.edu/"><img src='./images/footerRHA.png' alt="RHA" /></a>
				</div>
			</div>
