<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * .. and the story begins ..
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