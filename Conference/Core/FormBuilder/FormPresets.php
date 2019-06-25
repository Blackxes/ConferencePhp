<?php
/**********************************************************************************************
 * 
 * provides form markup presets as callables
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\FormBuilder;

class FormPresets extends \Conference\Core\Classes\ParameterBag {
	
	/**
	 * construction
	 */
	private function __construct() {
		/* no construction needed */
	}

	/**
	 * a preset for a submit button
	 */
	static public function submit( $id, $value = "Send" ) {

		return [
			"id" => $id,
			"type" => "submit",
			"value" => $value
		];
	}

	/**
	 * a preset for a form identification input field
	 */
	static public function formIdentification( $id ) {

		return [
			"type" => "hidden",
			"value" => $id
		];
	}
}
