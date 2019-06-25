<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * log model
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Logfile\Source\Models;

//_____________________________________________________________________________________________
class Log {

	/**
	 * the logging message
	*/
	public $message;
	
	/**
	 * the log type
	 */
	public $type;

	/**
	 * log code / eg. 1, 1024, who knows..
	 */
	public $code;

	/**
	 * describes wether this log has been checked or is still active
	 */
	public $open;

	/**
	 * construction
	 * 
	 * @param1 string - expects the log message
	 * @param2 string - expects the log type
	 * @param3 int - expects the code
	 */
	public function __construct( $message, $type = null, $code = null ) {

		$this->message = $message;
		$this->type = $type;
		$this->code = $code;
		$this->open = true;
	}
}

//_____________________________________________________________________________________________
//
