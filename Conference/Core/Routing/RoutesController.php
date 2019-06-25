<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * all system configuration is in here
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Routing;

use \Conference\Core\Controller;
use \Conference\Core\Routing;
use \Conference\Core\Rendering;

//_____________________________________________________________________________________________
class RoutesController extends Controller\ControllerBase {

	/**
	 * construction
	 */
	public function __construct( string $template = null, array $markup = [], array $hooks = [] ) {

		parent::__construct( $template, $markup, $hooks );
	}

	/**
	 * page that lists all routes
	 */
	public function index() {

		// echo "route index";

		return $this;
	}
}

//_____________________________________________________________________________________________
//