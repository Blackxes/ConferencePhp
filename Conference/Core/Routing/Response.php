<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * the response contains information for the renderer to render content
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Routing;

use \Conference\Core\Classes;
use \Conference\Core\Controller;
use \Conference\Core\Rendering;

//_____________________________________________________________________________________________
class Response extends Classes\ParameterBag {


	// Todo: implement use of http status codes
	
	/**
	 * http status codes
	 */
	const HTTP_VERSION = "1.1";

	const HTTP_OK = 200;
	const HTTP_MOVED_PERMANENTLY = 301;
	const HTTP_FOUND = 302;
	const HTTP_BAD_REQUEST = 400;
	const HTTP_FORBIDDEN = 403;
	const HTTP_NOT_FOUND = 404;

	const STATUS_TEXT = array(
		"200" => "Ok",
		"301" => "Moved Permanently",
		"302" => "Found",
		"400" => "Bag Request",
		"403" => "Forbidden",
		"404" => "Not Found"
	);

	/**
	 * construction
	 * 
	 * @param mixed $content - to the client sent content
	 * @param int $status - http status code
	 * @param array $headers - headers
	 */
	public function __construct( $content = null, int $status = Response::HTTP_OK, array $headers = array() ) {

		if ( is_a($content, __NAMESPACE__ . "\Response") )
			parent::__construct( $content->all() );

		else {

			parent::__construct( array(
				"content" => $content,
				"status" => $status,
				"headers" => new Classes\ParameterBag( $headers ),
				"preparedContent" => ""
			));
		}
	}

	/**
	 * prepares the response for being send to the client
	 * performs header corrections and content preparation
	 */
	public function prepare( Controller\ControllerBase $controller ) {

		// prepare headers before content
		// to ensure the content is not useless created since the headers can differ
		// based on the 
		$this->prepareHeaders();
		$this->prepareContent( $controller );

		return $this;
	}

	/**
	 * prepare the content
	 * 
	 * @param \Conference\Core\Controller\ControllerBase $controller - the used controller
	 * 
	 * @return $this
	 */
	public function prepareContent( Controller\ControllerBase $controller ) {
		
		$content = $this->getRef( "content" );
		
		// check wether a renderobject is used
		if ( is_a($content, "\Conference\Core\Rendering\RenderObject") )
			return $this->set( "preparedContent", $content->prepare()->get("preparedContent") );
		
		// when its a controller wrap is around an render object controller for the user
		else if ( is_a($content, "\Conference\Core\Controller\ControllerBase") )
			return $this->set( "preparedContent", (new Rendering\RenderObjectController($controller))->prepare()->get("preparedContent") );
		
		// else use the given content
		else if ( is_string($content) )
			return $this->set( "preparedContent", $content );
		
		// fallback
		else
			$this->set( "preparedContent", (new Rendering\RenderObject())->prepare()->get("preparedContent") );
		
		return $this;
	}

	/**
	 * prepares defined headers
	 * 
	 * @return $this
	 */
	public function prepareHeaders() {

		if ( headers_sent() )
			return $this;
		
		// Todo: validate defined header
		foreach( $this->all("headers") as $name => $config )
			header( $name . ":" . $config["value"], $config["replace"], $config["status"] );
		
		// set status code
		http_response_code( $this->get("status") );
		
		return $this;
	}
	
	/**
	 * sends the content to the client
	 */
	public function sendContent() {

		echo $this->get( "preparedContent" );

		return $this;
	}
}

//_____________________________________________________________________________________________
//