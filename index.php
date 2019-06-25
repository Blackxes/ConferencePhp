<?php
/**********************************************************************************************
 * 
 * .. and the story begins ..
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

require_once( "./bootstrap.php" );

$v1 = (int) str_replace( ".", "", "7.2.13" );
$v2 = (int) str_replace( ".", "", "5.6.3" );

var_dump( version_compare(phpversion(), "7.2.18") );

print_r( phpversion() );

# boot system
// \Conference\Core\Bootstrap\Bootstrap::init();

exit;

# build request
$request = \Conference::service( "request.handler" );

# build response based on the built request
$response = \Conference::service( "router" )->parseRequest( $request );

# render content
\Conference::service( "renderer" )->render( $response );