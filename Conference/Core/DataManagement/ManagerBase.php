<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * Todo: edit
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\DataManagement;

//_____________________________________________________________________________________________
class ManagerBase extends namespace\DataCache {

	/**
	 * table prefix
	 * 
	 * @var string
	 */
	private $_prefix;

	/**
	 * base table for this manager
	 * 
	 * @var string
	 */
	private $_table;

	/**
	 * construction
	 * 
	 * @param string $table - the table for this manager
	 */
	public function __construct( string $table, array $cacheOptions = [] ) {

		parent::__construct( $cacheOptions );

		$this->_table = $table;
		$this->_prefix = strtolower( $table[0] );
	}

	/**
	 * returns all records
	 * 
	 * @param string $index - the leading index
	 */
	protected function preparedAll( string $index = "uid", array $options = [] ) {

		if ( !$this->verify() || !$index )
			return false;
		
		if ( $this->hasCachedData("all", $index) )
			return $this->getCachedData( "all", $index );

		$db = \Conference::db();

		$db->query( "select", "select_keyword" );
		$db->query( $this->_prefix, "columns" );
		$db->query( "from", "from_keyword" );
		$db->query( $this->_table, "selected_table" );
		$db->query( "as $this->_prefix", "table_substitution" );

		$db->execute();

		return $this->cache( "all", $index, $db->fetch($index, $options) );
	}

	/**
	 * deletes records
	 * 
	 * @param string $value - the where condition value
	 * @param string $index - the index
	 */
	protected function preparedDelete( $value, string $index = "uid" ) {
		
		$db = \Conference::db();

		$db->query( "delete from", "delete_keyword" );
		$db->query( $this->_table, "table" );
		$db->query( "as $this->_prefix", "table_substitution" );
		$db->query( "where", "where" );
		$db->query( "$this->_prefix.$index = :value", "where_statement" );
		
		$db->bindValue( ":value", $value );

		return $db->fetch();
	}

	/**
	 * returns a single record
	 * 
	 * @param mixed $request - the requested value of the index
	 * @param string (optional | Default = "uid") $index - table index/column
	 * @param callable $callback - a callback which is called before executing the prepared query
	 * 
	 * @return \ParameterBag - the data
	 */
	protected function preparedGet( $value, string $index = "uid", $options = [] ) {

		if ( !$this->verify() || !$index )
			return false;
		
		if ( $this->hasCachedData("get", "{$index}_{$value}") )
			return $this->getCachedData( "get", "{$index}_{$value}" );
		
		$db = \Conference::db();

		$db->query( "select", "select_keyword" );
		$db->query( "$this->_prefix.*", "columns" );
		$db->query( "from", "from_keyword" );
		$db->query( $this->_table, "selected_table" );
		$db->query( "as $this->_prefix", "table_substitution" );
		$db->query( "where", "where_keyword" );
		$db->query( "$this->_prefix.$index = :value", "where_statement" );

		$db->bindValue( ":value", $value );

		$db->execute();

		// since a single record can block the identifier of the cache
		// the request value needs to be stored as well
		$data = $this->cache( "get", "{$index}_{$value}", $db->fetch($index, $options ) );
		
		return $data;
	}

	/**
	 * inserts a records
	 * 
	 * @param array $cols - table columns
	 * @param array $vals - values of the record
	 */
	protected function preparedInsert( array $columns, array $values ) {
		
		$db = \Conference::db();
		
		$db->query( "insert into", "insert_keyword" );
		$db->query( $this->_table, "table" );
		$db->query( "as $this->_prefix", "table_substitution" );
		
		$db->query( implode(",", $this->columns), "columns" );
		
		foreach( $values as $column => $value )
			$db->bindValue( ":$column", $value );
		
		$db->execute();
		
		return $db->getConn()->lastInsertId();
	}

	/**
	 * updates records
	 * 
	 * @param array $sets - associative array of cols and there new value
	 * @param $request - requested column
	 * @param string $index - the index
	 */
	protected function preparedUpdate( array $sets, $request, string $index = "uid" ) {

		$db = \Conference::db();

		$db->query( "update", "update_keyword" );
		$db->query( $this->_table, "table");
		$db->query( "as $this->_prefix", "table_substitution" );

		$db->query( "set", "set_keyword" );

		foreach( $sets as $key => $value )
			$db->query( "$key = :$key", "set_$key");
		
		foreach( $sets as $key => $value )
			$db->bindValue( ":$key", $value );
		
		$db->execute();

		return $db->fetch();
	}

	/**
	 * verifies the use of the defined functions of this class
	 * when not verified the function either need to be overriden
	 * or the tablename has to be defined before using \ManagerBase methods
	 */
	private function verify() {

		return !empty( $this->_table );
	}
}

//_____________________________________________________________________________________________
//