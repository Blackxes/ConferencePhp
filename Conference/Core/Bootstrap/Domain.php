<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * domain checks and operations
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Bootstrap;

class Domain {

	/**
	 * no construction needed for this class
	 */
	private function __construct() {}
	
	/**
	 * builds the root domain from globals
	 */
	static public function fromGlobals() {
		
		$protocol = ( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off" ) ? "https" : "http";
		$server = $_SERVER["SERVER_NAME"];
		$port = ( $_SERVER["SERVER_PORT"] != "80" ) ? $_SERVER["SERVER_PORT"] : "";
		$dir = pathinfo($_SERVER["PHP_SELF"])["dirname"];
		
		$domain = sprintf( "%s://%s:%s%s", $protocol, $server, $port, $dir );

		return trim( $domain, "/" );
	}
}