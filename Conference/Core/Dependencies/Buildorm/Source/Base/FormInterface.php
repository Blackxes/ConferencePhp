<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	interface for a formular

	Author: Alexander Bassov
	Email: blackxes@gmx.de
	Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Buildorm\Source\Base;

require_once ( BUILDORM_ROOT . "/Source/Base/FormBase.php" );

//_____________________________________________________________________________________________
interface FormInterface {

	//_________________________________________________________________________________________
	public function __construct();

	//_________________________________________________________________________________________
	// builds the configuration for the form
	//
	// return boolean
	//		true - when the build was successful
	//		false - when something failed - form wont be rendered when false!
	//
	public function build(): bool;

	//_________________________________________________________________________________________
	// verifies the form when submitted
	//
	// param1 (array) expects the submitted form values
	//
	// return array - containing the attributes which are not valid
	//
	public function verify( array $attributes ): array;

	//_________________________________________________________________________________________
	// is called when the verification failed
	public function fail( array $invalid );

	//_________________________________________________________________________________________
	// is called when the verification succeeded
	public function success( array $attributes );
}

//_____________________________________________________________________________________________
//