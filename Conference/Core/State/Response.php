<?php
/**********************************************************************************************
 * 
 * @File constructs a response containing information about a returned or cancelled process
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\State;

use \Conference\Core\Classes;

class Response extends Classes\ParameterBag {

	/**
	 * response types
	 */
	const OK = "ok";
	const ERROR = "error";
	const WARNING = "warning";
	const INFO = "info";
	
	/**
	 * response type
	 * Note: defined in ParameterBag
	 * 
	 * @var string
	 */
	# private $type

	/**
	 * response message
	 * Note: defined in ParameterBag
	 * 
	 * @var string
	 */
	# private $message

	/**
	 * response value
	 * Note: defined in ParameterBag
	 * 
	 * @var mixed
	 */
	# private $payload;

	/**
	 * construction
	 */
	public function __construct( ?string $message, string $type = namespace\Response::OK, $payload ) {

		parent::__construct([ "message" => $message, "type" => $type, "payload" => $payload ], true );
	}
}