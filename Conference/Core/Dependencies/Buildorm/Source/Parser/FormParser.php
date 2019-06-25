<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	parser for the form building

	Author: Alexander Bassov
	Email: blackxes@gmx.de
	Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Buildorm\Source\Parser;

use \Buildorm\Source\Base;

require_once ( BUILDORM_ROOT . "/Source/Parser/FormFieldParser.php" );


//_____________________________________________________________________________________________
class FormParser {

	private $fieldParser = null;

	//_________________________________________________________________________________________	
	public function __construct() {

		$this->fieldParser = new namespace\FormFieldParser();
	}

	//_________________________________________________________________________________________
	// parses the given form into a markup and returns its markup
	//
	// param1 (\Buildorm\Source\Base\BaseForm) expects the built form
	//
	// return string - the parsed form
	//
	public function parse( Base\FormBase $form ) {

		// deny parsing when rendering is not allowed
		if ( !$form->getConfig("render") ) return "";

		
		
		// parse fields
		$markup = array(
			"html_attribute_list" => $this->fieldParser->buildAttributeList( array(
				"class" => implode( " ", ["bd-form", "bd-form-{$form->getId()}", "box"] ),
				"data-bd-formselector" => $form->getId(),
				"method" => $form->getMethod(),
				"action" => $form->getTarget()
			))
		);

		// iterator count
		$itemIterator = 0;

		// insert hidden fields at first
		$markup["items"][$itemIterator]["fields"][] = $this->fieldParser->hidden( array( "args" => array(
			"id" => "bd-verify-{$form->getId()}",
			"attributes" => array(
				"name" => "Buildorm[BdFields][bd-verify-form]",
				"value" => $form->getId()
			)
		)));
		$itemIterator++;
		
		foreach( $form->getFields() as $id => $config ) {
			
			// parse field configuration and store markup
			$markup["items"][$itemIterator] = $this->fieldParser->parse( $id, $config );
			$markup["items"][$itemIterator]["html_attribute_list"]["attributes"][] = array(
				"attribute" => "data-bd-itemselector",
				"value" => "bd-form-{$id}-{$itemIterator}"
			);

			$itemIterator++;
		}
		
		
		
		return \Templax\Templax::parse( "base", $markup );
	}
	
}

//_____________________________________________________________________________________________
//