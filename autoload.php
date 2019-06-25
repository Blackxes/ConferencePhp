<?php
/**********************************************************************************************
 * 
 * autoloader for dynamic file inclusion
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

/**
 * the most basic autoloader
 * 
 * @param string $className - class name
 * 
 * @return boolean
 */
function BasicAutoloader( $className ) {

	$path = C7_ROOT . DIRECTORY_SEPARATOR . str_replace( "\\", DIRECTORY_SEPARATOR, $className ) . ".php";

	return !file_exists($path) ? false : require_once( $path );
}

spl_autoload_register('BasicAutoloader');

//---------------------------------------------------------------------------------------------
