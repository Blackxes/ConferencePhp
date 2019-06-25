<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * Todo: edit
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Routing;

use \Conference\Core\DataManagement;

//_____________________________________________________________________________________________
class RoutesManager extends DataManagement\ManagerBase {

	/**
	 * construction
	 */
	public function __construct() {
		
		// readonly object
		parent::__construct( "conren_routes" );
	}

	/**
	 * returns a route
	 * 
	 * HeadComment: complete
	 */
	public function get( $value, $index = "uid", array $options = [] ) {

		return $this->preparedGet( $value, $index, $options );
	}

	/**
	 * returns all routes
	 */
	public function all( $index = "uid", array $options ) {

		return $this->preparedAll( $index, $options );
	}

}

//_____________________________________________________________________________________________
//