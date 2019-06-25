<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * stores and indexed data
 * 
 * this class enables reusable data usage and reduces database requests
 * therefore increasing execution speed of the application
 * 
 * all data is publically stored in this storage following this naming convention
 * data_KEY_INDEX_
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\DataManagement;

//_____________________________________________________________________________________________
class DataCache {

	/**
	 * list of all identifiers
	 * 
	 * @var array
	 */
	private $_identifiers;

	/**
	 * cached data indexed by the following naming convention
	 * 
	 * DATAKEY_DATAINDEX => data
	 * 
	 * @var array
	 */
	private $_data;
	
	/**
	 * options for each key
	 * 
	 * overwritable - defines wether, when the same identifier is given
	 * 	the data may be overwritten or ignore the caching
	 * 
	 * @var array
	 */
	private $_options;
	
	/**
	 * construction
	 */
	public function __construct( array $options = [] ) {

		$this->_identifiers = [];
		$this->_data = [];
		
		$this->_options = array_merge([

			"overwritable" => false

		], $options );
	}

	/**
	 * returns the identifier created from the key and index
	 */
	private function buildIdentifier( string $key, string $index ) {

		if ( !$key || !$index )
			return false;
		
		$identifier = $key . "_" . $index;

		return $identifier;
	}

	/**
	 * checks wether the identifier is defined and not blocked
	 * 
	 * @return boolean - true when no blocked else false
	 * @return null - when not defined
	 */
	protected function blocked( string $identifier ) {

		if ( !$this->has($identifier) )
			return null;
		
		return !$this->_identifiers[ $identifier ];
	}
	
	/**
	 * caches the given data under the passed index
	 * 
	 * @param $key string - the data key ( eg. products )
	 * @param $index string - the data index ( eg. article_name )
	 * @param $data mixed - the actual data
	 * @param $options array - options
	 * 
	 * @return mixed - reference to the cached data
	 */
	protected function cache( string $key, string $index, $data, array $options = [] ) {

		$identifier = $this->buildIdentifier( $key, $index );
		$options = $this->parseOptions( $identifier, $options );

		// return cached when not overwriteable
		if ( $this->has($identifier) ) {
			if ( !$options["overwritable"] )
				return $this->select( $identifier );
		}

		// cache data
		if ( !$this->verify($identifier, $options) )
			throw new \Exception( "DataCache: caching data unsuccessful (identifier: $identifier)" );
		
		return $this->insert( $identifier, $data );
	}

	/**
	 * deletes cached data
	 */
	protected function deleteCachedData( string $key, stirng $index ) {

		$identifier = $this->buildIdentifier( $key, $index );

		if ( !$identifier || !$this->has($identifier) )
			return false;
		
		unset( $this->_data[$identifier] );
		unset( $this->_identifiers[$identifier] );
		
		return true;
	}

	/**
	 * returns cached data
	 * 
	 * Todo: complete comment
	 */
	protected function getCachedData( string $key, string $index ) {

		$identifier = $this->buildIdentifier( $key, $index );
		
		if ( $this->blocked($identifier) )
			return null;
		
		return $this->_data[ $identifier ];
	}

	/**
	 * checks if the identifier is defined
	 * 
	 * Todo: complete comment
	 */
	private function has( string $identifier ) {

		return array_key_exists( $identifier, $this->_identifiers );
	}

	/**
	 * returns true or false wether data is cached or not
	 * 
	 * @param string $key - the key
	 * @param string $index - the index
	 */
	protected function hasCachedData( string $key, string $index ) {

		$identifier = $this->buildIdentifier( $key, $index );

		return $this->has($identifier);
	}
	
	/**
	 * inserts the data by identifier into the cache
	 * and indexes the identifier
	 */
	private function insert( $identifier, $data ) {

		$this->_identifiers[$identifier] = true;
		$this->_data[$identifier] = $data;

		return $this->_data[$identifier];
	}

	/**
	 * returns a merge from the passed options over the defined ones
	 * indexed by the identifier
	 */
	private function parseOptions( string $identifier, array $options ) {

		return array_merge( $this->_options, (array) $this->_options[$identifier], $options );
	}

	/**
	 * returns a reference to the cached data
	 */
	protected function &refCachedData() {

		$identifier = $this->buildIdentifier( $key, $index );

		if ( $this->blocked($identifier) )
			return null;
		
		return $this->_data[ $identifier ];
	}

	/**
	 * returns the data defined by the given identifier
	 * 
	 * @param string $identifier - identifier of the data
	 * 
	 * @return mixed - the data
	 * @return null - no data found or the cache for this identifier is blocked
	 */
	private function &select( string $identifier ) {

		if ( $this->blocked($identifier) )
			return null;
		
		return $this->_data[ $identifier ];
	}

	/**
	 * verifies the caching process
	 */
	private function verify( string $identifier, array $options ) {

		// Note: conditions are split for easier reading

		// check identifier wether blocked or not valid
		if ( !$identifier || $this->blocked($identifier) )
			return false;

		return true;
	}
}

//_____________________________________________________________________________________________
//