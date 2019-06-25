<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * user relevant content building
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\User;

use \Conference\Core\Rendering\ContentBuilding;

//_____________________________________________________________________________________________
class UserMarkupBuilder extends ContentBuilding\MarkupBuilderBase {

	public function __construct() {
		
		parent::__construct([
			"templates" => [
				"conren_user_form_login", "conren_user_form_logout", "conren_user_profile", "conren_user_teaser",
				"user_addresses"
			],
			"options" => [ "file" => __DIR__ . "/user.html" ]
		]);
	}

	/**
	 * returns the markup for the addresses listing
	 * 
	 * HeadComment:
	 */
	public function addresses() {

		# initialize address markup builder to load template
		\Conference::service( "address.markupbuilder" );

		$markup = [
			"template" => "user_addresses",
			"templateSelect-template" => [
				"address_list" => [
					"custom-action" => $GLOBALS["CONREN"]["General"]["domain"] . "/user/address/register",
					"addresses" => \Conference::service( "address.manager" )->get( \Conference::currentUser()->get("uid"), "user_id", ["wrapInBag" => false, "key" => "uid"] )
				]
			]
		];

		return $markup;
	}

	/**
	 * returns the address registration form markup for a user
	 * 
	 * HeadComment:
	 */
	public function registerAddress() {

		# initialize address service to ensure the templates are loaded
		\Conference::service( "address.markupbuilder" );

		$markup = [
			"template" => "service_register_form",
			"templateSelect-template" => [

			]
		];

		return $markup;
	}

	/**
	 * returns the registration form of the user
	 * 
	 * HeadComment:
	 */
	public function registerForm() {

		$markup = [
			"template" => "conren_user_form_register",
			"templateSelect-template" => []
		];

		return $markup;
	}

	/**
	 * returns the registration form for a user address
	 * 
	 * HeadComment:
	 */
	public function registerAddressForm() {

		# initialize address templates
		\Conference::service( "address.markupbuilder" );

		$markup = [
			"template" => "address_register_form",
			"templateSelect-template" => [
				"form-destination" => "user/address/register",
				"address_types" => \Conference::service( "address.manager" )->types( "uid", ["wrapInBag" => false] ),
				"user_id" => \Conference::currentUser()->get("uid")
			]
		];

		return $markup;
	}
	
	/**
	 * returns the markup for the users listing
	 * 
	 * HeadComment:
	 */
	public function list() {

		$markup = [
			"template" => "conren_user_listing",
			"templateSelect-template" => []
		];

		$users = \Conference::service( "user.manager" )->getUserList();

		foreach( $users as $uid => $user )
			$markup["templateSelect-template"]["userlist"][] = $user->all();

		return $markup;
	}

	/**
	 * prepares this markup
	 * 
	 * HeadComment:
	 */
	public function prepare( array &$markup ) {

		return $this;
	}

	/**
	 * builds the user formular
	 * 
	 * HeadComment:
	 */
	public function loginFormular() {

		$user = \Conference::currentUser();
		
		$markup = array(
			"key" => "user-login",
			"template" => "conren_user_form_login"
		);
		
		return $markup;
	}

	/**
	 * builds the profile
	 * 
	 * HeadComment:
	 */
	public function profile( int $id ) {
		
		$user = \Conference::service( "user.manager" )->get( $id );

		$markup = [
			"template" => "conren_user_profile",
			"templateSelect-template" => []
		];

		# nothing more to render when no user is given
		if ( !$user )
			return $markup;
		
		$scope = &$markup["templateSelect-template"];
		$scope = $user->all();

		# adjust company link
		$scope["company-link"] = \Conference::generateLink( "/company/" . $user->get("company") );

		return $markup;
	}

	/**
	 * user profile teaser
	 * 
	 * HeadComment:
	 */
	public function userTeaser() {

		$user = \Conference::currentUser();

		$markup = array(
			"template" => "conren_user_teaser",
			"templateSelect-template" => array()
		);

		$scope = &$markup["templateSelect-template"];

		// user data
		$scope = array_merge( $scope, $user->all() );

		# add cart item count
		$scope["cart_item_count"] = \Conference::service( "cart.manager" )->count();

		return $markup;
	}
}

//_____________________________________________________________________________________________