<?php
/**********************************************************************************************
 * 
 * very simple logfile class
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Logfile;

class Logfile {

	/**
	 * stores logs
	 * 
	 * @var array
	 */
	private $logs;

	/**
	 * construction
	 */
	public function __construct() {

		$this->logs = [];
		$this->userLogs = [];
	}

	/**
	 * logs a message
	 * 
	 * @param string $message - the message
	 * @param string $type - log type
	 * @param object $classContext - the context from where the log comes from
	 * @param mixed $return - the return value of this function
	 */
	public function log( string $message, string $type = "info", $classContext = null, $return = null ) {

		$this->logs[] = [
			"message" => $message,
			"type" => $type,
			"created" => ( new \DateTime() ),
			"context" => is_object($classContext) ? get_class( $classContext ) : null
		];

		return $return;
	}
	
	/**
	 * logs a user message - difference to a normal log is that these are stored in sessions
	 * 
	 * @param string $message - user message
	 * @param string $type - message type
	 * @param mixed $return - the return type
	 * 
	 * @return mixed - defined return value
	 */
	public function userLog( string $message, string $type, $return = null ) {

		$_SESSION["CONFERENCE"]["Logfile"]["UserLogs"][] = [
			"message" => $message,
			"type" => $type
		];

		return $return;
	}

	/**
	 * returns all userlogs
	 */
	public function userLogs( $clear = false ) {

		$userLogs = $_SESSION["CONFERENCE"]["Logfile"]["UserLogs"];

		if ( $clear )
			$_SESSION["CONFERENCE"]["Logfile"]["UserLogs"] = [];

		return $userLogs;
	}

	/**
	 * returns all logs
	 * 
	 * @param boolean $clear - defines whether the logs array shall be cleared
	 */
	public function all( $clear = false ) {

		$logs = $this->logs;

		if ( $clear )
			$this->logs = [];

		return $logs;
	}
}