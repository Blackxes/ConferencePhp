<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * Todo: edit
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Database;

use \Conference\Core\Classes;

//_____________________________________________________________________________________________
// substitute for an actual database class / extremely simplified
class Database {

	/**
	 * statement
	 */
	private $stmt;

	/**
	 * database connection
	 */
	private $conn;

	/**
	 * config
	 */
	private $config;

	/**
	 * current query
	 */
	private $query;

	/**
	 * describes wether the query has been prepared or not
	 */
	private $prepared;

	/**
	 * construction
	 */
	public function __construct( $db, string $user = null, string $password = null, string $host = "localhost" ) {

		// either use the given config or a separated
		$this->Init( (is_array($db))
			? array_merge( (array) $this->config, $db )
			: array( "db" => $db, "host" => $host, "user" => $user, "password" => $password )
		);

		$this->query = array();
		$this->prepared = false;

		$this->config = array(
			"fetch" => array(
				"index" => "uid",
				"method" => \PDO::FETCH_ASSOC,
				"wrapInBag" => true,
				"single" => false
			)
		);
	}

	/**
	 * apply aggregate functions
	 */
	public function applyAggregate( &$db, array $options ) {
		
		if ( $options["group"] ) $db->query( "group by " . $options["group"] );
		if ( $options["order"] ) $db->query( "order by " . $options["order"] );
		if ( $options["limit"] ) $db->query( "limit " . $options["limit"] );
		if ( $options["offset"] ) $db->query( "offset " . $options["offset"] );
		
		return true;
	}

	/**
	 * builds an fields array with prefix + field as strings
	 * 
	 * $fields = [
	 *		"table_substitute" => ["field_name", "second_field_name"],
	 *		"table_substitute_2" => [["field", "field_substitute"], "another_table_field"],
	 *	];
	 * 
	 * HeadComment:
	 */
	public function buildFields( $fields ) {

		$built = [];

		foreach( $fields as $prefix => $columns ) {
			foreach( $columns as $index => $name ) {
				# catch asterisk
				if ( is_string($name) && strpos(trim($name), "*") !== false ) {
					$built[] = $prefix . ".*";
					continue;
				}

				# when substitution
				if ( is_array($name) ) {
					$built[] = $prefix . ".`" . $name[0] . "` as `" . $name[1] . "`";
					continue;
				}

				$built[] = $prefix . ".`" . $name . "`";
			}
		}
		
		return implode( ", ", $built );
	}

	/**
	 * internal initialization
	 */
	private function Init( $config ) {

		$this->conn = new \PDO( "mysql:host={$config["host"]}; dbname={$config["db"]}; charset=utf8;", $config["user"], $config["password"] );

		return true;
	}

	/**
	 * adds sql to the current query and returns the query
	 * 
	 * @param string $query - a query string
	 * @param string (optional) $key - the 
	 */
	public function &query( string $query, string $key = null ) {

		if ( $reset )
			$this->reset();

		if ( !is_null($key) )
			$this->query[$key] = $query;
		else
			$this->query[] = $query;

		return $this->query;
	}
	
	/**
	 * prepares the query
	 */
	public function prepare() {

		if ( empty($this->query) )
			return false;

		$this->stmt = $this->conn->prepare( implode(" ", $this->query) . ";" );

		$this->prepared = true;

		return $this->stmt;
	}

	/**
	 * binds a parameters bags parameter
	 */
	public function bindParameterBag( Classes\ParameterBag $bag ) {

		if ( !$this->prepared && !$this->prepare() || is_null($this->stmt) )
			return false;
		
		$params = $bag->all();
		
		foreach( $params as $key => $value )
			$this->bindValue( ":" . $key, $value );
		
		return $this;
	}

	/**
	 * binds a param by value
	 */
	public function bindValue( $marker, $value ) {

		if ( !$this->prepared && !$this->prepare() || is_null($this->stmt) )
			return false;

		$this->stmt->bindValue( $marker, $value );

		return $this;
	}

	/**
	 * binds a param by reference
	 */
	public function bindParam( $marker, $value ) {

		if ( !$this->prepared && !$this->prepare() || is_null($this->stmt) )
			return false;
		
		$this->stmt->bindParam( $marker, $value );

		return $this;
	}

	/**
	 * executes the current statement
	 */
	public function execute( $line = null ) {

		if ( !$this->prepared && !$this->prepare() )
			return false;
		
		if ( !$this->stmt->execute() )
			die( var_dump((string) $line . implode( " | ", $this->stmt->errorInfo())) );
		
		return $this;
	}

	/**
	 * fetches the result
	 */
	public function fetch( string $index = "uid", array $options = array(), $method = \PDO::FETCH_ASSOC ) {

		$options = array_merge( $this->config["fetch"], $options );

		if ( is_null($this->stmt) )
			return false;
		
		$results = array();
		$primIndex = is_null($options["key"]) ? $index  : $options["key"];

		while( $row = $this->stmt->fetch( $method ) ) {

			$result = ( $options["wrapInBag"] ) ? new Classes\ParameterBag( (array) $row ) : $row;;
			$results[ $row[$primIndex] ] = $result;
		}

		$this->reset();

		// return single when requested
		if ( count($results) == 1 && $options["single"] )
			return reset( $results );
		
		return $results;
	}

	/**
	 * same as ::fetch but with options as params
	 * convenient shorthand for ::fetch( "header", \PDO::METHOD, array(YOUR_OPTIONS) )
	 */
	public function fetchOptions( $options ) {

		$base = array_merge( $this->config["fetch"], $options );
		
		return $this->fetch( $base["index"], $base["method"], $base );
	}

	/**
	 * returns the lastInsertId()
	 */
	public function lastInsertId() {

		return $this->conn->lastInsertId();
	}

	/**
	 * resets the query
	 */
	public function reset() {

		$this->query = array();
		$this->stmt = null;
		$this->prepared = false;

		return $this;
	}

	/**
	 * returns the actual database connection
	 */
	public function &getConn() {

		return $this->conn;
	}

	/**
	 * returns a table description
	 */
	public function getDescription( string $table, array $options = [] ) {
		
		$this-query( "describe $table" );

		$this->execute();

		return $this->fetch( "uid", $options );
	}
}

//_____________________________________________________________________________________________
//