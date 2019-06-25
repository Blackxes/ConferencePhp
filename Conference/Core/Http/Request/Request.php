<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * handles the incoming request / the step before the router kicks in
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Http\Request;

use \Conference\Core\Classes;

//_____________________________________________________________________________________________
class Request extends Classes\ParameterBag {

	/**
	 * construction
	 */
	public function __construct() {
		
		// readonly object
		parent::__construct([

			"route" => (string) $_GET["r"],
			"env" => new Classes\ParameterBag( $_SERVER ),
			"files" => new Classes\ParameterBag( $_FILES ),
			"post" => new Classes\ParameterBag( array_merge($_POST, (array) $this->parseRawPost()) ),
			"session" => new Classes\ParameterBag( $_SESSION ),
			"query" => new Classes\ParameterBag( $_GET )

		], true );
	}

	/**
	 * pulls the raw post data
	 */
	public function parseRawPost() {

		// Todo: check content type		
		$raw = file_get_contents("php://input");

		if ( !$raw )
			return [];
		
		return json_decode( $raw, true );
	}
}

//_____________________________________________________________________________________________
//