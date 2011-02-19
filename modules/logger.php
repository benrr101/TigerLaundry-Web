<?
/*
* Tiger Laundry: Log Editing File
*
* Design by Benjamin Russell (www.csh.rit.edu/~benrr101)
* Project supported by RIT Residence Halls Association (rha.rit.edu)
*
*/

function log_entry($log, $entry) {
	/*
	* This function opens a connection to the MySQL database to post the
	* log entry to the table. The log data is constructed and sanitized
	* before insertion into the database. Returns true on successful query
	* and false on failure.
	* Args -
	* $log		- str, the type of entry
	* 		e = error log, p = posting log
	* $entry	- str, the entry to append to the log db
	*/

	// Include the mysqli interface if it hasn't been included already.
	include_once('/home/ben/www/laundryview2/modules/mysql.php');

	// Create an object for the log connection & Connect
	$db_log = mysqli_log::instance();
	$connection = $db_log->connect();

	// Check for errors
	if(!$connection) {
		return("<!-- Error: A connection to the log database could not be made. This will not hurt the functioning of the site. -->\n");
	}

	// Sanitize the input
	$log   = $db_log->sanitize($log);
	$entry = $db_log->sanitize($entry);

	// No failure, so continue by generating a query, piece by piece
	$query = "INSERT INTO log (type, ip, date, message) VALUES (";

	$ip = $_SERVER['REMOTE_ADDR'];
	$date = date('r');

	// Check to see if the log type is valid
	if(($log != 'e') and ($log != 'p')) {
		// Not a valid choice. Back out of the function.
		echo("<!-- Error: '{$log}' is not a valid log. This will not hurt the functioning of the site. -->\n");
		return(false);
	}

	$query = $query . "'{$log}', '{$ip}', '{$date}', '{$entry}')";

	// Run the query
	$result = $db_log->query($query);

	// Check for failure
	if(!$result) {
		echo("<!-- Error: The log failed to be inserted into the database. This will not hurt the functioning of the site. " . mysql_error() . " -->\n");
		return false;
	}

	// Done! Return true.
	return true;
}

?>