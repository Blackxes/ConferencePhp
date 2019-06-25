<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * HeadComment:
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Rendering\ContentBuilding;

//_____________________________________________________________________________________________
class FormMarkupBuilder extends namespace\MarkupBuilderBase {

	/**
	 * construction
	 */
	public function __construct() {

		parent::__construct([
			// "templates" => []
		]);
	}

	/**
	 * prepares the markup
	 * 
	 * HeadComment:
	 */
	public function prepare( array &$markup ) {

		return $this;
	}
}

//_____________________________________________________________________________________________
//