<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * HeadComment:
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Rendering\ContentBuilding;

//_____________________________________________________________________________________________
class RootMarkupBuilder extends namespace\MarkupBuilderBase {

	/**
	 * construction
	 */
	public function __construct() {
		
		parent::__construct();

		\Conference::service( "templax" )->init([
			"templates" => [ "conren_root", "conren_root_login" ],
			"options" => ["file" => CONREN_PATH_TEMPLATES_ABS . "/root.html"]
			
		], $this->additionalMarker() );
	}

	/**
	 * prepares the markup
	 * 
	 * HeadComment
	 */
	public function prepare( array &$markup ) {

		$builder = new namespace\ElementMarkupBuilder();

		# title
		$title = $GLOBALS["CONREN"]["General"]["baseWebpageTitle"];

		# meta
		$meta["meta.charset"] = $builder::attributeList([ "charset" => "utf-8" ]);
		$meta["meta.http-equiv"] = $builder->attributeList([ "http-equiv" => "X-UA-Compatible", "content" => "" ]);
		$meta["meta.viewport"] = $builder->attributeList([ "name" => "viewport", "content" => "width=device-width, initial-scale=1" ]);

		# base url
		$base = $GLOBALS["CONREN"]["General"]["domain"] . "/";

		# links
		$link["font.opensans"] = $builder->attributeList([ "href" => "https://fonts.googleapis.com/css?family=Open+Sans:300", "rel" => "stylesheet" ]);
		$link["style.jquery-ui-style"] = $builder->attributeList([ "href" => "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css", "rel" => "stylesheet" ]);
		
		foreach( $GLOBALS["CONREN"]["Assets"]["Styles"] as $i => $file )
			$link[ "style.style_$index" ] = $builder->attributeList([ "rel" => "stylesheet", "type" => "text/css", "href" => $file ]);
		
		$link["style.font-awesome"] = $builder->attributeList([
			"rel" => "stylesheet",
			"href" => "https://use.fontawesome.com/releases/v5.6.1/css/all.css",
			"integrity" => "sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP",
			"crossorigin" => "anonymous"
		]);

		# scripts
		$scriptH["script.jquery"] = $builder->attributeList([ "src" => "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" ]);
		$scriptH["script.jquery-ui-script"] = $builder->attributeList([ "src" => "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" ]);
		
		$scriptB["script.main"] = $builder->attributeList([ "src" => "Client\dist\index.bundle.js?" . time() ]);
		$scriptB["script.app"] = $builder->attributeList([ "src" => "assets\js\bundle.js?" . time() ]);

		# Todo: implement dynamic script including
		// foreach( $GLOBALS["CONREN"]["Assets"]["Scripts"] as $index => $file )
			// $scripts["script_$index"] = namespace\ElementMarkupBuilder::script( $file . "?" . time() );

		# additional root marker
		\Conference::service( "templax" )->init( [], $this->additionalMarker() );
		
		// finish up markup
		$markup = [
			"title" => $title,
			"base" => $base,
			"meta" => $meta,
			"link" => $link,
			"script-head-extern" => $scriptH,
			"script-body-extern" => $scriptB
		];
		// $markup = array_merge( $markup, ["head" => $head, "scripts" => $scripts] );

		return $this;
	}

	/**
	 * additional marker
	 * 
	 * HeadComment:
	 */
	public function additionalMarker() {

		$markup = [];

		# route refering marker
		$routeHandler = \Conference::service( "route.handler" );
		foreach( $routeHandler->routes() as $uid => $route )
			$markup[ "conren_route_" . $route->get("route_key") ] = $route->get("route");
		
		$markup["conren_requested_route"] = $routeHandler->getRouteObject()->get( "route" );
		
		# user refering marker
		$user = \Conference::currentUser();
		$markup["conren_user_logged_in"] = !(bool) $user->get( "anonymous" );
		$markup["conren_user_anonymous"] = !$markup["conren_user_logged_in"];
		$markup["conren_user_is_super"] = (bool) $user->get( "super_user" );

		# general
		$markup["conren_domain"] = $GLOBALS["CONREN"]["General"]["domain"];
		
		return $markup;
	}
}

//_____________________________________________________________________________________________
//