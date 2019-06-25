<?php
/**********************************************************************************************
 * 
 * singleton service class management
 * 
 * stores and returns class instances
 * every 
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

# Idea: implement service context
#	serviceKey: manager / context: user
#	serviceKey: manager / context: routes

namespace Conference\Core\Service;

class ServiceManager {

	/**
	 * contains all services / value is either the class instance
	 * or the class name depending on wether the service has been called
	 * 
	 * @var array
	 */
	private $services;

	/**
	 * construction
	 * 
	 * @param array $services - list of services
	 */
	public function __construct( array $services =  [] ) {

		$this->services = [];
	}

	/**
	 * returns all services
	 * 
	 * @param array $index - the index of the array with the records
	 * @param array $options - the options passed to the record fetching
	 * 
	 * @return array - the records
	 */
	public function all( string $index = "uid", array $options = [] ) {

		$db = \Conference::db();

		$db->query( "select s.* from `c7_services` as s" );
		$db->query( "order by s.key" );

		$db->execute();

		return $db->fetch( $index, $options );
	}

	/**
	 * delöetes
	 * 
	 * HeadComment:
	 */
	public function delete( $value, string $index = "uid" ) {

		$db = \Conference::db();

		$db->query( "delete from `c7_services` where {$index}=:value" );

		$db->bindValue( ":value", $value );

		$db->execute();

		return $db->fetch();
	}

	/**
	 * returns requested services
	 */
	public function get( $value, string $index = "uid", array $options = [] ) {

		$db = \Conference::db();

		$db->query( "select s.* from `c7_services` as s" );
		$db->query( "where s.{$index}=:value" );
		$db->query( "order by s.key" );

		$db->bindValue( ":value", $value );

		$db->execute();

		return $db->fetch( $index, $options );
	}

	/**
	 * returns the existance of a service as boolean
	 * the EXISTANCE not is its intantiated!
	 * 
	 * @param1 string - expects the service id
	 * 
	 * @return boolean - true when exists false when not
	 */
	public function has( string $id ) {

		return isset( $this->services[$id] );
	}

	/**
	 * intialization / when not able to initialize via the constructor
	 * here is the possibily afterwards
	 * 
	 * @param1 array - expects the service configurations
	 * 
	 * @return boolean - true on success false when failure occured see Logfile for more info
	 */
	public function init() {
		
		$servs = $this->all();

		foreach( $servs as $id => $service )
			$this->register( $service->get("key"), $service->get("source") );
		
		return $this;
	}

	/**
	 * instantiates a service
	 * 
	 * HeadComment:
	 */
	public function &instantiate( string $key ) {

		if ( !$this->has($key) || !class_exists($this->services[$key]) )
			return false;

		$service = new $this->services[$key]();

		$this->services[ $key ] = $service;

		# initialize when possible
		if ( \method_exists($service, "init") ) {

			$refl = new \ReflectionMethod( $service, "init" );

			if ( $refl->isPublic() )
				$service->init();
		}

		return $this;
	}

	/**
	 * returns the state of instantiation of a service
	 * wether its instantiated or not
	 * 
	 * @param1 string - expects the service id
	 * 
	 * @return boolean - true when instantiated false when not
	 */
	public function instantiated( string $key ) {

		return is_object( $this->services[$key] );
	}

	/**
	 * Todo: edit function comment / the throw is incorrect later on due to implementation of custom exceptions
	 * 
	 * registers a service
	 * 
	 * @param1 string -  expects the service id
	 * @param2 string - expects the service class name
	 * 
	 * @return boolean - when registration succeeded / it includes when the aggressiveRegistration
	 * 		option is true that
	 * 
	 * @throw \Exception - on duplicates or invalid values
	 */
	public function register( string $id, string $source ) {

		# Todo: rewrite implementation

		$sconf = $GLOBALS["CONREN"]["Service"]["Configuration"];

		// quit when existing service is not allowed to be overwritten
		if ( $this->has($id) && !$sconf["overwriteDuplicates"] )
			return true;

		if ( empty($id) || empty($source) )
			return false;
		
		// final registration / this also overwrites existing services when allowed
		$this->services[$id] = $source;
		
		return true;
	}

	/**
	 * registers a service from post data
	 * 
	 * HeadComment:
	 */
	public function registerFromPost() {

		if ( !$_POST["service_register"] )
			return false;
		
		$db = \Conference::db();

		$db->query( "insert into `c7_services` (`key`, `source`) values (:key, :source)" );

		$db->bindValue( ":key", trim($_POST["key"]) );
		$db->bindValue( ":source", trim($_POST["source"]) );

		$db->execute();

		return true;
	}

	/**
	 * returns an instance of a service
	 * 
	 * HeadComment:
	 */
	public function &service( string $key ) {

		# check if service is already instantiated
		if ( $this->instantiated($key) )
			return $this->services[ $key ];
		
		# try instantiation
		if ( !$this->instantiate($key) )
			return null;
		
		# recursively get service since its instantiated now
		$service = $this->service( $key );

		return $service ? $service : null;
	}

	/**
	 * updates a service via sent post data
	 * 
	 * HeadComment:
	 */
	public function updateFromPost() {

		if ( !$_POST["service_update"] )
			return false;
		
		$db = \Conference::db();

		$db->query( "update `c7_services` as s set s.key=:key, s.source=:source where s.uid=:uid" );

		$db->bindValue( ":key", $_POST["key"] );
		$db->bindValue( ":source", $_POST["source"] );
		$db->bindValue( ":uid", $_POST["service_id"] );

		$db->execute();

		return true;
	}

}

//_____________________________________________________________________________________________
//