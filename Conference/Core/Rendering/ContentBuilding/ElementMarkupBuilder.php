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
class ElementMarkupBuilder extends namespace\MarkupBuilderBase {

	/**
	 * construction
	 */
	public function __construct() {

		parent::__construct([
			"templates" => [
				"conren_message", "conren_attribute_list", "conren_html_singletag", "conren_html_multitag", "conren_link",
				"conren_style", "conren_script", "conren_meta"
			],
			"options" => [ "file" => CONREN_PATH_TEMPLATES_ABS . "/elements.html" ]
		]);
	}

	/**
	 * creates a raw attribute list
	 * 
	 * HeadComment:
	 */
	static public function rawAttributeList( array $attr ) {
		
		$markup = array();

		foreach( $attr as $name => $value )
			$markup["attributes"][] = array( "attribute" => $name, "value" => $value );
		
		return $markup;
	}

	/**
	 * creates a wrapped attribute list
	 */
	static public function attributeList( $attr ) {
		return [ "conren_attribute_list" => self::rawAttributeList($attr) ];
	}

	/**
	 * creates a link markup
	 * 
	 * HeadComment:
	 */
	static public function link( string $label, string $url, string $type = "regular" ) {

		$markup = [
			"template" => "conren_link",
			"templateSelect-template" => [
				"name" => $label,
				"link" => $url,
				"type" => $type
			]
		];

		return $markup;
	}

	/**
	 * creates singletag element
	 * 
	 * HeadComment:
	 */
	static public function singleTag( $tag, array $attributes = null ) {

		$markup = array(
			"template" => "conren_html_singletag",
			"templateSelect-template" => array(
				"tag" => $tag,
				"conren_attribute_list" => self::attributeList( (array) $attributes )
			)
		);

		return $markup;
	}

	/**
	 * creates a simple message markup
	 * 
	 * HeadComment:
	 */
	static public function message( string $message ) {

		$markup = [
			"template" => "conren_message",
			"templateSelect-template" => [
				"message" => $message
			]
		];
		
		return $markup;
	}

	/**
	 * createa a metatag markup
	 * 
	 * HeadComment:
	 */
	static public function meta( string $name, string $content ) {

		$markup = [
			"template" => "conren_meta",
			"templateSelect-template" => [
				"name" => $name,
				"content" => $content
			]
		];
		
		
		return $markup;
	}

	/**
	 * creates a multitag markup
	 * 
	 * HeadComment:
	 */
	static public function multiTag( $tag, array $attributes = null, $elements = null ) {

		$markup = array(
			"template" => "conren_html_multitag",
			"templateSelect-template" => array(
				"tag" => $tag,
				"conren_attribute_list" => self::attributeList( (array) $attributes ),
				"content" => null,
				"elements" => null
			)
		);

		// when array there are multiple content element
		if ( is_array($config) )
			$markup["templateSelect-template"]["elements"] = $elements;
		else
			$markup["templateSelect-template"]["content"] = (string) $elements;
		
		return $markup;
	}

	/**
	 * prepares the markup
	 * 
	 * HeadComment:
	 */
	public function prepare( array &$markup ) {

		return $this;
	}

	/**
	 * creates a script markup
	 */
	static public function script( $value, $inline = false ) {

		$markup = [
			"template" => "conren_script",
			"templateSelect-template" => []
		];
		
		# use value as script source or the actual script content
		if ( $inline )
			$markup["templateSelect-template"]["content"] = $value;
		else
			$markup["templateSelect-template"]["source"] = $value;
		
		return $markup;
	}

	/**
	 * creates a styletag
	 * 
	 * HeadComment:
	 */
	static public function style( $href ) {

		$markup = [
			"template" => "conren_style",
			"templateSelect-template" => [
				"source" => $href
			]
		];

		return $markup;
	}
}

//_____________________________________________________________________________________________
//