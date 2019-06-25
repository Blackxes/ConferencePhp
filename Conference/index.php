<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * conference backend entrance
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

require_once( "./bootstrap.php" );

//_____________________________________________________________________________________________
	// load and initialize system
	\Conference\Core\Bootstrap\Bootstrap::Init();

	// handle incoming request
	$request = \Conference::service( "requestHandler" )->handleRequest();

	// get response 
	$response = \Conference::service( "router" )->parseRequest( $request );
	
	// parse response through the renderer and display output
	\Conference::service( "renderer" )->render( $response );

//_____________________________________________________________________________________________
//

/**
 * todos:
 * 
 * credit icon host: <div>Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
 */