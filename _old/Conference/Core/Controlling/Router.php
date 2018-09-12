<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	// TODO: do description

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Controlling;

//_____________________________________________________________________________________________
class Router {
	
	private $routinus;

	public $query; // $_GET prepared
	public $post; // $_POST prepared
	public $server; // $_SERVER

	public $route; // parsed route

	//_________________________________________________________________________________________
	public function __construct() {
		
		$this->routinus = \Conference::service( "routinus" );

		// Todo: prepare these values
		$this->query = $_GET;
		$this->post = $_POST;
		$this->server = $_SERVER;

		$this->cachedSelectionRoutes = array();
	}

	//_________________________________________________________________________________________
	// handles the incoming request
	public function handleRequest(): namespace\Response\Response {
		
		$routeObject = $this->parseRequest();
		
		// when no route has been passed redirect to default or the base controller
		if ( !$this->routinus->getRoute() ) {
			if ( isset($GLOBALS["Conference"]["Routing"]["defaultRoute"]) )
				\Conference::redirect( $GLOBALS["Conference"]["Routing"]["defaultRoute"] );
			return (new namespace\Controller\DefaultController())->index();
		}
		
		// when passed route is invalid
		if ( !$routeObject->route )
			return \Conference::http404Response();
		
		// when route defined but not record defined
		if ( !class_exists($routeObject->controller) )
			return \Conference::http404Response();
		
		// check user access
		$user = \Conference::user();

		if ( !empty($routeObject->access) && !count(array_intersect(explode(",", $routeObject->access), $user->getRoles())) && !$user->getSuper() )
			return \Conference::http403Response();
		
		// acces controller at entrance
		$controller = new $routeObject->controller;
		$entrance = $routeObject->entrance;

		// check entrance availability
		if ( !method_exists($controller, $entrance) )
			return \Conference::http404Response();

		// prepare base markup and options

		$bases = \Conference::service( "renderer" )->getPrepared();

		$response = $controller->$entrance( $bases["markup"], $bases["options"] );

		return $response;
	}

	//_________________________________________________________________________________________
	// parses the incoming request and returns the route object
	//
	// return \Routinus\Route
	//		the route object
	//
	public function parseRequest() {

		$route = $this->routinus->parse( function($regex) {
			
			$db = \Conference::db();
			$regex = trim($regex, "/");

			$stmt = $db->prepare("SELECT * FROM `conf_routes` WHERE `route` REGEXP :regex;");
			$stmt->bindParam(":regex", $regex);

			if (!$stmt->execute())
				debug($stmt->errorInfo());

			while( $route = $stmt->fetchObject() )
				$this->cachedSelectionRoutes[$route->route] = $route;
			
			return array_map( function( $route ) {
				return $route->route;
			}, $this->cachedSelectionRoutes );
		});

		if ( !$route ) return null;

		$this->route = $this->cachedSelectionRoutes[$route->getRoute()];
		$this->route->variables = $route->getVariables();
		
		return $this->route;
	}

	//_________________________________________________________________________________________
	// clears the post and get data
	public function clearQueriesAndPosts() {

		$_POST = array();
		$_GET = array();

		return true;
	}
}

//_____________________________________________________________________________________________
//