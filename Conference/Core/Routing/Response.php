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

//_____________________________________________________________________________________________
class Response {

	/**
	 * markup from the controller
	 * 
	 * @var array|null
	 */
	public $markup;

	/**
	 * options from the controller
	 * 
	 * @var array|null
	 */
	public $options;

	/**
	 * hooks from the controller
	 * 
	 * @var array|null
	 */
	public $hooks;

	/**
	 * status code of this response
	 * 
	 * @var int
	 */
	public $code;

	/**
	 * headers
	 * 
	 * @var array
	 */
	public $headers;

	/**
	 * construction
	 * 
	 * @param array|null $markup
	 * @param array|null $options
	 * @param array|null $hooks
	 */
	public function __construct( string $content = "", int $status = "200", array $header = array() ) {

		$this->content = $content;
		$this->status = $status;
		$this->header = $header;
		
		$this->markup = (array) $markup;
		$this->options = (array) $options;
		$this->hooks = (array) $hooks;
	}
}

//_____________________________________________________________________________________________
//