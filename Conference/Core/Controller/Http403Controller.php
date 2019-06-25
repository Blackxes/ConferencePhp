<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * 403 controller - nearly the same as the 404
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Controller;

use \Conference\Core\Http\Response;
use \Conference\Core\Rendering;

//_____________________________________________________________________________________________
class Http403Controller extends namespace\ControllerBase {

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

		$this->markup["page-title"] = "403 Nicht gefunden";
		$this->markup[ "content" ][] = $builder->message( "Kein Zugriff" );
		$this->markup[ "content" ][] = $builder->link( "Zu den Produkten", $GLOBALS["CONREN"]["General"]["domain"] . "/products" );

		$response = new Response\ControllerResponse( $this, Routing\Response::HTTP_FORBIDDEN, [] );

		return $response;
	}
}

//_____________________________________________________________________________________________
//