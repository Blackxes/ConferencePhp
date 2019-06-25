<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * initial start of the application .. here.. all the magic starts here
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Bootstrap;

use \Conference\Core;
use \Conference\Core\Database;

//_____________________________________________________________________________________________
class Bootstrap {

	/**
	 * database instance
	 */
	static public $dbal = null;

	/**
	 * instance of the service manager
	 */
	static public $serviceManager = null;

	/**
	 * package manager instance
	 */
	static public $packageManager = null;
	
	/**
	 * no need to create an instance of the Bootstrap class
	 * since the system will be initialized globally and only once
	 */
	private function __construct() {}
		
	/**
	 * initializes the system
	 * include files/ defines runtime constants and 
	 */
	static public function Init() {

		session_start();

		require_once( C7_ROOT . "/Conference/Core/Configuration/Configuration.php" );
		// require_once( C7_ROOT . "/Conference/Core/Configuration/Translations.php" );
		require_once( C7_ROOT . "/Conference/Core/Debugging/Debugging.php" );
		require_once( C7_ROOT . "/Conference/Core/Conference.php" );

		require_once( C7_ROOT . "/Conference/Core/Dependencies/Logfile/Logfile.php" );
		require_once( C7_ROOT . "/Conference/Core/Dependencies/Routinus/Routinus.php" );
		require_once( C7_ROOT . "/Conference/Core/Dependencies/Templax/Templax.php" );

		# define base url
		$GLOBALS["CONREN"]["General"]["domain"] = namespace\Domain::fromGlobals();

		# database
		self::$db = new Database\Database( $GLOBALS["CONREN"]["Database"]["Connection"] );
		
		# service manager
		// self::$serviceManager = ( new Core\Service\ServiceManager() )->init();

		// initializations
		// self::$sm->get( "templax" )->Init(
		// 	$GLOBALS["CONREN"]["Rendering"]["Templates"],
		// 	self::$sm->get( "renderer" )->getDefaultTemplateMarkup()
		// );

		// $templax = \Conference::service("templax");

		// $templax->registerSet( ["category_menu", "category_side_menu", "category_side_menu_recursive", "category_listing"], [
		// 	"file" => _CONREN_TEMPLATEPATH . "/category.html",
		// ]);
	}
}

//_____________________________________________________________________________________________
//