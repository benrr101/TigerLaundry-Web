<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<?php
		     // Include the mysql, logger, and pageloader modules
		     include_once("./modules/pages.php");
		     include_once("./modules/logger.php");
		     include_once("./modules/mysql.php");

		     // Generate a global list of rows for the locations
		     $db = mysqli_client::instance();
		     $db->connect() or die("<h1>Fatal Error -</h1><p>Could not connect to the database as a client</p>");
		     $locationRows = $db->selectQuery("SELECT * FROM `location`") or die("<h1>Fatal Error -</h1><p>The location selection query failed.</p>");
		     
		     // Get the title of the page
		     $title = getTitle($page, $titleList, $default);
		?>

		<title><? echo($title); ?></title>

		<!-- META DATA -->
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />

		<!-- CSS -->
		<link href="./css/global2.css" rel="stylesheet" type="text/css" />

		<!-- JavaScript -->
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/menu_animate2.js"></script>
	</head>

	<body>
		<div id="container">
			<!-- HEADER -->
			<?
			  // Require the header
			  require_once("./pages/header.php");
			?>

			<!-- BODY -->
			<div id="content">
				<?
				  // Get the page
				  $gotten = getPage($page, $pageList, $locationRows);

				  // If it failed, write to the log
				  if(!$gotten) {
				  	// Build the error string and log it
				  	$error = "{$_GET['page']} could not be gotten by /index.php\n";
				  	log_entry('e', $error);
				  }
				?>
			</div>

			<!-- FOOTER -->
			<?
			  // Require the footer
			  require_once("./pages/footer.php");
			?>
		</div>


	</body>
</html>
