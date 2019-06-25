<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * HeadComment:
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Database\DBal;

use \Conference\Core\Classes;

//_____________________________________________________________________________________________
class DBal {

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
	 * prepared statement / excluded value bindings
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
	 * connects to a database
	 * 
	 * 
	 */

	/**
	 * internal initialization
	 * 
	 * HeadComment:
	 */
	private function Init( $config ) {

		$this->conn = new \PDO( "mysql:host={$config["host"]}; dbname={$config["db"]}; charset=utf8;", $config["user"], $config["password"] );

		return true;
	}

	/**
	 * executes a simple given query
	 * 
	 * HeadComment:
	 */
	public function insert( $table, $values, $where ) {

		
	}
}

//_____________________________________________________________________________________________
//