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
	 * template
	 * 
	 * @var string
	 */
	public $template;

	/**
	 * markup hooks
	 * 
	 * @var array|null
	 */
	public $hooks;

	/**
	 * markup
	 * 
	 * @var array|null
	 */
	public $markup;

	/**
	 * construction
	 * 
	 * HeadComment:
	 */
	public function __construct( string $template = null, array $markup = [], array $hooks = [] ) {
		
		# initial defaults
		$this->template = $template;
		$this->markup = $markup;
		$this->hooks = $hooks;

		$this->prepare();
	}
	
	/**
	 * core initialization of controller
	 * 
	 * HeadComment:
	 */
	private function init( string $template = null, array $markup = [], array $hooks = [] ) {
		
		$this->template = $template;
		$this->markup = $markup;
		$this->hooks = $hooks;
		
		return true;
	}

	/**
	 * the main entrance function
	 */
	public function index() {

		$this->markup["page-title"] = $GLOBALS["CONREN"]["General"]["baseWebpageTitle"];

		return $this;
	}

	/**
	 * defines the default markup for the whole controller
	 * 
	 * HeadComment:
	 */
	public function prepare() {

		# prepare initial markup
		$this->markup = \Conference::service( "markupbuilder" )->prepare();

		return $this;
	}
}

//_____________________________________________________________________________________________
//