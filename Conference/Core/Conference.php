<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * the main conference file in which the convenient functionalities lay
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

// global namespace

use \Conference\Core\Bootstrap;
use \Conference\Core;

//_____________________________________________________________________________________________
class Conference {

	/**
	 * no construction allowed since this is just a global function caller class
	 */
	private function __construct() {}

	/**
	 * returns the database instance
	 * 
	 * @return \PDO - the connected database instance
	 * @return null - no database defined
	 */
	static public function db() {

		return Bootstrap\Bootstrap::$db;
	}
	
	/**
	 * returns the requested service
	 * 
	 * @param1 string - expects the service id
	 * 
	 * @return object - the service instance
	 * @return null - no service found under provided id
	 */
	static public function service( $id ) {

		return Bootstrap\Bootstrap::$sm->get( $id );
	}

	/**
	 * returns the actual service manager
	 * 
	 * @return \Conference\Core\Service\ServiceManager
	 */
	static public function serviceManager() {

		return Bootstrap\Bootstrap::$sm;
	}

	/**
	 * returns the current user
	 * 
	 * @return object - the user record
	 * @return null - when no user is logged in
	 */
	static public function user() {

		return Bootstrap\Bootstrap::$sm->get( "userManager" )->getUser();
	}

	/**
	 * returns the actual user manager
	 * 
	 * @return \Conference\Core\User\UserManager;
	 */
	static public function userManager() {

		return Bootstrap\Bootstrap::$um;
	}

	/**
	 * returns the render engine
	 */
	static public function renderer() {

		return Bootstrap\Bootstrap::$sm->get( "renderer" );
	}

	/**
	 * returns the current request
	 * 
	 * @return \Conference\Core\Routing\Request;
	 */
	static public function request() {

		return self::service( "requestHandler" )->getRequest();
	}
	
	/**
	 * returns a defined response
	 */
	static public function response( $markup, $template, $hooks ) {

		return new \Conference\Core\Controller\Response( $markup, $template, $hooks );
	}

	/**
	 * returns a prepared 404 response
	 */
	static public function Http404Response() {

		return ( new \Conference\Core\Controller\Http404Controller() )->index();
	}

	/**
	 * returns a prepared 403 response
	 */
	static public function Http403Response() {

		return ( new \Conference\Core\Controller\Http403Controller() )->index();
	}
}

//_____________________________________________________________________________________________
//