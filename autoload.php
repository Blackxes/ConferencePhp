<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * autoloader for dynamic file inclusion
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

/**
 * autoloader function to include files by namespace
 * 
 * @param string $class - the classname including namespace
 * 
 * @return boolean
 */
function DynamicAutoloader($class)
{
	// turn slashes
	$path = CONREN_ROOT . DIRECTORY_SEPARATOR . str_replace( "\\", DIRECTORY_SEPARATOR, $class ) . ".php";

	if ( !file_exists($path) )
		return false;
	
	return (bool) require_once( $path );
}

/**
 * register autoloader
 */
spl_autoload_register('DynamicAutoloader');

//_____________________________________________________________________________________________
//
