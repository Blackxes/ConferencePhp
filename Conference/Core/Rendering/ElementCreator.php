<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * creates markup configurations for the templax parser
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Rendering;

//_____________________________________________________________________________________________
class ElementCreator {

	/**
	 * construction
	 * 
	 * no construction needed
	 */
	private function __construct() { /* nothing to do here */ }

	/**
	 * creates an attribute list
	 */
	static public function attributeList( array $attr ) {
		
		$markup = array();

		foreach( $attr as $name => $value )
			$markup["attributes"][] = array( "attribute" => $name, "value" => $value );
		
		return $markup;
	}

	/**
	 * creates singletag element
	 */
	static public function singleTag( $tag, $attributes ) {

		$markup = array(
			"element" => "conren_htmlelement_singletag",
			"templateSelect-element" => array(
				"tag" => $tag,
				"conren_attribute_list" => self::attributeList( $attributes )
			)
		);

		return $markup;
	}

	// /**
	//  * creates multitag element
	//  * 
	//  * @param string $tag 
	//  */
	// static public function multiTag( string $tag, array $attributes = array() , array $elements = array() ) {

	// }

	/**
	 * create meta tag set
	 */
	static public function meta( array $config ) {

		$markup = array();

		foreach( $config as $i => $attr )
			$markup[] = self::singleTag( "meta", $attr );

		return $markup;
	}

	/**
	 * creates a styletag
	 */
	static public function style( array $config ) {

		$markup = array();

		foreach( $config as $i => $href ) {

			$markup[] = self::singleTag( "link", array(
				"type" => "text/css",
				"rel" => "stylesheet",
				"href" => $href
			));
		}

		return $markup;
	}

}

//_____________________________________________________________________________________________
//