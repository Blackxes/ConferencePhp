<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * middleware for the response contructor to use controller as render objects
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Rendering;

use \Conference\Core\Controller;

//_____________________________________________________________________________________________
class RenderObjectController extends namespace\RenderObject {
	
	/**
	 * construction
	 * 
	 * @param Controller\ControllerBase - a controller
	 * 
	 */
	public function __construct( Controller\ControllerBase $controller ) {

		// fallback castings
		parent::__construct(
			(string) $controller->template,
			(array) $controller->markup,
			(array) $controller->hooks,
			(array) $controller->options
		);
	}
}

//_____________________________________________________________________________________________
//