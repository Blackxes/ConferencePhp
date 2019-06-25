<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * user relevant content building
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Packages\User;

//_____________________________________________________________________________________________
class UserContentBuilder {

	public function __construct() {
		\Conference::service("templax")->registerTemplateSet( array(
			"user_login_form" => array( "file" => CONREN_PACKAGE_FOLDER . "/User/user.html", "marker" => "USER_LOGIN_FORMULAR" ),
			"user_register_form" => array( "file" => CONREN_PACKAGE_FOLDER . "/User/user.html", "marker" => "USER_REGISTER_FORMULAR" ),
			"user_profile" => array( "file" => CONREN_PACKAGE_FOLDER . "/User/user.html", "marker" => "USER_PROFILE" )
		));
	}

	/**
	 * builds the user formular
	 */
	public function userLoginFormular() {

		echo \Conference::service( "templax" )->parse( "user_login_form", array(
			"email" => (string) $_POST["email"]
		));
		
		exit;
	}

	/**
	 * builds the profile
	 */
	public function userProfile() {
		
	}
}

//_____________________________________________________________________________________________