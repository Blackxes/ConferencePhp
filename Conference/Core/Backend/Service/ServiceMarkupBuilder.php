<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * user relevant content building
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Backend\Service;

use \Conference\Core\Rendering\ContentBuilding;

//_____________________________________________________________________________________________
class ServiceMarkupBuilder extends ContentBuilding\MarkupBuilderBase {

	public function __construct() {
		
		parent::__construct([
			"templates" => [
				"service_list", "service_edit_form", "service_register_form"
			],
			"options" => [ "file" => __DIR__ . "/service.html" ]
		]);
	}

	/**
	 * returns the markup for the edit form of a service
	 * 
	 * HeadComment:
	 */
	public function editForm() {

		$uid = \Conference::service( "route.handler" )->getRouteObject()->get([ "variables", "service_id" ]);
		$service = \Conference::serviceManager()->get( (int) $uid, "uid", ["single" => true] );

		$markup = [
			"template" => "service_edit_form",
			"templateSelect-template" => ( $service ) ? $service->all() : []
		];
		
		return $markup;
	}
	
	/**
	 * returns the markup for the users listing
	 */
	public function list() {

		$markup = [
			"template" => "service_list",
			"templateSelect-template" => [
				"services" => \Conference::serviceManager()->all("uid", ["wrapInBag" => false])
			]
		];

		return $markup;
	}

	/**
	 * prepares the markup
	 * 
	 * HeadComment:
	 */
	public function prepare( array &$markup ) {

		return $this;
	}

	/**
	 * returns the markup for a register form
	 * 
	 * HeadComment:
	 */
	public function registerForm() {

		$markup = [
			"template" => "service_register_form",
			"templateSelect-template" => [
				"sources" => \Conference::serviceManager()->all( "uid", ["wrapInBag" => false] )
			]
		];

		return $markup;
	}
}

//_____________________________________________________________________________________________