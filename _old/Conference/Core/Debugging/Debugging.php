<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	// TODO: do description

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/



//_____________________________________________________________________________________________
// based on the environment display errors or not
$__CONFERENCE_DEBUG = ( $GLOBALS["Conference"]["Environment"] == "development" );

// error_reporting( E_ALL & ~E_NOTICE );

//_____________________________________________________________________________________________
if ( $__CONFERENCE_DEBUG )
	error_reporting( E_ALL & ~E_NOTICE );
else
	error_reporting( 0 );

//_____________________________________________________________________________________________
// debugging function
//
// methods:
//	e => echo
//	r => print_r
//	v => var_dump
//
function debug( $value, $method = "r" ) {
	
	if ( !$__CONFERENCE_DEBUG )
		return true;
	
	echo '<pre>';
	switch( $method ) {
		case ("e"): echo $value; break;
		case ("r"): print_r($value); break;
		case ("v"): var_dump($value); break;
		default: print_r("undefined print method in debug()");
	}
	echo '</pre>';
}

//_____________________________________________________________________________________________
//