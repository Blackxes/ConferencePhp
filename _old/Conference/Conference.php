<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	simplified function access of the system

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/

// global namespace

use Conference\Core\Bootstrap;
use Conference\Core;

//_____________________________________________________________________________________________
class Conference {

	//_________________________________________________________________________________________
	// no instance of this class is allowed nor needed
	private function __construct() {}
	
	//_________________________________________________________________________________________
	// returns conference prefix
	static public function ConferencePrefix() {
		return Core\Configuration\General["prefix"];
	}

	//_________________________________________________________________________________________
	// returns the database instance
	static public function db() {
		return Bootstrap\Bootstrap::$db;
	}

	//_________________________________________________________________________________________
	// returns the service handler
	static public function serviceHandler() {
		return Bootstrap\Bootstrap::$serviceHandler;
	}

	//_________________________________________________________________________________________
	// returns the requested service
	static public function service( string $id ) {
		return Bootstrap\Bootstrap::service( $id );
	}

	//_________________________________________________________________________________________
	// returns the current user
	static public function user() {
		return Bootstrap\Bootstrap::$currentUser;
	}

	//_________________________________________________________________________________________
	// returns the user handler
	static public function userHandler() {
		return Bootstrap\Bootstrap::service( "user" );
	}

	//_________________________________________________________________________________________
	// returns the form builder
	static public function formBuilder() {
		return Bootstrap\Bootstrap::service( "formBuilder" );
	}

	//_________________________________________________________________________________________
	// html parser
	static public function renderer() {
		return Bootstrap\Bootstrap::service( "renderer" );
	}

	//_________________________________________________________________________________________
	// returns a new regular response instance
	static public function response( string $code = "200") {
		return new Core\Response\Response( $code );
	}

	//_________________________________________________________________________________________
	// logs a message into the internal logfile
	static public function log( string $message, string $type = "", int $code = 0, $return = null ) {
		return Bootstrap\Bootstrap::service( "logfile" )->logReturnFull( $message, $type, $code, $return );
	}

	//_________________________________________________________________________________________
	// returns the current route
	static public function currentRoute() {
		return Bootstrap\Bootstrap::service( "router" )->route->route;
	}

	//_________________________________________________________________________________________
	// returns a response with the status code 404 and the belonging default 404 controller
	static public function http404Response() {
		return new Core\Controlling\Response\Http404Response();
	}

	//_________________________________________________________________________________________
	// synonym for the http 404 response {
	static public function pageNotFound() {
		return new Core\Controlling\Response\Http404Response();
	}

	//_________________________________________________________________________________________
	// returns a response with the status code 403 and the belonging default 403 controller
	static public function http403Response() {
		return new Core\Controlling\Response\Http403Response();
	}

	//_________________________________________________________________________________________
	// synonym for the http 403 deny access response
	static public function denyAccess() {
		return new Core\Controlling\Response\Http403Response();
	}

	//_________________________________________________________________________________________
	// redirection to another page
	static public function redirect( string $route ) {

		// clear out data before redirecting
		\Conference::service( "router" )->clearQueriesAndPosts();
		
		// redirect then internally to a page
		header( "Location: " . $GLOBALS["Conference"]["General"]["domain"] . $route );
	}

	//_________________________________________________________________________________________
	// redirects to a static page
	static public function redirectStatic( string $url ) {

		// clear out data before redirecting
		\Conference::service( "router" )->clearQueriesAndPosts();

		header( "Location: " . $url );
	}
}

//_____________________________________________________________________________________________
//