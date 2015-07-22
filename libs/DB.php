<?php

class DB{
	static protected $instances;
	public           $connection;
	protected        $dbIp;
	protected        $dbName ;

	private function __construct($dbIpp,$dbUserNamee,$dbPasswordd,$dbNamee) {
		$this->dbIp = $dbIpp;
		$this->dbName = $dbNamee ;
		
		try {
		    $this->connection = mysqli_connect($dbIpp,$dbUserNamee,$dbPasswordd,$dbNamee);
		    if(!$this->connection){
				throw new ErrorException();
		    }
		    self::$instances[] = $this ;
	    }catch (ErrorException $obj) {
			$text   = " System =Error = Could Not Connect To The Database $dbNamee on server $dbIpp from page ".$_SERVER['HTTP_REFERER']." on main class  error string=".mysqli_connect_error()."\n*********************************************************** \n\n " ;
			@error_log($text);
			
        }//end try
	}

	public function __destruct() {
		if (is_array(self::$instances)) {
			foreach (self::$instances as $instance) {
				if ($instance instanceof DB  && $instance->connection == $this->connection && $instance->dbName == $this->dbName  ) {
					mysqli_close($instance->connection);
				}
			}
		}
	}

	/**
	 * Create a DB Connection
	 *
	 * @return DB
	 */
	static public function Connect($dbIp,$dbUserName,$dbPassword,$dbName) {
		
		
		if (is_array(self::$instances)) {
			foreach (self::$instances as $instance) {
				if ($instance instanceof DB  && $dbIp == $instance->dbIp && $dbName == $instance->dbName ) {
					if (mysqli_ping($instance->connection)){
						return $instance;
					}
				}
			}
		}
		$return = new DB($dbIp,$dbUserName,$dbPassword,$dbName);
		//self::$instances[] = $return;
		return $return;
	}

	/**
	 * Send an SQL Query to the DB
	 *
	 * @param string $query
	 * @return mysql_result
	 */
	public function Query($query) {
        try{
        	if (!$this->connection){
        		throw new ErrorException();
        	}
		    $result = mysqli_query($this->connection,$query);
		    
		    while(mysqli_more_results($this->connection)) {
			   mysqli_next_result($this->connection);
		    }
		    
		   
		    return $result;
		}catch (ErrorException $obj) {
			@error_log($query.$this->errorString);
        }//end try
    }

	/**
	 * get Error number
	 * @name errorNumber
	 * @return mysql_result
	 */
	public function errorNumber() {
    	$result = mysqli_errno($this->connection);
    	return $result;
    }//end method

 /**
	 * get Error String
	 * @name errorString
	 * @return mysql_result
	 */
    public function errorString() {
    	$result = mysqli_error($this->connection);
    	return $result;
    }//end method

}
?>