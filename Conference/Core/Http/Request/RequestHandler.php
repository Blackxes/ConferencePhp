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

//_____________________________________________________________________________________________
class RequestHandler {

	/**
	 * contains the current request
	 * 
	 * @var \Conference\Core\Http\Request\Request
	 */
	private $request;

	/**
	 * construction
	 */
	public function __construct() {
	}

	/**
	 * handles the request and returns a request object
	 * 
	 * @return \Conference\Core\Routing\Request
	 */
	public function handle() {

		// only handle requests once
		if ( !is_null($this->request) )
			return $this->request();
		
		$this->request = new namespace\Request();

		return $this->request;
	}

	/**
	 * returns the request
	 * 
	 * @return \Conference\Core\Http\Request
	 */
	public function getRequest() {

		return $this->request;
	}
}

//_____________________________________________________________________________________________
//