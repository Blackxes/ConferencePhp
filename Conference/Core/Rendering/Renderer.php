<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * manages the display of content based on the given response
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Rendering;

use \Conference\Core\Classes;
use \Conference\Core\Http\Response;

//_____________________________________________________________________________________________
class Renderer extends Classes\ParameterBag {

	/**
	 * construction
	 */
	public function __construct() {

		parent::__construct();
	}

	/**
	 * renders the given response
	 */
	public function render( Response\Response $response ) {

		// prepare the actual content
		$response->prepare();

		// debug( \Conference::service("logfile")->userLogs(true) );

		benchmark();

		// debug( $response );
		// exit;

		// check benchmarking
		if ( !$GLOBALS["CONREN"]["Benchmarking"]["benchmark"] )
			$response->sendContent();
		else
			printBenchmarks();

		return $this;
	}
}


//_____________________________________________________________________________________________
//