<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * all system configuration is in here
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Packages\User;

use \Conference\Core\Controller;
use \Conference\Core\Routing;
use \Conference\Core\Rendering;

//_____________________________________________________________________________________________
class UserController extends Controller\ControllerBase {

	/**
	 * construction
	 */
	public function __construct( array $markup = array(), array $options = array(), array $hooks = array() ) {

		parent::__construct( $markup, $options, $hooks );
	}

	/**
	 * main entrance
	 */
	public function index() {

		$uManager = \Conference::service( "userManager" );

		// display all user
		var_dump( $uManager->getUserList() );

		exit;

		return new Routing\Response();
	}

	public function login() {

		// check if user already logged in
		if ( \Conference::currentUser()->get("anonymous") )
			\Conference::redirect( "user_profile" );
		
		

		$uContentBuilder = \Conference::service( "userContentBuilder" );

		$hooks = array();

		$uContentBuilder->userLoginFormular();

		exit;
	}

	/**
	 * display the user profile of either current user or the requested one
	 */
	public function profile() {

		// get user id
		$id = \Conference::service( "router" )->routeObj;

		

		var_dump( $id );

		exit;
	}

	/**
	 * registering a new user
	 */
	public function register() {

		$uManager = \Conference::service( "userManager" );

		var_dump( $uManager->getCurrentUser() );

		// var_dump( $uManager->get("") );
		// var_dump( $uManager->getUserList() );
		// var_dump( $uManager-> );

		exit;
		
		// $markup[ "webpage-title" ] = "Wow neuer Titel";	
		
		// $hooks[ "conren_root_webpage-title" ] = "Neuen Benutzer registrieren";

		return new Routing\Response( new Rendering\RenderObject(null, $markup, $hooks) );
	}
}

//_____________________________________________________________________________________________
//