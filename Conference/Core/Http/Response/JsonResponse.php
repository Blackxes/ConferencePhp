<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * Todo: edit
 * Todo: implement header / content-type / mime-type / etc
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Http\Response;

use \Conference\Core\Http\Response;

//_____________________________________________________________________________________________
class JsonResponse extends namespace\Response {

	/**
	 * construction
	 */
	public function __construct( $content, string $type = "ok", string $message = "" ) {

		parent::__construct( null, Response\Response::HTTP_OK, [
			"Content-Type" => "application/json;charset=utf8",
			"Access-Control-Allow-Origin" => "*",
			"Access-Control-Allow-Headers" => "Origin, X-Requested-With, Content-Type, Accept",
			"Access-Control-Allow-Methods" => "GET, POST, PUT, OPTIONS"
		]);

		$this->set( "content", json_encode([
			"data" => $content,
			"type" => $type,
			"message" => $message
		]));
	}
}

//_____________________________________________________________________________________________
//