<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * Todo: edit
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Packages\Test;

use \Conference\Core\Controller;
use \Conference\Core\Routing;
use \Conference\Core\Rendering;

//_____________________________________________________________________________________________
class TestController extends Controller\ControllerBase {

	/**
	 * construction
	 */
	public function __construct( string $template = null, array $markup = [], array $hooks = [] ) {

		parent::__construct( $template, $markup, $hooks );
		
		$templates = [
			"templates" => [
				"render_test", "render-test-title", "render-test-container", "render-test-form", "render-test-button",
				"render-test-listing", "render-test-slider"
			],
			"options" => [ "file" => __DIR__ . "/test.html" ]
		];

		\Conference::service( "templax" )->init( $templates, (array) json_decode(file_get_contents(__DIR__ . "/lorem_ipsum.json")) );
	}

	/**
	 * main entrance
	 */
	public function index() {

		# disables
		$this->markup["page-top"] = null;
		$this->markup["side-menu"] = null;
		$this->markup["pre-top-content"] = null;
		$this->markup["top-content"] = null;
		$this->markup["footer"] = null;

		$this->markup["content"][] = array(
			"template" => "render_test",
		);

		return $this;
	}
}

//_____________________________________________________________________________________________
//