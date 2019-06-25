<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * the response contains information for the renderer to render content
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Http\Response;

use \Conference\Core\Classes;

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
	
	const STATUS_TEXT = [
		"200" => "Ok",
		"301" => "Moved Permanently",
		"302" => "Found",
		"400" => "Bag Request",
		"403" => "Forbidden",
		"404" => "Not Found"
	];

	/**
	 * construction
	 * 
	 * @param mixed $content - to the client sent content
	 * @param int $status - http status code
	 * @param array $headers - headers
	 */
	public function __construct( $content = null, int $status = namespace\Response::HTTP_OK, array $headers = array() ) {
		
		parent::__construct( array(
			"content" => $content,
			"status" => $status,
			"headers" => new Classes\ParameterBag( $headers )
		));
	}

	/**
	 * for simple echoing the content
	 */
	public function __toString() {

		return $this->get( "content" );
	}

	/**
	 * prepares the response to being sent to the client
	 */
	final public function prepare() {
		
		static::prepareHeaders();

		return $this;
	}
	
	/**
	 * prepares defined headers
	 * 
	 * HeadComment:
	 */
	public function prepareHeaders() {

		if ( headers_sent() || $this->isNull("headers") )
			return $this;
		
		// Todo: validate defined header
		foreach( $this->all("headers") as $name => $value ) {

			if ( is_array($value) )
				header( $name . ":" . $value["value"], $value["replace"], $value["status"] );
			
			else if ( is_string($value) )
				header( $name . ":" . $value, null, namespace\Response::HTTP_OK );
		}
		
		// set status code
		http_response_code( $this->get("status") );
		
		return $this;
	}

	/**
	 * sends the content to the client
	 */
	public function sendContent() {

		echo $this->get( "content" );
	}
}

//_____________________________________________________________________________________________
//