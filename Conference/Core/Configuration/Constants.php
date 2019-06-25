<?php
/**********************************************************************************************
 * 
 * @File Constants.php
 * 
 * 
 * @Author: Alexander Bassov
 * 
/*********************************************************************************************/

# versioning
const C7_VERSION = "1.0.0";

# minimum php version
const C7_PHP_MIN_VERSION = "7.2.13";

# paths
const C7_PATH_ASSETS = "assets";
const C7_PATH_ASSETS_ABS = CONREN_ROOT . "assets";
const C7_PATH_TEMPLATES = "Conference/Core/Templates";
const C7_PATH_TEMPLATES_ABS = CONREN_ROOT . "/Conference/Core/Rendering/Templates";
const C7_PATH_UPLOADS = "uploads";
const C7_PATH_UPLOADS_ABS = CONREN_ROOT . "uploads";

# Note: currently not in use
// const CONREN_PATH_PACKAGE = "Packages";
// const CONREN_PATH_PACKAGE_ABS = CONREN_ROOT . "Packages";

# enrivonment / debugging
const C7_ENVIRONMENT = "dev";
const C7_ENABLE_BENCHMARKING = false;

# database
const C7_DATABASE_CONNECTION = [
	"db" = "Conference",
	"host" => "localhost",
	"user" => "root",
	"password" => ""
];