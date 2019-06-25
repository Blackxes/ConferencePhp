<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * user management
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Backend\Service;

use \Conference\Core\Classes;
use \Conference\Core\Controller;

//_____________________________________________________________________________________________
class ServiceController extends Controller\ControllerBase {

	/**
	 * construction
	 */
	public function __construct() {

		parent::__construct();
	}

	/**
	 * backup entrance
	 */
	public function index() {
		
		return $this;
	}

	/**
	 * deletes services
	 * 
	 * HeadComment:
	 */
	public function delete() {

		# via post sent ids have higher prio
		$uid = $_POST["service_id"]
			? $_POST["service_id"]
			: \Conference::service( "route.handler" )->getRouteObject()->get([ "variables", "service_id" ]);

		\Conference::serviceManager()->delete( $uid );

		\Conference::redirectByRouteKey( "admin_services" );

		die("redirecting ..");
	}

	/**
	 * edit form of a service
	 * 
	 * HeadComment:
	 */
	public function edit() {

		$this->markup["page-title"] = "Edit service";

		$this->markup["content"][] = \Conference::service( "service.markupbuilder" )->editForm();
		
		return $this;
	}

	/**
	 * lists all services
	 * 
	 * HeadComment:
	 */
	public function list() {

		\Conference::service( "penis" );

		$this->markup["page-title"] = "Services";

		$this->markup["content"][] = \Conference::service( "service.markupbuilder" )->list();

		return $this;
	}

	/**
	 * register form
	 * 
	 * HeadComment:
	 */
	public function register() {

		$this->markup["page-title"] = "Register service";

		if ( \Conference::serviceManager()->registerFromPost() )
			\Conference::redirectByRouteKey( "admin_services" );

		$this->markup["content"][] = \Conference::service( "service.markupbuilder" )->registerForm();

		return $this;
	}

	/**
	 * updates a service
	 * 
	 * HeadComment:
	 */
	public function update() {

		$this->markup["page-title"] = "Update service";

		if ( \Conference::serviceManager()->updateFromPost() )
			\Conference::redirectByRouteKey( "admin_services" );

		return $this;
	}


}

//_____________________________________________________________________________________________