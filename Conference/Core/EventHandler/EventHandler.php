<?php
/**********************************************************************************************
 * 
 * stores a set of events and parses them successively
 * while parsing the listener added to the system are still able to add more events
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\EventHandler;

class EventHandler {

	/**
	 * contains all events which will be parsed when executing the EventSystem::run function
	 * 
	 * @var array
	 */
	private $queue;

	/**
	 * contains listener to the events
	 * 
	 * @var array
	 */
	private $listener;

	/**
	 * construction
	 */
	private function __construct() {

		$this->queue = [];
		$this->listener = [];
	}

	/**
	 * adds an event to the queue
	 */

	/**
	 * create and returns a new event
	 * 
	 * @param string $type - the event type
	 * @param mixed $data - the data passed to the listener
	 * @param object $context - the $this context of the callback of the listener
	 */
	public function createEvent( string $type, $data ) {

		if ( !$type || !$base->valid() )
			throw new Exception( "invalid type" );

		return new namespace\Event( $type, $data );
	}

	/**
	 * adds a listener to an event
	 */
	public function addListener( string $type, callable $fn ) {
		
		$this->listener[$type][] = $fn;
	}

	/**
	 * starts the event loop and parses all events
	 * calls the callback functions when a type matches the listener and risen event type
	 */
	public function run() {

		if ( empty($this->queue) )
			return true;
		
		while( $evt = &array_shift($this->listener) ) {
			echo "<pre>";
				print_r( $evt );
			echo "</pre>";
		}
	}
}