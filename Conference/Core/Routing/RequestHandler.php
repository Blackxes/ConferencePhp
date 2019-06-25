<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * handles the incoming request / the step before the router kicks in
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Routing;

//_____________________________________________________________________________________________
class RequestHandler {

	/**
	 * current request
	 */
	private $request = null;
	

	/**
	 * construction
	 */
	public function __construct() {}

	/**
	 * handles the request and returns a request object
	 * 
	 * @return \Conference\Core\Routing\Request
	 */
	public function handleRequest() {

		// initial request and subsituation
		// the request object will be defined while the request is handled
		$this->request = new namespace\Request();
		$request = &$this->request;

		// the route and data
		$data = $this->getPreparedSentData();

		$request->data = $data;
		$request->route = ($data["r"]) ? $data["r"] : null;

		// Todo: implement filter for dangerous values or header.. etc.
		$request->env = $_SERVER;

		$request->session = $_SESSION;

		return $request;
	}
	
	/**
	 * returns the request object
	 * 
	 * @return null|\Conference\Core\Routing\Request
	 */
	public function getRequest() {

		return $this->request;
	}

	/**
	 * prepares the post and get data into one array
	 * higher prio have the post data
	 * 
	 * @return array
	 */
	private function getPreparedSentData() {

		// Todo: implement filter for invalid or dangerous data
		$data = array_merge( $_GET, $_POST );

		return $data;
	}
}

//_____________________________________________________________________________________________
//