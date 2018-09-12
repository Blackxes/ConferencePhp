<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * manages a logging system stored in a database
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Logfile;

define( "LOGFILE_ROOT", __DIR__, true );

require_once ( LOGFILE_ROOT . "/Configuration.php" );
// require_once ( LOGFILE_ROOT . "/Source/Models/Log.php" );

//_____________________________________________________________________________________________
class Logfile {

	/**
	 * the database connection
	 */
	static private $db;

	/**
	 * defines wether the logfile is initialized and usable or not
	 */
	static private $defined;

	/**
	 * no constructiong needed since the logfile provides contexts in which
	 * the situation or anything else is definable to differ between logs
	 */
	private function __construct() {}

	/**
	 * defines the logfile / initializes it
	 */
	static public function Init( \PDO $db ) {

		// when the configuration is not set not logs can be displayed / no matter what
		if ( !isset($GLOBALS["Logfile"]["active"]) ) return true;

		self::$db = $db;
		self::$defined = true;

		return true;
	}

	/**
	 * logs messages
	 * 
	 * @param1 string - expects the log message
	 * @param2 string - expects the log type
	 * @param3 int - expects the status code
	 * @param4 context - expects the context
	 * @param5 mixed - expects the return value
	 * 
	 * @return null
	 * 	- when nothing is defined as return value
	 * 	- when invalid values are passed
	 * 	- when the return value is defined as nul
	 * 	- when the logfile is not initialized
	 * @return mixed - the defined return value
	 */
	static public function log( string $message, ?string $type = null, ?int $code = null, ?string $context = null, $return = null ) {

		if ( !self::$defined || !$GLOBALS["Logfile"]["active"] || !self::verify($message, $type, $code, $context) )
			return null;

		try {
			self::insert($message, $type, $code, $context);
		}
		catch( \Exception $exc) {

			echo $exc->getMessage();
			return null;
		}
		
		return $return;
	}

	/**
	 * verifies the given log information and determines wether is loggable or not
	 */
	static private function verify( string $message, ?string $type, ?int $code, ?string $context ) {

		// so far only the message has to be defined
		if ( empty($message) ) return false;

		return true;
	}

	/**
	 * inserts a new log
	 * 
	 * @param1 string - expects the log message
	 * @param2 string - expects the log type
	 * @param3 int - expects the status code
	 * @param4 context - expects the context
	 * 
	 * @return true - when insertion was successful
	 * @throw \Exception - containing the db error
	 */
	static private function insert( string $message, ?string $type, ?int $code, ?string $context ) {
		
		$stmt = self::$db->prepare("
			INSERT INTO `conc_logs`
				( message, type, code, context )
			VALUES
				( :message, :type, :code, :context );
		");
		$stmt->bindParam( ":message", $message );
		$stmt->bindParam( ":type", $type );
		$stmt->bindParam( ":code", $code );
		$stmt->bindParam( ":context", $context );
		
		if ( !$stmt->execute() ) throw new \Exception( $stmt->errorInfo()[2] );
		
		return true;
	}

	/**
	 * returns all open logs
	 * 
	 * @param1 string - expects the type
	 * @param2 string - expects the context
	 * @param3 int - expects the code
	 * 
	 * @return array
	 * 	- the logs as indexed objects
	 * 	- empty array when no retrieve
	 */
	static public function getLogs( string $type = "", string $context = "", int $code = 0 ) {

		if ( !self::$defined || !$GLOBALS["Logfile"]["retrieveLogs"] )
			return array();

		// initial query
		$query = array();

		// select the logs
		$query[] = "SELECT cl.uid, cl.message, cl.type, cl.code, cl.context, cl.open, unix_timestamp(cl.crdate) as crdate, unix_timestamp(cl.updated) as updated FROM `conc_logs` as cl";

		// where clause
		$whereClause = self::buildWhere( array("cl.type" => $type, "cl.context" => $context, "cl.code" => $code) );
		
		// add where to select
		$query[] = "WHERE cl.open=1";

		if ( !empty($whereClause) )
			$query[] = "AND {$whereClause}";
		
		// finish select
		$query[] = "ORDER BY cl.crdate;";

		// update open state
		$query[] = "UPDATE `conc_logs` as cl SET cl.open=0";

		
		$query[] = "WHERE cl.open=1";

		if ( !empty($whereClause) )
			$query[] = "AND {$whereClause}";
		
		print_r($query);

		// execution
		$stmt = self::$db->prepare( implode(" ", $query) );

		if ( !$stmt->execute() ) throw new \Exception( $stmt->errorInfo()[2] );

		// extraction
		$logs = array();

		while( $log = $stmt->fetchObject() )
			$logs[$log->uid] = $log;

		return $logs;
	}

	/**
	 * builds the where clause for a log pull
	 * 
	 * @param1 array - expects the where items
	 * 
	 * @return string
	 * 	- the where clause
	 * 	- empty string when invalid values
	 */
	static private function buildWhere( array $items ) {

		if ( empty($items) )
			return "";
		
		$whereItems = array();
		
		foreach( $items as $key => $value )
			if ( is_string($key) && !empty($value) )
				$whereItems[$key] = "'{$key}' = '{$value}'";
		
		return implode( " AND ", $whereItems );
	}
}

//_____________________________________________________________________________________________
//