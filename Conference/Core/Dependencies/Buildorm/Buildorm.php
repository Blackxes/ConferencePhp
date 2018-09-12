<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	form building class to dynamically create forms

	Author: Alexander Bassov
	Email: blackxes@gmx.de
	Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Buildorm;

use \Buildorm\Source\Manager;
use \Buildorm\Source\Parser;

define( "BUILDORM_ROOT", __DIR__ );

require_once ( BUILDORM_ROOT . "/Configuration.php" );
require_once ( BUILDORM_ROOT . "/Source/Manager/FormManager.php" );
require_once ( BUILDORM_ROOT . "/Source/Parser/FormParser.php" );

// include library
require_once ( BUILDORM_ROOT . "/Source/Base/FormBase.php" );

//_____________________________________________________________________________________________
class Buildorm {

	static private $defined = false;
	
	static public $fManager = null;
	static private $fParser = null;
	
	//_________________________________________________________________________________________
	// defines the class
	//
	// param1 (array) expects the form configurations
	//		"formid" => "\YourFormClass" (the class needs to extends from the form base)
	//
	static public function define( array $forms, array $templates = array() ): bool {

		if ( $defined ) return \Conference::log( "Templax: Parser already defined - reset first or use the associated class operations", "error", 0, false );

		// check dependencies
		if ( !self::checkDependencies( $GLOBALS["Buildorm"]["Dependencies"], function($file) {
			print_r("Buildorm: Dependency {$name} ({$file}) is missing");
		}))
		{
			return false;
		}

		self::$fManager = new Manager\FormManager();
		self::$fParser = new Parser\FormParser();

		// initialize libraries
		\Templax\Templax::templateManager()->registerTemplateSet( $GLOBALS["Buildorm"]["Templates"] );

		if ( !self::$fManager->registerFormSet($forms) ) return false;
		if ( !\Templax\Templax::templateManager( $templates) ) return false	;

		// check if a form has been send
		$formid = ( isset($_POST["Buildorm"]["BdFields"]["bd-verify-form"]) )
			? $_POST["Buildorm"]["BdFields"]["bd-verify-form"]
			: $_GET["Buildorm"]["BdFields"]["bd-verify-form"];
		
		if ( $formid !== null )
			self::verifyForm( $formid );

		self::$defined = true;

		return true;
	}

	//_________________________________________________________________________________________
	// builds a formular
	//
	// param1 (string) expects the form id
	//
	// return string - the parsed form
	//
	static public function build( string $id ): string {

		// check form existance
		if ( !self::$fManager->has($id) )
			return "";

		// create form instance and build
		$form = self::$fManager->getInstanciated( $id );

		if ( !$form ) return \Conference::log( "Buildorm: form not found: {$id}", "error", 0, false );

		// check form building
		if ( !$form->build() ) return \Conference::log( "Buildorm: building form failed: {$id}", "error", 0, false );
		
		$content = self::$fParser->parse( $form );

		return $content;
	}

	//_________________________________________________________________________________________
	// verifies a submitted form
	//
	static private function verifyForm( $formid ) {

		if ( !$formid ) return false;

		// get form and invoke the verification callback
		$form = self::$fManager->getInstanciated( $formid );

		if ( $form === null ) return false;

		// get form data
		$data = ( isset($_POST["Buildorm"]["BdFields"]) )
			? $_POST["Buildorm"]["BdFields"]
			: $_GET["Buildorm"]["BdFields"];

		$invalid = $form->verify( $data );

		if ( count($invalid) )
			return $form->fail( $invalid );

		// execute success and redirect afterwards
		$form->success( $data );
		
		// on success redirect to the given link when given
		$target = $form->getTarget();

		if ( !$target )
			return true;
			
		header( "Location: " . $target );
		exit();
	}

	//_________________________________________________________________________________________
	// checks wether all dependencies are given
	//
	// param1 (array) expects the dependencies
	//		$libraryName => $pathToFile
	// param2 (callback) expects the callback that invokes when a missing dependency has been found
	//		callback parameteres
	//			param1 (string) expects the name of the library
	//			param2 (string) expects the path to the file
	//
	// return boolean
	//		true - when all dependencies are present
	//		false - when dependencies are missing - pull the logs from the internal logfile
	//
	static private function checkDependencies( array $dependencies, callable $callback = null,
		bool $cancelOnFirstFound = false): bool
	{

		// dont need to check when there are no dependencies
		if ( count($dependencies) )
			return true;

		$valid = true;
		
		// check dependency existance and react how defined
		// pass file to callback and return when necessary
		//
		foreach( $dependencies as $name => $file ) {

			if ( !file_exists($file) && $callback !== null ) {

				$callback( $name, $file );

				if ( $cancelOnFirstFound ) return false;
				else if ( $valid ) $valud = false;
			}
		}

		return $valid;
	}
}

//_____________________________________________________________________________________________
//