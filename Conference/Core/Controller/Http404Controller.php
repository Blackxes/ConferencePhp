<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * 404 controller
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Controller;

use \Conference\Core\Http\Response;
use \Conference\Core\Rendering;

//_____________________________________________________________________________________________
class Http404Controller extends namespace\ControllerBase {

	/**
	 * construction
	 */
	public function __construct( string $template = "", array $markup = array(), array $hooks = array() ) {

		parent::__construct( $template, $markup, $hooks );
	}
	
	/**
	 * default entrance
	 */
	public function index() {

		# disables
		$this->markup["side-menu"]["sideMenu"] = array();

		# content
		$builder = \Conference::service( "markupbuilder.element" );

		$this->markup["page-title"] = "404 Nicht gefunden";
		$this->markup[ "content" ][] = $builder->message( "Die von Ihnen gesuchte Seite konnte nicht gefunden werden." );
		$this->markup[ "content" ][] = $builder->link( "Zu den Produkten", $GLOBALS["CONREN"]["General"]["domain"] . "/products" );

		$response = new Response\ControllerResponse( $this, Response\Response::HTTP_MOVED_PERMANENTLY, [] );

		return $response;
	}
}

//_____________________________________________________________________________________________
//