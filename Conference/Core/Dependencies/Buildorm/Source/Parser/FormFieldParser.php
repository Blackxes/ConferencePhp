<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	uses the users form configurations and builds the form fields

	Author: Alexander Bassov
	Email: blackxes@gmx.de
	Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Buildorm\Source\Parser;

//_____________________________________________________________________________________________
class FormFieldParser {

	//_________________________________________________________________________________________
	public function __construct() {

		$this->localFieldIterator = 0;
	}

	//_________________________________________________________________________________________
	// parses the given field configuration into a field markup
	//
	// param1 (array) expects the field configuration
	// param2 (callable) expects the callback function into which the parsed field configuration
	//		of the user is passed which then can be used to build up the markup
	//		for the template parser
	//
	// return array - the markup for the given field configuration
	//
	public function parse( string $id, array $config ): array {

		if ( !$id || !count($config) || isset($config["render"]) && !$config["render"] )
			return array();
		
		$markup = array(
			"html_attribute_list" => $this->buildAttributeList(array(
				"class" => implode( " ", ["bd-formitem"] ),
				"data-bd-formselector" => "data-bd-form-{$id}"
			))
		);

		// pass parent parameter for further use
		$config["_fieldItemId"] = $id;

		// process
		$process = array(
			"label" => array( "args" => $config ), // label
			$config["type"] => array( "args" => $config ), // the input field
		);

		$markup["fields"] = $this->processFieldConfigs( $process );

		return $markup;
	}

	//_________________________________________________________________________________________
	// processes the given fields and their building callback and returns the created markup
	//
	public function processFieldConfigs( $fields ) {

		$markup = array();

		foreach( $fields as $func => $config ) {

			if ( !method_exists("\Buildorm\Source\Parser\FormFieldParser", $func) )
				continue;
			
			// define some extra values
			$config["_fieldIndex"] = $this->localFieldIterator;

			$markup[$this->localFieldIterator] = $this->$func( $config );

			$this->localFieldIterator++;
		}

		return $markup;
	}

	//_________________________________________________________________________________________
	// builds the label field
	//
	public function label( array $config ) {

		if ( is_null($config["args"]["label"]) || !count($config) )
			return array();

		$args = $config["args"];

		$markup = array(
			"field" => "bd_form_field_label",
			"templateSelect-field" => array(
				"html_attribute_list" => $this->buildAttributeList( array(
					"class" => implode( " ", ["bd-formfield-label"] ),
					"data-bd-fieldselector" => "bd-formfield-label-{$config["_fieldIndex"]}"
				)),
				"label" => $args["label"]
			),
		);

		return $markup;
	}

	//_________________________________________________________________________________________
	// parses the form field type text
	//
	public function text( array $config ): array {

		if ( is_null($config) || !count($config) ) return array();

		$args = $config["args"];
		$markup = array();
		$attributes = (isset($args["attributes"])) ? $args["attributes"] : array();

		$markup = array(
			"field" => "bd_form_field_input",
			"templateSelect-field" => array(
				"inputs" => array(
					array(
						"html_attribute_list" => $this->buildAttributeList( array_merge( array(
							"type" => "text",
							"placeholder" => $args["placeholder"],
							"value" => $args["defaultValue"],
							"class" => implode( " ", ["bd-formfield-text"] ),
							"data-bd-fieldselector" => "bd-formfield-text-{$config["_fieldIndex"]}",
							"name" => "Buildorm[BdFields][{$args["_fieldItemId"]}]",
							"required" => $args["required"]
						), $attributes ), true )
					)
				)
			)
		);

		return $markup;
	}

	//_________________________________________________________________________________________
	// parses the form field type text
	//
	public function password( array $config ): array {

		if ( is_null($config) || !count($config) ) return array();

		$args = $config["args"];

		// remove damaging attributes
		unset($args["attributes"]["defaultValue"]);
		
		// Todo: merge user defined attributes into attribute list!
		$attributes = (isset($args["attributes"])) ? $args["attributes"] : array();

		$markup = array(
			"field" => "bd_form_field_input",
			"templateSelect-field" => array(
				"inputs" => array(
					array(
						"html_attribute_list" => $this->buildAttributeList( array_merge(array(
							"type" => "password",
							"placeholder" => $args["placeholder"],
							"class" => implode( " ", [ "db-formfield-type-password" ] ),
							"data-bd-fieldselector" => "bd-formfield-password-{$config["_fieldIndex"]}",
							"name" => "Buildorm[BdFields][{$args["_fieldItemId"]}]",
							"required" => $args["required"]
						), $attributes), true )
					)
				)
			)
		);

		return $markup;
	}

	//_________________________________________________________________________________________
	// parses the form field type select
	//
	public function select( array $config ): array {

		if ( is_null($config) || !count($config) ) return array();

		$args = $config["args"];
		$attributes = (isset($args["attributes"])) ? $args["attributes"] : array();

		$markup = array( 
			"field" => "bd_form_field_select",
			"templateSelect-field" => array(
				"html_attribute_list" => $this->buildAttributeList( array_merge(array(
					"class" => implode( " ", [ "db-formfield-type-select" ] ),
					"data-bd-fieldselector" => "bd-formfield-select-{$config["_fieldIndex"]}",
					"name" => "Buildorm[BdFields][{$args["_fieldItemId"]}][]",
					"required" => $args["required"]
				), $attributes) , true),
				"options" => $this->selectPrepareOptions( $config )
			)
		);

		return $markup;
	}

