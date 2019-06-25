<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * manages the routing through the system before the rendering happens
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Routing;

use \Conference\Core\Classes;

//_____________________________________________________________________________________________
class RouteHandler {

	/**
	 * the route object built by the sent request
	 * 
	 * @var \Conference\Core\Routing\RouteObject
	 */
	private $routeObject;

	/**
	 * construction
	 */
	public function __construct() {

		$this->routeObject = null;
	}

	/**
	 * builds the route object
	 * 
	 * HeadComment:
	 */
	public function buildRouteObject() {

		# check existing route object
		if ( !is_null($this->routeObject) )
			return $this->routeObject;
		
		$route = \Conference::service( "request.handler" )->getRequest()->get([ "route" ]);

		# the query falsified the route search therefore we need to cut it off
		if ( strpos($route, "?") )
			$route = strstr( $route, "?", true );
		
		# is no route defined as well as no default route
		# then there is nothing to route to
		if ( !$route ) {

			$default = $GLOBALS["CONREN"]["Routing"]["defaultRoute"];

			if ( !$default )
				throw new \Exception( "RouteHandler: no default route has been defined. Please define one in the Configuration.php" );
			
			$route = $default;
		}

		# get route selection based on segments count
		$selection = $this->getRouteSelection( $this->countSegmentCount($route) );

		# absolute and relative route match
		$match = $this->getRequestedRouteFromSelection( $route, $selection );

		if ( is_null($match) )
			return new namespace\RouteObject( $route );
		
		$this->routeObject = namespace\RouteObject::fromRouteMatch( $match->all() );

		$this->routeObject->set( "valid", true );
		$this->routeObject->block();

		return $this->routeObject;
	}

	/**
	 * checks a selection for an absolute route match
	 * 
	 * HeadComment:
	 */
	private function checkAbsoluteRouteMatch( $route, $selection ) {

		foreach( $selection as $recordsRoute => $bag ) {
			if ( $recordsRoute == $route )
				return $bag;
		}

		return false;
	}

	/**
	 * checks is a given route matches relatively to the compared route
	 * 
	 * HeadComment:
	 */
	private function checkRelativeRouteMatch( $requestedRoute, $checkingRoute ) {

		$variables = array();
		$reqSegments = explode( "/", $requestedRoute );
	
		# check given route with user route segment by segment
		# absolute segment matches have higher priority than variable ones
		foreach( explode("/", $checkingRoute) as $index => $segment ) {
			
			// when the segment matches the one from the request route
			if ( $segment == $reqSegments[$index] )
				continue;
			
			// check and store variables
			preg_match( "/^{(.*)}$/", $segment, $result );

			// this route doesnt match when it doesnt pass any checks
			if ( empty($result) )
				return null;
			
			$variables[ $result[1] ] = $reqSegments[ $index ];
		}
		
		# at this point the route matches every segment
		return [ "variables" => new Classes\ParameterBag( $variables ) ];
	}

	/**
	 * returns the segment count of the given route
	 */
	private function countSegmentCount( string $route ) {

		return (int) preg_match_all( "/(?:[^\/]+)/", $route );
	}

	/**
	 * tries to find the requested route out of a selection
	 * 
	 * HeadComment:
	 */
	private function getRequestedRouteFromSelection( $route, $selection ) {

		if ( is_null($route) || empty($route) || empty($selection) )
			return null;
		
		$segments = explode( "/", $route );

		# check absolute match
		$absoluteMatch = $this->checkAbsoluteRouteMatch( $route, $selection );

		if ( $absoluteMatch )
			return $absoluteMatch;
		
		# relative match
		foreach( $selection as $recordRoute => $recordBag ) {
			
			$result = $this->checkRelativeRouteMatch( $route, $recordBag->get("route") );

			// continue searching when no match
			if ( !is_null($result) )
				return $recordBag->merge( null, $result );
		}


		return $match;
	}

	/**
	 * returns the route object
	 */
	public function getRouteObject() {

		return !is_null($this->routeObject) ? $this->routeObject : $this->buildRouteObject();
	}

	/**
	 * returns all routes based on the given segment count
	 * 
	 * @param int $segmentCount - the segment count of the route
	 * 
	 * @return false - when the segment count is invalid (0)
	 * @return array - array of routes with the given segment count
	 */
	private function getRouteSelection( int $segmentCount ) {

		if ( !$segmentCount )
			return false;
		
		$db = \Conference::db();
	
		// select everything from routes and a concatenated list of role permitted labels
		// $query[] = "SELECT r.uid, UNIX_TIMESTAMP(r.crdate) AS crdate, UNIX_TIMESTAMP(r.updated) AS updated, r.active, r.hidden,";
		$db->query( "select r.uid, r.route, r.segment_count, r.controller, r.entrance, group_concat( roles.uid ) as access" );

		// the from part because the field selection is quite long
		$db->query( "from `c7_routes` as r" );

		// left join the the route access references on the permitted role uids
		$db->query( "left join `c7_route_accesses` as raccess on raccess.route_id = r.uid" );
		$db->query( "left join `c7_roles` as roles on roles.uid = raccess.role_id" );

		// select by the current segment count
		// $query[] = "WHERE r.segment_count = :segment_count AND r.active = 1";
		$db->query( "where r.segment_count = :segment_count" );

		// grouping and ordering
		$db->query( "group by r.uid order by r.uid" );

		// bind
		$db->bindValue( ":segment_count", $segmentCount );

		$db->execute();

		return $db->fetch( "route" );
	}

	/**
	 * returns all routes
	 */
	public function routes( $index = "uid" ) {

		$db = \Conference::db();

		$db->query( "select r.* from `c7_routes` as r" );
		
		$db->execute();

		return $db->fetch( $index );
	}
}

//_____________________________________________________________________________________________
//