<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	formbase for custom forms

	Author: Alexander Bassov
	Email: blackxes@gmx.de
	Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Buildorm\Source\Base;

require_once ( BUILDORM_ROOT . "/Source/Base/FormInterface.php" );

//_____________________________________________________________________________________________
abstract class FormBase implements namespace\FormInterface {
	
	protected $id = null;
	protected $config = array();
	protected $fields = array();

	protected $target = "";
	protected $method = "";

	//_________________________________________________________________________________________
	public function __construct() {
		
		$this->config = array(
			"render" => true
		);
	}

	//_________________________________________________________________________________________
	// builds the configuration for the form
	//
	// return boolean
	//		true - when the build was successful
	//		false - when something failed - form wont be rendered when false!
	//
	abstract public function build(): bool;

	//_________________________________________________________________________________________
	// verifies the form when submitted
	//
	// param1 (array) expects the submitted form values
	//
	// return array - containing the attributes which are not valid
	//
	abstract public function verify( array $attributes ): array;

	//_________________________________________________________________________________________
	// is called when the verification failed
	abstract public function fail( array $invalid );

	//_________________________________________________________________________________________
	// is called when the verification succeeded
	abstract public function success( array $attributes );

	//_________________________________________________________________________________________
	// basic setter/getter
	//
	public function setId( string $id ) { $this->id = $id; }
	public function setConfigs( array $config ) { $this->config = $config; }
	public function setConfig( string $key, $value ) { $this->config[$key] = $value; }
	public function setFields( array $fields ) { $this->fields = $fields; }
	public function setField( $index, $field ) { $this->fields[$index] = $fields; }
	
	public function setTarget( $target ) { $this->target = $target; }
	public function setMethod( $method ) { $this->target = $method; }
	//
	public function getId(): string { return $this->id; }
	public function getConfigs(): array { return $this->config; }
	public function getConfig( string $key ) { return $this->config[$key]; }
	public function getFields(): array { return $this->fields; }
	public function getField( $index ): array { return $this->fields[$index]; }

	public function getTarget() { return $this->target; }
	public function getMethod() { return $this->method; }
	//
}

//_____________________________________________________________________________________________
//