	//_________________________________________________________________________________________
	// prepares the selection options markup for the select field building function
	//
	private function selectPrepareOptions( $config ): array {

		if ( !is_array($config["args"]["items"]) || !count($config["args"]["items"]) ) return array();

		$markup = array();
		$args = $config["args"];

		foreach( $args["items"] as $index => $config ) {

			// clean up values to apply operations easier
			$value = ( is_null($config["value"]) ) ? $index : $config["value"];
			$selected = ( !is_null($args["defaultValue"]) && $args["defaultValue"] == $value ) ? "selected" : null;
			$attributes = ( isset($config["attributes"]) && is_array($config["attributes"]) ) ? $config["attributes"] : array();

			$option = array(
				"html_attribute_list" => $this->buildAttributeList( array_merge(array(
					"value" => $value,
					"selected" => $selected
				), $attributes) , true ),
				"label" => $config["label"]
			);

			$markup[] = $option;
		}

		return $markup;
	}

	//_________________________________________________________________________________________
	// parses the form field type radio
	//
	public function radio( array $config ): array {

		if ( is_null($config) || !count($config) ) return array();
		
		$args = $config["args"];
		$attributes = (isset($args["attributes"])) ? $args["attributes"] : array();
		
		$markup = array( 
			"field" => "bd_form_field_input",
			"templateSelect-field" => array(
				"inputs" => $this->radioPrepareItems( $config )
			)
		);

		return $markup;
	}

	//_________________________________________________________________________________________
	//
	public function radioPrepareItems( $config ): array {

		if ( !count($config["args"]["items"]) ) return array();

		$markup = array();
		$args = $config["args"];

		foreach( $args["items"] as $index => $config ) {

			// prepare values for easier operations
			$value = ( is_null($config["value"]) ) ? $index : $config["value"];
			$attributes = (isset($config["attributes"])) ? $config["attributes"] : array();
			$selected = ( !is_null($args["defaultValue"] ) && $args["defaultValue"] == $value ) ? "selected" : null;
			
			$option = array(
				"html_attribute_list" => $this->buildAttributeList( array_merge(array(
					"type" => "radio",
					"name" => "Buildorm[BdFields][{$args["_fieldItemId"]}]",
					"value" => $value,
					"selected" => $selected
				), $attributes) , true ),
				"label" => $config["label"]
			);

			$markup[] = $option;
		}

		return $markup;
	}

	//_________________________________________________________________________________________
	// parses the form field type checkbox
	//
	public function checkbox( array $config ): array {

		if ( is_null($config) || !count($config) ) return array();

		$markup = array();
		$args = $config["args"];
		$attributes = (isset($args["attributes"])) ? $args["attributes"] : array();

		$markup = array(
			"field" => "bd_form_field_input",
			"templateSelect-field" => array(
				"inputs" => array(
					array(
						"html_attribute_list" => $this->buildAttributeList( array_merge(array(
							"type" => "checkbox",
							"class" => implode( " ", [ "db-formfield-type-checkbox" ] ),
							"data-bd-fieldselector" => "bd-formfield-checkbox-{$config["_fieldIndex"]}",
							"name" => "Buildorm[BdFields][{$args["_fieldItemId"]}]",
							"required" => $args["required"],
							"checked" => $args["defaultValue"]
						), $attributes), true )
					)
				)
			)
		);

		return $markup;
	}

	//_________________________________________________________________________________________
	// parses the form field type hidden
	//
	public function hidden( array $config ): array{

		if ( is_null($config) || !count($config) ) return array();

		$args = $config["args"];
		$markup = array();
		$attributes = (isset($args["attributes"])) ? $args["attributes"] : array();

		$markup = array(
			"field" => "bd_form_field_input",
			"templateSelect-field" => array(
				"inputs" => array(
					array(
						"html_attribute_list" => $this->buildAttributeList( array_merge( array(
							"type" => "hidden",
							"value" => ( isset($config["value"]) ) ? $config["value"] : null,
							"class" => implode( " ", ["bd-formfield-hidden"] ),
							"data-bd-fieldselector" => "bd-formfield-hidden-{$config["_fieldIndex"]}",
							"name" => "Buildorm[BdFields][{$args["_fieldItemId"]}]",
						), $attributes ), true )
					)
				)
			)
		);

		return $markup;
	}

	//_________________________________________________________________________________________
	// parses the form field type submit
	//
	public function submit( array $config ): array{

		if ( is_null($config) || !count($config) ) return array();

		$args = $config["args"];
		$markup = array();
		$attributes = (isset($args["attributes"])) ? $args["attributes"] : array();

		$markup = array(
			"field" => "bd_form_field_input",
			"templateSelect-field" => array(
				"inputs" => array(
					array(
						"html_attribute_list" => $this->buildAttributeList( array_merge( array(
							"type" => "submit",
							"value" => ( isset($args["submitLabel"]) ) ? $args["submitLabel"] : "Senden",
							"class" => implode( " ", ["bd-formfield-submit"] ),
							"data-bd-fieldselector" => "bd-formfield-submit-{$config["_fieldIndex"]}",
						), $attributes ), true )
					)
				)
			)
		);

		return $markup;
	}

	//_________________________________________________________________________________________
	// build attributes list
	//
	// param1 (array) expects the attributes as "attribute" => "value"
	// param2 (boolean) describes wether empty value are being filtered
	//
	// return array - the markup for the attribute template
	//
	public function buildAttributeList($attributes, bool $filter = false ) {

		if ( !$attributes || !count($attributes) ) return array( "attributes" => array() );

		$markup = array( "attributes" => array() );

		foreach( $attributes as $attribute => $value ) {
			if ( $value === NULL && $filter )
				continue;
			else
				$markup["attributes"][] = array( "attribute" => $attribute, "value" => $value );
		}

		return $markup;
	}
}

//_____________________________________________________________________________________________
//