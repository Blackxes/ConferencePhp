<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	user management

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Backend\Controller;

use Conference\Core\Controlling\Controller;
use Conference\Core\Controlling\Response;

//_____________________________________________________________________________________________
class UserController extends Controller\ControllerBase {

	//_________________________________________________________________________________________
	public function __construct() {}

	//_________________________________________________________________________________________
	// base entrance of the controller when no entrance is defined
	// 
	// param1 (&array) expects the base markup
	//
	// return \Conference\Core\Controlling\Response\Response
	//		the response object
	//
	public function index( array $markup, array $options ): Response\Response {

		return new Response\Response( $markup, $options );
	}

	//_________________________________________________________________________________________
	// registers a new user
	//
	public function register( array $markup, array $options ): Response\Response {

		$markup["page-head"]["title"] = "Neuer Benutzer";
		$markup["page-body"]["page-title"] = "Neuen Benutzer registrieren";
		$body = &$markup["page-body"]["content-items"];

		$body[]["item"] = \Conference::formBuilder()->build( "userRegister" );

		return new Response\Response( $markup, $options );
	}

	//_________________________________________________________________________________________
	// login entrance
	//
	public function login( array $markup, array $options ): Response\Response {

		// check if user is logged in
		if ( !\Conference::userHandler()->isAnonymous() )
			\Conference::redirect( "/user/profile" );

		$markup["page-head"]["title"] = "Benutzerlogin";
		$markup["page-body"]["page-title"] = "Benutzerlogin";
		$body = &$markup["page-body"]["content-items"];

		$body[]["item"] = \Buildorm\Buildorm::build( "userlogin" );

		return new Response\Response( $markup, $options );
	}

	//_________________________________________________________________________________________
	// logout
	//
	public function logout( array $markup, array $options ): Response\Response {

		\Conference::userHandler()->logoutUser();
		\Conference::redirect("/user/login");

		return new Response\Response( $markup, $options );
	}

	//_________________________________________________________________________________________
	//
	public function profile( array $markup, array $options ): Response\Response {

		// check if user is logged in
		if ( \Conference::userHandler()->isAnonymous() )
			\Conference::redirect( "/user/login" );
		
		$user = \Conference::user();
		
		$markup["page-head"]["title"] = "Benutzerprofil | " . $user->getUsername();
		$markup["page-body"]["page-title"] = $user->getUsername();

		$body = &$markup["page-body"]["content-items"];

		$body[]["item"] = \Conference::service( "userContentBuilder" )->buildProfile();

		return new Response\Response( $markup, $options );
	}
}

//_____________________________________________________________________________________________
//