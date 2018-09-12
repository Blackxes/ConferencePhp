<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * manages the display of content based on the given response
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Rendering;

use \Conference\Core\Controller;

//_____________________________________________________________________________________________
class Renderer {

	/**
	 * default markup
	 * 
	 * @var array
	 */
	private $baseMarkup;
	
	/**
	 * default options
	 * 
	 * @var array
	 */
	private $baseOptions;

	/**
	 * default hooks
	 * 
	 * @var array
	 */
	private $baseHooks;

	/**
	 * construction
	 */
	public function __construct() {

		$renderConfig = $GLOBALS["CONREN"]["Rendering"];

		$this->baseMarkup = $this->buildBaseMarkup();
		$this->baseOptions = $renderConfig["BaseOptions"];
		$this->baseHooks = $renderConfig["BaseHooks"];
	}

	/**
	 * renders the given response
	 */
	public function render( Controller\ControllerBase $response ) {

		$content = "";

		// on invalid response
		if ( is_null($response) )
			$content = "no content";
		
		// parse the response
		else {

			$content = \Templax\Templax::parse(
				$response->template,
				$response->markup,
				$response->hooks
			);
		}

		echo $content;

		return true;
	}
	
	/**
	 * builds the default markup
	 */
	private function buildBaseMarkup() {
		
		$markup = array(
			"webpage-title" => "Conference",
		);

		$GLOBALS["CONREN"]["Rendering"]["BaseMarkup"] = $markup;

		return $markup;
	}

	/**
	 * returns the default markup
	 * 
	 * @return array
	 */
	public function getBaseMarkup() {
		
		return $this->baseMarkup;
	}

	/**
	 * returns the default options
	 * 
	 * @return array
	 */
	public function getBaseOptions() {

		return $this->baseOptions;
	}

	/**
	 * returns the default hooks
	 * 
	 * @return array
	 */
	public function getBaseHooks() {

		return $this->hooks;
	}
}

//_____________________________________________________________________________________________
//