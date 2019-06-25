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
class Request {

	/**
	 * current route
	 */
	public $route = null;

	/**
	 * contains the server settings / the environment
	 */
	public $env = array();

	/**
	 * contains the get and post data
	 */
	public $data = array();

	/**
	 * contains the session data
	 */
	public $session = array();

	/**
	 * construction
	 */
	public function __construct( string $route = null, array $env = array(), array $data = array() ) {
		
		$this->route = $route;
		$this->env = $env;
		$this->data = $data;
	}
}

//_____________________________________________________________________________________________
//