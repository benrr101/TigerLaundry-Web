<?php
/*
 * Log Contoller Class for TigerLaundry (2011 Recode Version)
 * 
 * The LogController class allows for logging of errors, warnings, and notices.
 * It provides a simple method for writing a log entry, constants for the
 * different types of log entries. If the database cannot be reached, then the
 * log entries are written to a file, instead. This is handy if the database
 * fails to connect. The class is essentially a static class, so its methods
 * can be accessed outside of an instance of it. 
 * 
 * @author Ben Russell (benrr101@csh.rit.edu)
 */
 
class LogController {
	// CONSTANTS ================================
	
	/* string
	 * Error entry type 
	 */
	const TYPE_ERROR = "E";
	
	/* string
	 * Warning entry type 
	 */
	const TYPE_WARN = "W";
	
	/* string
	 * Notic entry type 
	 */
	const TYPE_NOTICE = "N";
	
	// METHODS ==================================
	
	// LOG RETRIEVAL METHODS --------------------
	
	/**
	 * Retrieves that last n log entries from the database.
	 * 
	 * @param	$n		int		The number of entries to retrieve. Defaults to 1
	 * 
	 * @return	array	An array of the elements of the log entry. It will be 
	 * 					2D if $n is >1.
	 */
	public static function getLastDBEntries($n = 1) {
		throw new Exception("NOT IMPLEMENTED");
	}
	
	/**
	 * Retrieves that last n log entries from the Log file.
	 * 
	 * @param	$n		int		The number of entries to retrieve. Defaults to 1
	 * 
	 * @return	array	An array of the elements of the log entry. It will be
	 * 					2D if $n is >1.
	 */
	public static function getLastFileEntries($n = 1) {
		throw new Exception("NOT IMPLEMENTED");
	}
	
	// LOGGING METHODS --------------------------
	
	/**
	 * Attempts to store a new log entry in the database. Failing that, an
	 * entry is logged in the log file. In addition to the type of the entry
	 * and message, the remote ip address and date/time stamp is stored.
	 * 
	 * @param	$type		TYPE	The type of the entry. Should be from the
	 * 								set of constants provided by LogController
	 * @param	$message	string	The message of the entry.
	 */
	public static function newEntry($type, $message) {
		// Grab an instance of the database
		$dbConn = MySQLConnection::getInstance();
		
		// Is it connected?
		if($dbConn->isConnected()) {
			// Sanitize the input
			$type = $dbConn->sanitize($type);
			$sanitizedMessage = $dbConn->sanitize($message);

			// Query the database!
			$result = $dbConn->query("INSERT INTO `log` \n" .
					"(type, message, date, ip) \n" .
					"VALUES ('$type', '$sanitizedMessage', NOW(), INET_ATON('{$_SERVER['REMOTE_ADDR']}'))\n"
					);
			
			// Did it fail?
			if($result === FALSE) {
				// Put entry in the log file for the given message and one for
				// the query failure
				self::newFileEntry($type, $message);
				self::newFileEntry(self::TYPE_ERROR, "Log query failed with: " . $dbConn->getLastError());
			}
		} else {
			// Put the entry in the log file
			self::newFileEntry($type, $message);
		}
	}
	
	/**
	 * Attempts to store a log entry in the log file. Failing that, the
	 * method just stops. I don't have the time or energy to make a triple
	 * redundant logging system. Theoretically, the errors shouldn't happen
	 * so, I'm not too deeply invested in catching every single damn one.
	 * 
	 * @param	$type		TYPE	The type of the entry. Should be from the
	 * 								set of constants provided by LogController
	 * @param	$message	string	The message of the entry.	
	 */
	public static function newFileEntry($type, $message) {
		// Open a handle to the log file, supress error
		// TODO: Magic String
		$handle = fopen("logs/log.log", 'a');
		
		// If it failed, just stop.
		if(!$handle) { return; }
		
		// Build the log entry
		// Entry type
		switch($type) {
			case self::TYPE_ERROR:
				$entry = "*E*\t";
				break;
			case self::TYPE_WARN:
				$entry = "+W+\t";
				break;
			case self::TYPE_NOTICE:
				$entry = "-N-\t";
				break;
		}
		// Date/time
		$entry .= date("Y-m-d H:i:s") . "\t";
		// Message
		$entry .= $message . " FROM: " . $_SERVER['REMOTE_ADDR'] . "\n";
		
		// Write it
		fwrite($handle, $entry);
		
		// Close handle
		fclose($handle);
	}
	
	// PRIVATE CONSTRUCTOR/DECONSTRUCTOR/CLONER -
	final private function __construct() {}
	final private function __destruct() {}
	final private function __clone() {}
}
?>