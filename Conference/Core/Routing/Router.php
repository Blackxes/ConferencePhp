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
use \Conference\Core\Controller;
use \Conference\Core\Http\Request;
use \Conference\Core\Http\Response;

//_____________________________________________________________________________________________
class Router {

	/**
	 * construction / nothing to do here
	 */
	public function __construct() {

		$this->baseRoute = $GLOBALS["CONREN"]["General"]["baseRoute"];
	}
	
	/**
	 * parses the build route object and returns a response
	 * 
	 * HeadComment:
	 */
	private function parseRouteObject( namespace\RouteObject $routeObject ) {
		
		# check 404
		if ( !$this->verifyController($routeObject) )
			return ( new Controller\Http404Controller() )->index();

		# check 403
		else if ( !$this->verifyAccess($routeObject) )
			return ( new Controller\Http403Controller() )->index();
		
		# actual controller
		list( $controller, $entrance ) = $routeObject->get( ["controller", "entrance"], true, false );

		# Note: implement option within the database
		// if ( \Conference::currentUser()->get("anonymous") )
		// 	return ( new \Conference\Core\User\UserController )->login();

		return ( new $controller )->prepare()->$entrance();
	}
	
	/**
	 * parses the raw response provided by a controller
	 */
	private function parseControllerResponse( $response ) {

		# Note: the response will be redefined in this scope
		# the given value from the parameter $response may differ throughout this function

		# wrap response in a controller response when simply return $this happened
		if ( is_a($response, "\Conference\Core\Controller\ControllerBase") )
			$response = new Response\ControllerResponse( $response );

		# when wrapped in a controller response parse the content
		if ( is_a($response, "\Confenrece\Core\Http\Response\ControllerResponse") )
			$response->parse();
			
		return $response;
	}

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
	public function parseRequest( Request\Request $request ) {
		
		$routeObject = \Conference::service( "route.handler" )->buildRouteObject();
		
		# prepare default markup
		\Conference::service( "markupbuilder" )->prepare();
		
		$response = $this->parseControllerResponse(
			$this->parseRouteObject( $routeObject )
		);

		return $response;
	}

	/**
	 * verifies the access to current route based on the current user
	 * 
	 * @return boolean
	 */
	private function verifyAccess( namespace\RouteObject $routeObject ) {

		# the conditions are split due to documentation
		
		# when no access roles are given the page is public
		$access = $routeObject->get("access");

		if ( is_null($access) )
			return true;
		
		# admins and super user have access no matter what
		if ( \Conference::currentUser()->get("super_user") )
			return true;
		
		# check roles
		$roles = array_keys( (array) \Conference::currentUser()->get("roles") );

		if ( !(bool) array_intersect($roles, explode(",", $access)) )
			return true;
		
		// else for safety reasons block access when nothing above matches
		return false;
	}

	/**
	 * verifies the controller and entrance of the current route
	 * 
	 * @return boolean
	 */
	private function verifyController( namespace\RouteObject $routeObject ) {

		// there is nothing to check for when no route defined
		if ( is_null($routeObject) )
			return false;

		// check the controller and the method
		$controller = $routeObject->get( "controller" );

		if ( !class_exists($controller) || !method_exists($controller, $routeObject->get("entrance")) )
			return false;

		return true;
	}

	/**
	 * redirecting to a given absolute link
	 */
	public function redirect( string $link ) {

		header( "Location: " . $link );
		die( "redirecting .." );
	}

	/**
	 * redirects the user to a route
	 */
	public function redirectByRouteKey( string $routeKey ) {

		$db = \Conference::db();

		$db->query( "select r.* from `c7_routes` as r where route_key=:route_key" );

		$db->bindValue( ":route_key", $routeKey );

		$db->execute();

		$res = reset( $db->fetch() );

		if ( !$res )
			die( "redirecting failed - route not found .." );
		
		header( "Location: " . $GLOBALS["CONREN"]["General"]["domain"] . "/" . $res->get("route") );
		die( "redirecting .." );
	}
}

//_____________________________________________________________________________________________
//