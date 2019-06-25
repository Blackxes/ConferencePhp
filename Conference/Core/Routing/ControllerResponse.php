<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * wrapper for the response of the controller and the controller itself
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Routing;

use \Conference\Core\Controller;

//_____________________________________________________________________________________________
class ControllerResponse extends namespace\Response {
	
	/**
	 * construction
	 * 
	 * @param \Conference\Core\Routing\Response
	 * @param \Conference\Core\Controller\ControllerBase
	 */
	public function __construct( namespace\Response $controllerResponse, Controller\ControllerBase $controller ) {

		parent::__construct( $controllerResponse );

		$this->merge( null, array(
			"controllerResponse" => $controllerResponse,
			"controller" => $controller
		));
	}
}

//_____________________________________________________________________________________________
//