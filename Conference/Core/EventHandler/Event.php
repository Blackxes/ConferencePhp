<?php
/**********************************************************************************************
 * 
 * @File
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\EventHandler\Event;

class Event extends Classes\ParameterBag {

	/**
	 * event type / in bag
	 * 
	 * @var string
	 */
	# private $type;

	/**
	 * event callback / in bag
	 * 
	 * @var function
	 */
	# private $fn;

	/**
	 * callback context / in bag
	 * 
	 * @var object
	 */
	# private $context;

	/**
	 * construction
	 * 
	 * @param string $type - the event type
	 * @param function $fn - the callback function
	 * @param object (optional) $context - the context of the callback
	*/
	public function __construct( string $type, callable $fn, object $context = null ) {

		# without a type the event is useless
		if ( !$type )
			throw new \Exception( "Invalid type. Nothing given" );

		# the event cant be created when the context is defined but not an object to bind to
		if ( $context != null && !is_object($context) )
			throw new \Exception( "invalid context for event. Type: $type" );

		# define as readonly to prevent manipulation
		parent::__construct( ["type" => $type, "fn" => $fn, "context" => $context], true );
	}
}