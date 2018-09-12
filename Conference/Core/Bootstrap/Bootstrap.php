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

//_____________________________________________________________________________________________
class Bootstrap {

	/**
	 * database
	 */
	static public $db = null;

	/**
	 * current user / when null its anonymous
	 */
	static public $user = null;

	/**
	 * instance of the service manager
	 */
	static public $sm = null;

	/**
	 * user handler instance
	 */
	static public $um = null;
	
	/**
	 * construction is prohibited / the system will be initialized globally
	 * when constructing an instance the instance somehow needs to be stored within
	 * or somewhere to access the database/ the user/ etc.
	 */
	private function __construct() {}

	/**
	 * initialization / configuration is done in the configuration file
	 */
	static public function Init() {

		// start session
		session_start();

		// include files
		require_once( CONREN_ROOT . "/Conference/Core/Configuration/Configuration.php" );
		require_once( CONREN_ROOT . "/Conference/Core/Debugging/Debugging.php" );
		require_once( CONREN_ROOT . "/Conference/Core/Conference.php" );

		require_once( CONREN_ROOT . "/Conference/Core/Dependencies/Logfile/Logfile.php" );
		require_once( CONREN_ROOT . "/Conference/Core/Dependencies/Routinus/Routinus.php" );
		require_once( CONREN_ROOT . "/Conference/Core/Dependencies/Templax/Templax.php" );
		
		// initializations
		$dbc = $GLOBALS["CONREN"]["Database"];
		self::$db = new \PDO( "mysql:host={$dbc["host"]}; dbname={$dbc["database"]}; charset=utf8;", $dbc["user"], $dbc["password"] );
		
		\Logfile\Logfile::Init( self::$db );
		\Templax\Templax::Init( $GLOBALS["CONREN"]["Rendering"]["Templates"] );
		// \Buildorm\Buildorm::define( $GLOBALS["CONC"]["Formular"]["Forms"] );

		// definitions
		self::$sm = new Core\Service\ServiceManager;
		self::$sm->Init( $GLOBALS["CONREN"]["Service"]["Services"] );

		// self::$um = self::$sm->get( "userManager" );
		// self::$user = self::$um->getUser();
	}
}

//_____________________________________________________________________________________________
//