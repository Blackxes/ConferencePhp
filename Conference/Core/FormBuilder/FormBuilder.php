<?php
/**********************************************************************************************
 * 
 * builds a templax form configuration array
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\FormBuilder;

class FormBuilder {

	/**
	 * list of form keys
	 * 
	 * @var array
	 * 	key: string => class: string (\Conference\Core\FormBulder\FormBase)
	 */
	private $forms;

	/**
	 * construction
	 */
	public function __construct( array $forms = [] ) {
		
		$this->forms = $forms;
	}

	/**
	 * builds a form configuration
	 */
	public function build( string $key ) {

		$form = $this->get( $key );

		if ( !$form )
			return \Conference::log( "Could'nt build form. See logfile for more information", "error", $this, [] );
		
		$form = ( new $this->forms[$key]($key) )->build();

		exit;
	}

	/**
	 * returns true/false wether the passed form key exists or not
	 * Note: it does not check wether the given form config is valid or not!
	 * 
	 * @param string $key - the form key
	 * 
	 * @return boolean - true when exists false when not
	 */
	public function has( string $key ) {

		return array_key_exists( $key, $this->forms );
	}

	/**
	 * checks wether the form is defined and valid
	 * 
	 * @param string $key - the form key
	 * 
	 * @return boolean - true on valid else false
	 */
	public function validate( string $key ) {

		if ( !$this->has($key) )
			return \Conference::log( "Formkey '$key' not found", "error", $this, false );
		
		$className = $this->forms[ $key ];

		if ( !class_exists($className) )
			return \Conference::log( "Invalid class name '$key' for form configuration", "error", $this, false );
		
		if ( is_subclass_of($className, "\Conference\Core\FormBuilder\FormBase") )
			return \Conference::log( "Not valid form configuration in form '$key'. Needs to extend from \Conference\Core\FormBuilder\FormBase", "error", $this, false );
		
		$instance = ( new $this->forms[$key] );

		return $this->has($key) && $this->forms[$key]->valid();
	}

	/**
	 * returns a form configuration
	 * 
	 * @param string $key - the form key
	 * @param boolean $validate - defines wether the form config shall be validated before returning
	 * 
	 * @return string - the form config instance
	 * @return false - when $validate is true and the form is invalid
	 * @return null - when the key is not defined
	 */
	public function get( string $key ) {
		
		return $this->forms[$key];
	}
}