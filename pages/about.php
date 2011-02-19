<?
/*
* Tiger Laundry: About Pages
*
* Design by Benjamin Russell (www.csh.rit.edu/~benrr101)
* Project supported by RIT Residence Halls Association (rha.rit.edu)
*
*/

/**
* Register the global and print the page that matches the requested one
*/

if(isset($_GET['about'])) {
	$about = $_GET['about'];
} else {
	$about = 'goals';
}

switch($about) {
	case 'locations':
		break;

	case 'team':
		break;

	case 'goals':
	default:
		?>
				<div id="container2" style="width:70%; float:left;">
					<h1>Goals of the TigerLaundry Project -</h1>
					<div id="aboutContentLeft">
						<p>The TigerLaundry project is still a work in progress. Our main goal is to provide the RIT residence community with the status of washers and dryers all across campus. We also hope to enhance the features of the TigerLaundry service to provide:</p>
						<ul>
							<li>Text Message Notification of Open Machines</li>
							<li>Usage Statistics on Laundry Locations</li>
							<li>Notification of Broken Machines to Maintenence Staff</li>

						</ul>
					</div>
				</div>
		<?
		break;
}
?>