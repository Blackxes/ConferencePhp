<?php
/**********************************************************************************************
 * 
 * base class for a custom form
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\FormBuilder;

abstract class FormBase extends \Conference\Core\Classes\ParameterBag {

	/**
	 * form key
	 * 
	 * @var string
	 */
	public $key;

	/**
	 * title of this form
	 * 
	 * @var string
	 */
	public $config;

	/**
	 * contains the form fields
	 * 
	 * @var array
	 */
	private $fields;

	/**
	 * construction
	 */
	public function __construct( string $key ) {
		
		$this->key = $key;
		$this->config = [];
		$this->fields = [];
	}

	/**
	 * builds the form configuration
	 * 
	 * @return array - 
	 */
	abstract public function build(): \Conference\Core\FormBuilder\FormBase;

	/**
	 * validates this form configuration
	 */
	final public function validate() {
		
		// Todo: check for validation
		return true;
	}

	/**
	 * verification when this form has been submitted
	 * 
	 * @return array - contains the errors
	 * 	when empty the verification was successful
	 */
	abstract public function verify( $data ): array;

	/**
	 * will be executed when the verification fails
	 */
	abstract public function error();
}