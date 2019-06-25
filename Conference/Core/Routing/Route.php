<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * route object model
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Routing;

//_____________________________________________________________________________________________
class Route {

	/**
	 * route id
	 * 
	 * @var id|null
	 */
	public $uid;

	/**
	 * the route creation date
	 * 
	 * @var int|null
	 */
	public $crdate;

	/**
	 * the last time this route has been updated
	 * 
	 * @var int|null
	 */
	public $updated;

	/**
	 * describes wether this route is active
	 * inactive route are visible in the system but not accessable
	 * 
	 * @var boolean
	 */
	public $active;

	/**
	 * describes wether this route is hidden
	 * hidden route are completely hidden from the system
	 * 
	 * @var boolean
	 */
	public $hidden;

	/**
	 * contains the current route
	 * 
	 * @var string|null
	 */
	public $route;

	/**
	 * the segment count of this route
	 * 
	 * @var int
	 */
	public $segmentCount;

	/**
	 * the controller this route belongs to
	 * 
	 * @var string
	 */
	public $controller;

	/**
	 * the entrance function in the controller
	 * 
	 * @var string
	 */
	public $entrance;

	/**
	 * defines which roles have access to this route
	 * 
	 * @var array
	 */
	public $access;
	
	/**
	 * contains possible variables in this route
	 * 
	 * @var array
	 */
	public $variables;

	/**
	 * construction
	 * 
	 * @param string|null $route - the route
	 * @param array|null $variables - the variables within the route
	 */
	public function __construct( ?string $route = null, ?array $variables = array() ) {

		$this->route = $route;
		$this->variables = $variables;
	}

	/**
	 * defines this object by the given object and returns $this
	 * properties have to be public
	 * 
	 * @return $this
	 */
	public function defineByObject( $obj ) {

		foreach( $obj as $key => $value )
			if ( property_exists($this, $key) )
				$this->$key = $value;

		return $this;
	}
}

//_____________________________________________________________________________________________
//