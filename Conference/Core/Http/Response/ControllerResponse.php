<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * HeadComment:
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Http\Response;

use \Conference\Core\Controller;
use \Conference\Core\Rendering;

//_____________________________________________________________________________________________
class ControllerResponse extends namespace\Response {

	/**
	 * defined controller
	 */
	private $controller;
	
	/**
	 * construction
	 * 
	 * HeadComment:
	 */
	public function __construct( Controller\ControllerBase $controller, int $status = namespace\Response::HTTP_OK, array $headers = [] ) {

		parent::__construct( "", $status, $headers );

		$this->controller = $controller;

		# parse controller to define content
		$this->parse();
	}

	/**
	 * parses the controller given values and defined the content
	 * 
	 * HeadComment:
	 */
	public function parse() {

		list( $template, $markup, $hooks ) = [ $this->controller->template, $this->controller->markup, $this->controller->hooks ];

		$renderObject = new Rendering\RenderObject(
			$template ? $template : "conren_root",
			$markup,
			$hooks			
		);

		$this->set( "content", $renderObject->prepare()->get("content") );
		
		return $this;
	}
}

//_____________________________________________________________________________________________
//