<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * all system configuration is in here
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\User;

use \Conference\Core\Controller;
use \Conference\Core\Rendering;
use \Conference\Core\Http\Response;
use \Conference\Core\User\Verification;

//_____________________________________________________________________________________________
class UserController extends Controller\ControllerBase {

	/**
	 * construction
	 */
	public function __construct( string $template = null, array $markup = [], array $hooks = [] ) {

		parent::__construct( $template, $markup, $hooks );
	}

	/**
	 * deletes an user address
	 * 
	 * HeadComment:
	 */
	public function deleteAddress() {

		if ( \Conference::service("address.manager")->deleteFromPost() )
			\Conference::redirectByRouteKey( "user_profile" );
		
		return $this;
	}

	/**
	 * main entrance
	 */
	public function index() {

		# either login user or shows its profile
		if ( !\Conference::currentUser() )
			\Conference::redirectByRouteKey( "user_login" );
		
		\Conference::redirectByRouteKey( "user_profile" );
	}
	
	/**
	 * user login
	 * 
	 * HeadComment:
	 */
	public function login() {

		$manager = \Conference::service( "user.manager" );

		# redirect to profile when already logged in
		if ( !\Conference::currentUser()->get("anonymous") ) {

			# redirect to requested page
			if ( $_SERVER["HTTP_REFERER"] )
				\Conference::redirect( $_SERVER["HTTP_REFERER"] );
			
			# else regular profile page
			\Conference::redirectByRouteKey( "user_profile" );
		}
		
		$builder = \Conference::service( "user.markupbuilder" );

		$this->template = "conren_root_login";

		$this->markup["sections"][] = $builder->loginFormular();
		
		return $this;
	}

	/**
	 * logs the current user out
	 * 
	 * HeadComment:
	 */
	public function logout() {

		if ( !\Conference::currentUser()->get("anonymous") )
			Verification\Logout::logout();

		\Conference::redirectByRouteKey( "user_login" );
	}

	/**
	 * display the user profile of either current user or the requested one
	 * 
	 * HeadComment:
	 */
	public function profile() {

		# base markup
		$this->markup["page-title"] = "Benutzerprofil";
		$this->markup["head"]["webpage-title"]["templateSelect-template"]["content"] = "Benutzer";

		$urlid = \Conference::service( "route.handler" )->getRouteObject()->get([ "variables", "user_id" ]);
		$uid = is_null($urlid) ? \Conference::currentUser()->get("uid") : \Conference::service( "user.manager" )->get( $urlid );

		# profile not found
		if ( !$uid )
			return $this;
		
		# initialize address markup builder to load address templates
		\Conference::service( "address.markupbuilder" );

		$builder = \Conference::service( "user.markupbuilder" );
		$this->markup["content"][] = $builder->profile( $uid );
		$this->markup["content"][] = $builder->addresses( $uid );
		// $this->markup["content"][] = \Conference::service( "purchaseMarkupBuilder" )->history( $uid );

		return $this;
	}

	/**
	 * lists all users
	 * 
	 * HeadComment:
	 */
	public function list() {
	}

	/**
	 * registering a new user
	 * 
	 * HeadComment:
	 */
	public function register() {
	}

	/**
	 * view to register a new user address
	 * 
	 * HeadComment:
	 */
	public function registerAddress() {

		# check registration attempt
		if ( \Conference::service( "user.manager" )->registerAddressFromPost() )
			\Conference::redirectByRouteKey( "user_profile" );

		$this->markup["page-title"] = "Adresse registrieren";

		$this->markup["content"][] = \Conference::service( "user.markupbuilder" )->registerAddressForm();

		# disables
		$this->markup["side-menu"]["side-menu"] = null;

		return $this;
	}
}

//_____________________________________________________________________________________________
//