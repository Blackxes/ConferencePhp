<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	form for registaring a new user

	Author: Alexander Bassov
	Email: blackxes@gmx.de
	Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Forms;

//_____________________________________________________________________________________________
class UserRegistrationForm extends \Buildorm\Source\Base\FormBase {
	
	protected $id = null;
	protected $config = array();
	protected $fields = array();

	protected $target = "";
	protected $method = "";

	//_________________________________________________________________________________________
	public function __construct() {
		
		parent::__construct();

		$this->id = "userRegister";
		$this->method = "post";
		$this->target = $GLOBALS["Conference"]["General"]["domain"] . "/user/register";
	}

	//_________________________________________________________________________________________
	// builds the configuration for the form
	//
	// return boolean
	//		true - when the build was successful
	//		false - when something failed - form wont be rendered when false!
	//
	public function build(): bool {

		// username
		$this->fields["username"] = array(
			"type" => "text",
			"required" => true,
			"label" => "Benutzername",
		);

		// email
		$this->fields["email"] = array(
			"type" => "text",
			"required" => true,
			"label" => "Email"
		);

		// password and password verification
		$this->fields["password"] = array(
			"type" => "password",
			"required" => true,
			"label" => "Passwort"
		);
		$this->fields["password-verify"] = array(
			"type" => "password",
			"required" => true,
			"label" => "Passwort wiederholen"
		);

		// company
		$this->fields["company"] = array(
			"label" => "Betrieb",
			"type" => "select",
			"required" => true,
			"items" => \Conference::service( "companyContentBuilder" )->buildSelectionItems()
		);

		// submit..
		$this->fields["submit"] = array(
			"type" => "submit",
			"attributes" => array(
				"value" => "Registrieren"
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

		$invalid = array();

		// password needs to match the verification
		if ( $attributes["password"] != $attributes["password-verify"] )
			$invalid["password"] = "password needs to match the verification";

		// check if user already exist
		if ( \Conference::userHandler()->existByUsername($attributes["username"]) )
			$invalid["username"] = "username '{$attributes["username"]}' already exist";
		
		return $invalid;
	}

	//_________________________________________________________________________________________
	// is called when the verification failed
	public function fail( array $invalid ) {

		foreach( $invalid as $attribute => $message )
			\Conference::log( $message, "error", 0 );
		
		return true;
	}

	//_________________________________________________________________________________________
	// is called when the verification succeeded
	public function success( array $attributes ) {

		if ( !\Conference::userHandler()->register($attributes) )
			return \Conference::log( "Error occured while registering user '{$attributes["username"]}'", "error", 0, false );
		
		return \Conference::log( "User '{$attributes["username"]}' registered", "success", 0, true );
	}
}

//_____________________________________________________________________________________________
//