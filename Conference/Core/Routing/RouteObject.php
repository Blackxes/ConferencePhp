<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * route object model
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Routing;

use \Conference\Core\Classes;

//_____________________________________________________________________________________________
class RouteObject extends Classes\ParameterBag {

	/**
	 * construction
	 * 
	 * @param string $route - the requested route
	 * @param array $variables - variables of the route
	 */
	public function __construct( string $route, array $variables = [] ) {

		parent::__construct([
			"route" => $route,
			"segments" => explode( "/", $route ),
			"variables" => $variables,
			"valid" => false
		]);

		$this->set( "segmentCount", $this->get("route") );
	}

	/**
	 * creates a route object instance from given params
	 */
	static public function fromRouteMatch( $matchParams ) {

		$instance = new self( "" );
		$instance->merge( null, $matchParams );

		return $instance;
	}
}

//_____________________________________________________________________________________________
//