<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	manages the form configurations

	Author: Alexander Bassov
	Email: blackxes@gmx.de
	Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Buildorm\Source\Manager;

//_____________________________________________________________________________________________
class FormManager {

	private $forms;

	//_________________________________________________________________________________________
	public function __construct( array $forms = array() ) {

		// when forms are given
		if ( count($forms) )
			$this->registerFormSet( $forms );
		else $this->forms = array();
	}

	//_________________________________________________________________________________________
	// registers a set of forms at once
	//
	// param1 (array) expects the form configurations
	//			"formid" => "YourFormClass" (needs to extends from \Buildorm\Source\Base\FormBase)
	//
	// return boolean
	//		true - when all sets are registered
	//		false - when something went bad - see more information in the Buildorm logfile
	//			- one set contains an empty id or is not a string
	//			- one set contains a null class
	//
	public function registerFormSet( array $forms ): bool {

		// nothing to register when empty
		if ( !count($forms) ) return true;

		// Todo: rewrite foreach / duplicated check for the $cancelOnFirstFound variable
		foreach( $forms as $id => $class ) {
			
			// invalid class
			if ( !$class ) return \Conference::log( "Buildorm: invalid class (id: {$id})", "error", 0, false );

			// class doesnt exist
			if ( !class_exists($class) ) return \Conference::log( "Buildorm: class '{$class}' doesnt exist (id: {$id})", "error", 404, false );

			// not supported class because it doesnt extend the functionalites from base
			if ( !is_subclass_of($class, "\Buildorm\Source\Base\FormBase") )
				return \Conference::log("Buildorm: class needs to extends from Buildorm\Source\Base\FormBase (id: {$id})", "error", 0, false );
			
			if ( !$this->register( $id, $class ) )
				return false;
		}

		return true;
	}

	//_________________________________________________________________________________________
	// registers a form
	//
	// param1 (string) expects the form id
	// param2 (string) expects the form class name
	//
	// return boolean
	//		true - when the form has been registered
	//		false - when the form contains invalid values
	//			see Buildorms logfile for more information
	//
	public function register( string $id, string $form ): bool {

		// empty ids are prohibited
		if ( !$id || !is_string($id) ) return \Conference::log( "Buildorm: invalid id for form (id: {$id})", "error", 0, false );

		// duplicated forms are not allowed as well
		// Todo: define overwritting of duplicated forms in the configurations
		if ( $this->has($id) ) \Conference::log( "Buildorm: duplicated form ({$id})", "error", 0, false );

		// final registration
		$this->forms[$id] = $form;
		
		return true;
	}

	//_________________________________________________________________________________________
	// returns the existance of a form as boolean
	//
	// param1 (string) expects the form id
	//
	// return boolean
	//		true - when the form exists
	//		false - when the form does not exist
	//
	public function has( string $id ): bool {

		return isset( $this->forms[$id] );
	}

	//_________________________________________________________________________________________
	// returns the forms or the requested
	//
	// param1 (optional) (string) expects the form id
	//
	// return string - the class the user has registered
	//
	public function get( ?string $id ) {
		
		if ( $id ) return $this->forms[$id];
		else return $this->forms;
	}

	//_________________________________________________________________________________________
	// returns the form instantiated
	//
	// param1 (string) expects the form id
	//
	// return CustomUserClass
	//
	public function getInstanciated( string $id ) {

		$formName = $this->get( $id );

		if ( !$formName ) return null;

		return new $formName();
	}
}

//_____________________________________________________________________________________________
//