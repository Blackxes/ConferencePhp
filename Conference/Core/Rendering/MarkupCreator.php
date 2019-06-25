<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * builds markup configurations for the template parser
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Rendering;

//_____________________________________________________________________________________________
class MarkupCreator {

	/**
	 * no construction needed since its only static function usage
	 */
	private function __construct() {}
	
	/**
	 * builds markup for the "templateSelect" command
	 */
	static public function buildTemplateSelect( $key, $template, $markup ) {

		return array(
			$key => $template,
			"templateSelect-{$key}" => $markup,
		);
	}

	/**
	 * builds markup for the "foreach" command
	 */
	static public function buildForeach( $key, $items ) {

		return array( $key => $items );
	}
}

//_____________________________________________________________________________________________
//