<?php

/**********************************************************************************************
 * 
 * @File user management
 * 
 * @Author: Alexander Bassov
 * @Email: alexander.bassov@trentmann.com
 * 
/*********************************************************************************************/

namespace Conference\Core\User;

use \Conference\Core\Classes;
use \Conference\Core\DataManagement;
use \Conference\Core\User\Verification;

//_____________________________________________________________________________________________
class UserManager extends DataManagement\ManagerBase {

	/**
	 * current user
	 */
	private $user = null;

	/**
	 * construction
	 */
	public function __construct() {

		parent::__construct( "c7_user" );
		
		$this->user = null;
	}

	/**
	 * returns all users
	 */
	public function all( string $index = "uid", array $options = [] ) {

		$db = \Conference::db();

		$db->query( "select c.name, u.* from `c7_user` as u" );
		$db->query( "left join `companies` as c on u.company_id = c.uid" );

		$db->execute();

		// return $this->cache( "all", $index, $db->fetch($index, $options) );
		return $db->fetch( $index, $options );
	}

	/**
	 * returns a user
	 */
	public function get( $value, string $index = "uid", array $options = [] ) {

		if ( $this->hasCachedData("get", "{$index}_{$value}") )
			return $this->getCachedData( "get", "{$index}_{$value}" );

		$db = \Conference::db();

		$db->query( "select c.name as company, u.* from `c7_user` as u" );
		$db->query( "left join `companies` as c on u.company_id = c.uid" );
		$db->query( "where u.${index} = :value" );

		$db->bindValue( ":value", $value );

		$db->execute();

		return $this->cache( "get", "{$index}_{$value}", $db->fetch($index, $options) );
	}

	/**
	 * returns the existance of a user
	 */
	public function has( $value, string $index = "uid", array $options = [] ) {

		$db = \Conference::db();

		$db->query( "select u.* from `c7_user` as u" );
		$db->query( "where u.${index} = :value limit 1" );

		$db->bindValue( ":value", $value );

		$db->execute();

		return $db->fetch( $index, $options );
	}

	/**
	 * deletes users from the database
	 */
	public function delete( $value, string $index = "uid" ) {

		# dont delete anything when the value is empty
		if ( !$value )
			return null;

		$db = \Conference::db();

		$db->query( "delete from `c7_user` where ${index}=:value" );
		
		$db->bindValue( ":value", $value );

		$db->execute();

		return $db->fetch();
	}


	public function update( int $uid, array $params ) {

		$db = \Conference::db();

		$db->query( "update `c7_user` as u set" );

		foreach( $params as $key => $value )
			$db->query( $key . "= :" . $key );
		
		$db->query( "where u.uid=:uid" );
		
		foreach( $params as $key => $value )
			$db->bindValue( ":" . $key, $value );
		
		$db->bindValue( ":uid", $uid );
		
		$db->execute();

		return $db->fetch();
	}
	
	/**
	 * registers a new user
	 * 
	 * HeadComment:
	 */
	public function register( array $params, array $roles = [] ) {

		if ( !$params )
			return false;
		
		$db = \Conference::db();

		$db->query( "insert into `c7_user`" );

		$keys = array_keys( $params );
		$fields = "(" . implode( ",", $keys ) . ")";
		$placeholder = "(:" . implode( ", :", $keys ) . ")";

		$db->query( $fields . " values " . $placeholder );

		foreach( $params as $k => $v ) {

			# exception is the password
			if ( $k != "password" )
				$db->bindValue( ":" . $k, $v );
			
			$db->bindValue( ":" . $k, password_hash($v, PASSWORD_DEFAULT) );
		}

		$db->execute();

		return $db->fetch();
	}

	/**
	 * initialization
	 */
	public function init() {

		# authenticate user and get instance
		$auth = new namespace\Auth\Authenticate();
		$auth->init();

		$this->user = $auth->getUser();

		return $this;
	}
}

//_____________________________________________________________________________________________