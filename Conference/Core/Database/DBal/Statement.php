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
class Statement {

	/**
	 * parameter
	 * 
	 * @var array
	 */
	private $params;

	/**
	 * defines wether this statement is prepared
	 * when prepared it cannot be changed anymore
	 * 
	 * @var boolean
	 */
	private $prepared;

	/**
	 * the pdo statement
	 * 
	 * @var \PDOStatement
	 */
	private $stmt;

	/**
	 * the sql query
	 * 
	 * @var string
	 */
	private $sql;

	/**
	 * construction
	 */
	public function __construct( string $query = "" ) {
		
		$this->params = [];
		$this->prepared = false;
		$this->query = $query;
	}

	/**
	 * caches a value binding by value
	 * 
	 * HeadComment:
	 */
	public function bindValue( $key, $value ) {

		$this->params[ $key ] = $value;

		return $this;
	}

	/**
	 * caches a value binding by reference
	 * 
	 * HeadComment
	 */
	public function bindParam( $key, &$value ) {

		$this->params[ $key ] = $value;

		return $this;
	}


	/**
	 * executes this statement
	 */
	public function execute() {

		if ( !$this->prepared || is_null($this->stmt) )
			return false;
		
		try {
			$this->stmt->execute();
		}

		catch( \PDOException $ex ) {

			return $ex;
		}

		return true;
	}


}
//_____________________________________________________________________________________________
//
