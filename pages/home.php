<div id="container2" style="width:70%; float:left;">
					<h1>Welcome to the TigerLaundry Project!</h1>
					<p>Ever wanted to know how full a laundry room is? Without leaving your room? The TigerLaundry project's goal is to enable you to do just that! On the TigerLaundry website, you can check the status of laundry facilities all over the RIT campus. Now you can check each room and see how full it is, which machines are in use, which are taken, and which are broken. </p><br />

					<p>The TigerLaundry project is still in it's infancy and at this point our goal is to prove that a site like this can be created. We currently only have two machines connected to the system, but in the near future, we plan to serve laundry facilities all over the RIT campus and implement many more features such as:</p><br />

					<ul>
						<li>Text message updates of machine status</li>
						<li>Usage statistics by day and by hour</li>
						<li>Automatic notification to maintenence staff of broken machines</li>
						<li>Live time remaining on machines</li>
						<li>And more...</li>
					</ul>
				</div>
				<div id="quickStatus">
					<h3>Quick Status -</h3>
					<ul>					
<?
  // Load up the statuses of each location
  foreach($locationRows as $location) {
	// Status icon
	switch($location['status']) {
	case 0:
		echo("\t\t\t\t\t\t<li><img src='./images/status_green.png' alt='[Green]' /> ");
		break;
	case 1:
		echo("\t\t\t\t\t\t<li><img src='./images/status_yellow.png' alt='[Yellow]' /> ");
		break;
	case 2:
		echo("\t\t\t\t\t\t<li><img src='./images/status_red.png' alt='[Red]' /> ");
		break;
	}
	// Link to location
	echo("<a href='index.php?page=bldg&amp;location={$location['shortname']}'>{$location['longname']}</a></li>\n");
  }
?>
					</ul>
				</div>					

