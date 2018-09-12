<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	creates markup to render open/closed logs

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Service;

//_____________________________________________________________________________________________
class LogfileBuilder extends \Logfile\Logfile {

	//_________________________________________________________________________________________
	public function __construct() {

		parent::__construct();

		// Todo: move registration of logfile templates into constructor
	}

	//_________________________________________________________________________________________
	//
	// param1 (string) expects the extract mode
	//		modes: open/closed/all -logs
	//
	public function buildLogs( string $mode = "open" ) {

		$markup = array();
		$logs = $this->getModeBasedLogs( $mode );

		foreach( $logs as $index => $log ) {
			$markup["logs"][] = array(
				"type" => $log->getType(),
				"message" => $log->getMessage(),
				"code" => $log->getCode(),
				"status" => ($log->getOpen()) ? "open" : "closed"
			);
		}

		// when no logs found just return without parsing
		if ( empty($markup) ) return "";
		
		$content = \Templax\Templax::parse( "log-listing", $markup );
		
		return $content;
	}

	//_________________________________________________________________________________________
	// returns based on the mode the correct logs
	//
	// param1 (string) expects the mode
	//
	public function getModeBasedLogs( string $mode ) {

		// switcherihey switcheridoo and away with you
		switch( $mode ) {
			case ("open"): return $this->getOpenLogs(); break;
			case ("closed"): return $this->getClosedLogs(); break;
			case ("all"): return $this->getLogs(); break;
		}

		return array();
	}

	//_________________________________________________________________________________________
	// builds the logs history markup
	
}

//_____________________________________________________________________________________________
//