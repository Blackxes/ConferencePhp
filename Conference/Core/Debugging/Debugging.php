<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * debugging management
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

// global namespace

/**
 * Todo: implement debugging system to
 * 	- [DONE] log messages
 *	- and exceptions
 */

if ( $GLOBALS["CONREN"]["Environment"] == "dev" ) {

	error_reporting( E_ALL & ~E_NOTICE );
	ini_set( "display_errors", 1 );
}