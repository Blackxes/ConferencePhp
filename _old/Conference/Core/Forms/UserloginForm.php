<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	formbase for custom forms

	Author: Alexander Bassov
	Email: blackxes@gmx.de
	Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Forms;

//_____________________________________________________________________________________________
class UserloginForm extends \Buildorm\Source\Base\FormBase {

	//_________________________________________________________________________________________
	public function __construct() {

		parent::__construct();

		$this->id = "userlogin";
		$this->target = $GLOBALS["Conference"]["General"]["domain"] . "/user/login";
		$this->method = "post";
	}
	
	//_________________________________________________________________________________________
	// builds the configuration for the form
	//
	// return boolean
	//		true - when the build was successful
	//		false - when something failed - form wont be rendered when false!
	//
	public function build(): bool {

		// fields
		$this->fields["username"] = array(
			"type" => "text",
			"required" => true,
			"placeholder" => "Benutzer",
		);

		$this->fields["password"] = array(
			"type" => "password",
			"placeholder" => "Passwort",
			"attributes" => array(),
			"required" => true,
		);
		
		$this->fields["submit"] = array(
			"type" => "submit",
			"attributes" => array(
				"value" => "Login"
			)
		);

		return true;
	}

	//_________________________________________________________________________________________
	// verifies the form when submitted
	//
	// param1 (array) expects the submitted form values
	//
	// return array - containing the attributes which are not valid
	//
	public function verify( array $attributes ): array {

		if ( !\Conference::userHandler()->verifyUserLogin($attributes["username"], $attributes["password"]) )
			return array( 1 );

		return array();
	}

	//_________________________________________________________________________________________
	// is called when the verification failed
	public function fail( $invalid ): bool {
		print_r("fail");
		exit;
	}

	//_________________________________________________________________________________________
	// is called when the verification succeeded
	public function success( $attributes ): bool {

		\Conference::userHandler()->loginUser( $attributes["username"] );
		\Conference::redirect( "/user/profile" );
	}
}

//_____________________________________________________________________________________________
//