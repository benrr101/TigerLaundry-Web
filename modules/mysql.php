<?
/*
* Database Connection Module for Tiger Laundry (version 4)
*
* Design by Benjamin Russell (www.csh.rit.edu/~benrr101)
* Project Supported by Residence Halls Association (rha.rit.edu)
*
*/

class mysqli_ardu {
	/*
	* This class is for a singleton database connection object using
	* the credentials for an arduino.
	*/

	// Object containing the mysqli database connection in different modes
	private $mysqli;

	// The instances of the database connection in different modes
	private static $db_instance;

	// Constructor of the object. By making it private, an object like
	// this cannot be created except through itself
	private function __construct() {}

	public static function instance() {
		/*
		* This method checks for an already existing connection to
		* the database. If it exists, we return that one. If it doesn't
		* then we create one.
		* Args -
		* 	None.
		*/

		if(!self::$db_instance) {
			self::$db_instance = new mysqli_ardu();
		}

		return self::$db_instance;

	}

	public function connect() {
		/*
		* This method connects to the database using the mysqli
		* class. A connection is made using the ardu credentials.
		* Args -
		* 	None.
		*/

		// Connect!
		$this->mysqli = new mysqli("localhost", "tl_ardu", "ardu101", "tigerlaundry");

		// Check for failure
		if(mysqli_connect_error()) {
			return(false);
		}
		return(true);
	}

	public function close() {
		/*
		* Close out the connection.
		* Args -
		* 	None.
		*/

		$this->mysqli->close();
	}

	public function sanitize($data) {
		/*
		* This method simply escapes data passed to it to prevent
		* most SQL injection attacks.
		* Args -
		* $data		- str, the data to be sanitized
		*/

		$sanidata = $this->mysqli->real_escape_string($data);

		return($sanidata);
	}

	public function query($query) {
		/*
		* Run the query using the specified connection mode.
		* Args -
		* $query	- str, the query to run
		*/

		//Run It!
		if (!$result = $this->mysqli->query($query)) {
			return false;
		} else {
			return $result;
		}
	}

	public function __destruct() {
		/*
		* Deconstructs the class.
		* Args -
		* 	None.
		*/

		$this->mysqli->close();
	}
}

class mysqli_client {
	/*
	* This class is for a singleton database connection object using
	* the credentials for a client. Same as above, so condensed.
	*/
	private $mysqli;
	private static $db_instance;

	private function __construct() {}

	public static function instance() {
		if(!self::$db_instance) {
			self::$db_instance = new mysqli_client();
		}
		return self::$db_instance;
	}

	public function connect() {
		$this->mysqli = new mysqli("localhost", "tl_client", NULL, "tigerlaundry");
		if(mysqli_connect_error()) {
			return(false);
		}
		return(true);
	}

	public function close() {
		$this->mysqli->close();
	}

	public function sanitize($data) {
		$sanidata = $this->mysqli->real_escape_string($data);
		return($sanidata);
	}

	public function query($query) {
		if (!$result = $this->mysqli->query($query)) {
			return false;
		} else {
			return $result;
		}
	}

	public function selectQuery($query) {
		/*
		* This method runs a selection query on the database and then
		* returns an array containing all the rows, matching the query.
		*/
		//Make sure it's a selection query
		if(!strstr($query, "SELECT")) {
			return false;
		}

		$resultArray = array();

		// Run the query
		if($result = $this->mysqli->query($query)) {
			while($row = $result->fetch_assoc()) {
				array_push($resultArray, $row);
			}

			$result->close();

			if(count($resultArray) >= 1) {
				return $resultArray;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function __destruct() {
		$this->mysqli->close();
	}
}

class mysqli_log{
	/*
	* This class is for a singleton database connection object using
	* the credentials for a client. Same as above, so condensed.
	*/
	private $mysqli;
	private static $db_instance;

	private function __construct() {}

	public static function instance() {
		if(!self::$db_instance) {
			self::$db_instance = new mysqli_log();
		}
		return self::$db_instance;
	}

	public function connect() {
		$this->mysqli = new mysqli("localhost", "tl_log", "log101", "tigerlaundry");
		if(mysqli_connect_error()) {
			return(false);
		}
		return(true);
	}

	public function close() {
		$this->mysqli->close();
	}

	public function sanitize($data) {
		$sanidata = $this->mysqli->real_escape_string($data);
		return($sanidata);
	}

	public function query($query) {
		if (!$result = $this->mysqli->query($query)) {
			return false;
		} else {
			return $result;
		}
	}

	public function __destruct() {
		$this->mysqli->close();
	}
}