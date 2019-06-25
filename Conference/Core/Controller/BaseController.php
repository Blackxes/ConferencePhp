<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * the most basic controller
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Controller;

use \Conference\Core\Routing;

//_____________________________________________________________________________________________
class BaseController extends namespace\ControllerBase {

	/**
	 * construction
	 */
	public function __construct() {

		parent::__construct();
	}
	
	/**
	 * main controller entrance
	 */
	public function index() {
		
		return $this;
	}
}

//_____________________________________________________________________________________________
//