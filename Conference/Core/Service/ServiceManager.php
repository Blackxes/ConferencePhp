<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * manages services to exist only once and being globally accessible
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Service;

// use \Logfile\Logfile;

//_____________________________________________________________________________________________
class ServiceManager {

	/**
	 * contains all services / value is either the class instance
	 * or the class name depending on wether the service has been called
	 */
	private $services;

	/**
	 * construction
	 * 
	 * @param1 array - expects the service configurations
	 */
	public function __construct( array $services = array() ) {

		$this->services = array();
	}

	/**
	 * intialization / when not able to initialize via the constructor
	 * here is the possibily afterwards
	 * 
	 * @param1 array - expects the service configurations
	 * 
	 * @return boolean - true on success false when failure occured see Logfile for more info
	 */
	public function Init( array $services ) {

		if ( empty($services) ) return true;

		foreach( $services as $id => $source )
			$this->register($id, $source);
		
		return true;
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

		$sconf = $GLOBALS["CONC"]["Service"]["Configuration"];

		// quit when existing service is not allowed to be overwritten
		if ( $this->has($id) && !$sconf["overwriteDuplicates"] )
			return true;

		if ( empty($id) || empty($source) ) return false;
		
		// final registration / this also overwrites existing services when allowed
		$this->services[$id] = $source;
		
		return true;
	}

	/**
	 * returns an instance of a service
	 * 
	 * @param1 string - expects the service id
	 * 
	 * @return null - when no service under given id has been found
	 * @return object - the service instance
	 */
	public function &get( string $id ) {
		
		// check if service exists and the instantiation succeeds
		if ( !$this->instantiate($id) )
			return null;

		return $this->services[$id];
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
	 * returns the state of instantiation of a service
	 * wether its instantiated or not
	 * 
	 * @param1 string - expects the service id
	 * 
	 * @return boolean - true when instantiated false when not
	 */
	public function instantiated( string $id ) {

		return is_object( $this->services[$id] );
	}

	/**
	 * instantiates a service
	 * 
	 * @param1 string - expects the service id
	 * 
	 * @return boolean - true on successful instantiation false when service doesnt exists
	 */
	public function instantiate( string $id ) {

		if ( !$this->has($id) ) return false;

		if ( $this->instantiated($id) ) return true;
		
		// instantiate
		$class = $this->services[$id];
		$this->services[$id] = new $class();

		return true;
	}
}

//_____________________________________________________________________________________________
//