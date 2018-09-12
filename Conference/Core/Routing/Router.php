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

//_____________________________________________________________________________________________
class Router {

	/**
	 * route alias
	 * 
	 * @var string
	 */
	public $route;

	/**
	 * the route object
	 * 
	 * @var \Conference\Core\Routing\Route
	 */
	public $routeObj;

	/**
	 * storing the route segments extra to avoid spliting the the whole time
	 * when checking wether route segment match on the select route from selectoin
	 * 
	 * @var array
	 */
	public $routeSegments;

	/**
	 * contains the default controller
	 * 
	 * @var string|null
	 */
	public $baseController;

	/**
	 * the default route when no route is given
	 * 
	 * @var string|null
	 */
	public $baseRoute;


	/**
	 * construction / nothing to do here
	 */
	public function __construct() {}

	/**
	 * parses the handled request and returns the response from a controller
	 * 
	 * 404
	 * route defined
	 * no controller
	 * no entrance
	 * 
	 * 403
	 * route defined
	 * no access
	 * 
	 * 302
	 * no route defined
	 * base controller defined
	 * 
	 * 200
	 * route defined
	 * controller defined
	 * entrance defined
	 * 
	 * @return \Conference\Core\Routing\Response
	 */
	public function parseRequest( namespace\Request $request ) {

		// define alias
		$this->route = $request->route;
		$this->routeSegments = explode( "/", $this->route );
		
		// get the route object
		$this->routeObj = $this->getRouteFromSelection( $this->getPossibleRouteRecords() );

		// .. alias
		$routeObj = &$this->routeObj;

		// when a route object is defined ..
		if ( $routeObj ) {

			// .. check access first
			if ( !$this->verifyAccess() )
				return \Conference::Http403Response();
			
			// .. check controller and entrance
			if ( !$this->verifyController() )
				return \Conference::Http404Response();
			
			// instantiate controller and get response
			$controller = ( new $routeObj->controller );
			$entrance = $routeObj->entrance;

			$renderer = \Conference::renderer();
			
			return $controller->$entrance(
				$renderer->getBaseMarkup(),
				$renderer->getBaseOptions(),
				$renderer->getBaseHooks()
			);
		}

		// when no route is given ..
		//
		// .. then try to redirect when a base route is defined
		if ( isset($this->baseRoute) )
			\Conference::redirect( $this->baseRoute );
		
		// .. or return a blank response
		return new Controller\ControllerBase();
	}

	/**
	 * verifies the controller and entrance of the current route
	 * 
	 * @return boolean
	 */
	private function verifyController() {

		// there is nothing to check for when no route defined
		if ( is_null($this->routeObj) )
			return false;
		
		// alias
		$routeObj = &$this->routeObj;

		// check the controller and the method
		if ( !class_exists($routeObj->controller) || method_exists($routeObj->controller, $routeObj->entrance) )
			return false;

		return true;
	}

	/**
	 * verifies the access to current route based on the current user
	 * 
	 * @return boolean
	 */
	private function verifyAccess() {

		// when no route is defined nothing to access
		if ( is_null($this->routeObj) )
			return false;
		
		// when no roles are defined the route is publicly accessable
		if ( is_null($this->routeObj->access) )
			return true;
		
		// admins and super user have access no matter what
		if ( isset(explode(",", $this->routeObj->access)[1]) )
			return true;
		
		// get roles and intersect them with the user roles
		if ( !empty(array_intersect( explode(",", $this->routeObj->access), \Conference::user()->getRoles())) )
			return true;
		
		// else for safety reasons block access when nothing above matches
		return false;
	}

	/**
	 * returns the route records that possible match the current route
	 * 
	 * @return array|null
	 */
	public function getPossibleRouteRecords() {

		$query = array();
		
		// select everything from routes and a concatenated list of role permitted labels
		$query[] = "SELECT r.uid, UNIX_TIMESTAMP(r.crdate) AS crdate, UNIX_TIMESTAMP(r.updated) AS updated, r.active, r.hidden,";
		$query[] = "r.route, r.segmentCount, r.controller, r.entrance, GROUP_CONCAT( roles.uid ) AS access";

		// the from part because the field selection is quite long
		$query[] = "FROM `conren_routes` AS r";

		// left join the the route access references on the permitted role uids
		$query[] = "LEFT JOIN `conren_route_accesses` AS raccess ON raccess.route = r.uid";
		$query[] = "LEFT JOIN `conren_roles` AS roles ON roles.uid = raccess.role";

		// select by the current segment count
		$query[] = "WHERE r.segmentCount = :segmentCount AND r.active = 1";

		// grouping and ordering
		$query[] = "GROUP BY r.uid ORDER BY r.uid";

		// statement and binding
		$stmt = \Conference::db()->prepare( implode(" ", $query) );
		// print_r($stmt);
		$stmt->bindValue( ":segmentCount", $this->getCurrentRoutesSegmentCount() );

		// execute and return results
		$routes = array();

		if ( !$stmt->execute() )
			die( print_r($stmt->errorInfo()) );
		
		while( $r = $stmt->fetchObject() )
			$routes[$r->uid] = $r;
		
		return $routes;
	}

	/**
	 * returns the segment count of the current route
	 * 
	 * @return int
	 */
	private function getCurrentRoutesSegmentCount() {

		$route = $this->route;
		
		return ( is_null($route) ) ? 0 : preg_match_all( "/(?:[^\/]+)/", $route );
	}

	/**
	 * returns the route from the given selection that matches to the current route
	 * 
	 * @param array $selection - the possible routes that might match the current route
	 * 
	 * @return \Conference\Core\Routing\Route|null
	 */
	private function getRouteFromSelection( $selection ) {

		// check absolute match
		$absMatch = $this->checkAbsoluteMatch( $selection );

		if ( $absMatch )
			return ( new namespace\Route )->defineByObject( $absMatch );

		// search through the selection for a match
		foreach( $selection as $i => $routeObj ) {
			
			$route = $this->processRoute( $routeObj->route );

			// continue searching when no match
			if ( is_null($route) )
				continue;
			
			// include record values and return final route
			return $route->defineByObject( $routeObj );
		}

		return null;
	}

	/**
	 * checks wether the route matches absolute with the given selection
	 * and returns the record object
	 * 
	 * @param array $selection - the selection from which the route will be checked
	 * 
	 * @return \stdClass
	 */
	private function checkAbsoluteMatch( array $selection ) {

		foreach( $selection as $uid => $routeObj )
			if ( $routeObj->route == $this->route )
				return $routeObj;
		
		return false;
	}

	/**
	 * checks wether the current route matches the given one
	 * 
	 * @return boolean
	 */
	private function processRoute( string $route ) {

		$variables = array();
	
		// check given route with user route segment by segment
		// absolute segment matches have higher priority than variable ones
		//
		foreach( explode("/", $route) as $index => $segment ) {
			
			// when the segment matches the one from the request route
			if ( $segment == $this->routeSegments[$index] )
				continue;
			
			// check and store variables
			preg_match( "/^{(.*)}$/", $segment, $result );

			if ( !empty($result) ) {

				$variables[$result[1]] = $this->routeSegments[$index];
				continue;
			}

			// this route doesnt match when it doesnt pass any checks
			return null;
		}
		
		// at this point the route matches since everything has been continued
		return new namespace\Route( $route, $variables );
	}
}

//_____________________________________________________________________________________________
//