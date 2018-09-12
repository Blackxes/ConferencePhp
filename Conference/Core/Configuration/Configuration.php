<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * all system configuration is in here
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

/**
 * general informations and configurations
 */
$GLOBALS["CONREN"]["General"] = array(
	"version" => "1.0.0",
	"prefix" => "conren_",
	"domain" => "http://localhost/php/Conference",

	"baseRoute" => "/home",
	"baseWebpageTitle" => "Emha Werkzeuge"
);

/**
 * environment
 */
$GLOBALS["CONREN"]["Environment"] = "dev";

/**
 * database
 */
$GLOBALS["CONREN"]["Database"] = array(
	"database" => "conference",
	"host" => "localhost",
	"user" => "root",
	"password" => ""
);

/**
 * service configurations / here are the core services
 * custom services are addable by calling the service manager and registering the custom ones
 */
$GLOBALS["CONREN"]["Service"]["Services"] = array(
	"requestHandler" => "\Conference\Core\Routing\RequestHandler",
	"router" => "\Conference\Core\Routing\Router",
	"renderer" => "\Conference\Core\Rendering\Renderer",
	"logfile" => "\Logfile\Logfile",
	"userManager" => "\Conference\Core\Manager\UserManager"
);

/**
 * service configurations
 */
$GLOBALS["CONREN"]["Service"]["Configuration"] = array(

	/**
	 * defines wether the registration shall cancel or overwrite existing services
	 */	
	"overwriteDuplicates" => false,

	/**
	 * defines wether the service set registration shall continue
	 * or stop when invalid values or failure occure while registering a service
	 */
	"aggressiveSetRegistration" => false
);

/**
 * several form configurations and classes
 */
$GLOBALS["CONREN"]["Formular"]["Forms"] = array();

/**
 * substitution for the long template path
 */
const _CONREN_TEMPLATEPATH = CONREN_ROOT . "/Conference/Core/Rendering/Templates";

/**
 * template configurations / here are the core templates
 * custom templates are easily registered by calling the registerTemplate function
 * of the render engine "\Templax\Templax"
 * 
 * see more under how the to use templates
 */
$GLOBALS["CONREN"]["Rendering"]["Templates"] = array(
	"conren_root" => array( "file" => _CONREN_TEMPLATEPATH . "/page.html", "marker" => "conren_root" ),
	"conren_attribute_list" => array( "file" => _CONREN_TEMPLATEPATH . "/page.html", "marker" => "conren_attribute_list" )
);

/**
 * the default template
 */
$GLOBALS["CONREN"]["Rendering"]["BaseTemplate"] = "conren_root";

/**
 * default system markup basically to build up the page structure
 */
$GLOBALS["CONREN"]["Rendering"]["BaseMarkup"] = array();

/**
 * default options
 */
$GLOBALS["CONREN"]["Rendering"]["BaseOptions"] = array();

/**
 * markup hooks
 */
$GLOBALS["CONREN"]["Rendering"]["BaseHooks"] = array();

//_____________________________________________________________________________________________
//