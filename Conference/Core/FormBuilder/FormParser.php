<?php
/**********************************************************************************************
 * 
 * parses a form configuration
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\FormBuilder;

class FormParser {
	
	/**
	 * construction
	 */
	public function __construct() {

		/* nothing to do in here */
	}
	
	/**
	 * parses a form configuration
	 */
	public function parse( \Conference\Core\FormBuilder\FormBase $form ) {

		if ( !$form->validate() )
			return \Conference::log( "Could'nt parse given form {$form->get("key")}", "error", $this, false );
		
		$markup = [];
		$builder = $this->fieldBuilder;
		
		# builds fields
		foreach( $form->fields as $key => $config )
			$markup["fields"][] = $builder->build( $key, $config );
		
		# additional fields for identification
		$presets = \Conference\Core\FormBuilder\FormPresets::getPresets();
		$config = $form->config;

		$markup["fields"][] = $builder->build( "c7-form-key", $presets->formIdentification($form->key) );
		$markup["fields"][] = $builder->build( null, $presets->submit($config[""]) );

		return $markup;
	}
}