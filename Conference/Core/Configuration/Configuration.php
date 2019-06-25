<?php
/**********************************************************************************************
 * 
 * preprocessing system configuration
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

# general information
$GLOBALS["C7"] = [
	"Version" => "1.0.0",
	"Assets" => [
		"Styles" => [ C7_PATH_ASSETS . "/css/main.css" ],
		"Scripts" => [ C7_PATH_ASSETS . "/js/bundle.js", "Client/dist/" ]
	]
];


$GLOBALS["C7"]

/**
 * styles and scripts
 */
$GLOBALS["C7"]["Assets"] = array(
	"Styles" => [
		CONREN_PATH_ASSETS . "/css/main.css",
	],
	"Scripts" => [
		CONREN_PATH_ASSETS . "/js/bundle.js"
	]
);

/**
 * database
 */
$GLOBALS["C7"]["Database"]["Connection"] = [
	"db" => "emhashop_final",
	"host" => "localhost",
	"user" => "root",
	"password" => ""
];

/**
 * routing management
 */
$GLOBALS["C7"]["Routing"] = [

	# default route when nothing is requested expect the bse domain
	"defaultRoute" => "products",
];

/**
 * service configurations
 */
$GLOBALS["C7"]["Service"]["Configuration"] = array(

	/**
	 * defines wether the registration shall cancel or overwrite existing services
	 */	
	"overwriteDuplicates" => false,

	/**
	 * defines wether the service set registration shall continue
	 * or stop when invalid values or failure occure while registering a service
	 * 
	 * Todo: implement!
	 */
	"aggressiveSetRegistration" => false
);

/**
 * several form configurations and classes
 */
$GLOBALS["C7"]["Formular"]["Forms"] = array();

/**
 * template configurations / here are the core templates
 */
$GLOBALS["C7"]["Rendering"]["Templates"] = [
	"templates" => [
		"conren_root",
		"conren_attribute_list",
		"conren_basic_message",
		"conren_htmlelement_singletag",
		"conren_htmlelement_multitag",
		"conren_link",
		"conren_purchase"
	],
	"options" => [
		"dir" => CONREN_PATH_TEMPLATES_ABS . "/",
		"file" => "templates.html",
		"silent" => true
	]
];

/**
 * the default template
 */
$GLOBALS["C7"]["Rendering"]["BaseTemplate"] = "conren_root";

//_____________________________________________________________________________________________
//