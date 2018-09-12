<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * the base for a controller
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Controller;

use \Conference\Core\Routing;

//_____________________________________________________________________________________________
abstract class ControllerBase {

	/**
	 * the template for this controller
	 * 
	 * @var string
	 */
	public $template;

	/**
	 * markup
	 * 
	 * @var array|null
	 */
	public $markup;

	/**
	 * options
	 * 
	 * @var array|null
	 */
	public $options;

	/**
	 * markup hooks
	 * 
	 * @var array|null
	 */
	public $hooks;


	/**
	 * construction
	 */
	public function __construct( array $markup = array(), array $options = array(), array $hooks = array() ) {

		$renderer = \Conference::renderer();

		$this->template = $GLOBALS["CONREN"]["Rendering"]["BaseTemplate"];
		$this->markup = empty($markup) ? $renderer->getBaseMarkup() : $markup;
		$this->options = empty($options) ? $renderer->getBaseOptions() : $options;
		$this->hooks = $hooks;
	}

	/**
	 * the main entrance function
	 */
	abstract public function index();
}

//_____________________________________________________________________________________________
//