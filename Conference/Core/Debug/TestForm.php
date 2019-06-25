<?php
/**********************************************************************************************
 * 
 * builds a templax form configuration array
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Debug;

class TestForm extends \Conference\Core\FormBuilder\FormBase {

	/**
	 * construction
	 */
	public function __construct( string $key ) {

		parent::__construct( $key, "Testform" );
	}

	/**
	 * builds the form configuration
	 */
	public function build(): \Conference\Core\FormBuilder\FormBase {

		$this->config = [
			"action" => "test/testform",
			"method" => "get",
			"target" => "_top"
		];

		$this->fields["firstname"] = [
			"type" => "text",
			"defaultValue" => "Default Vorname",
			"placeholder" => "Firts Name",
			"required" => true,
			"disabled" => false,

		];

		$this->fields["password"] = [
			"type" => "password",
			"placeholder" => "Password",
			"required" => true,

		];

		return $this;
	}

	/**
	 * verification when this form has been submitted
	 * 
	 * @return array - contains the errors
	 * 	when empty the verification was successful
	 */
	public function verify( $data ): array {

		debug( "verification" );

		return [];
	}

	/**
	 * will be executed when the verification fails
	 */
	public function error() {

		debug( "error in form {$this->key}" );
	}
}