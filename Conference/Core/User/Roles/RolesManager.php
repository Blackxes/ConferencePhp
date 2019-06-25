<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * all system configuration is in here
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\User\Roles;

use \Conference\Core\DataManagement;

//_____________________________________________________________________________________________
class RolesManager extends DataManagement\DataCache {

	/**
	 * construction
	 */
	public function __construct() {

		parent::__construct();
	}

	/**
	 * returns all roles
	 */
	public function all( string $index = "uid", array $options = [] ) {

		$db = \Conference::db();

		$db->query( "select r.* from `c7_roles` as r" );

		$db->execute();

		return $db->fetch( $index, $options );
	}

	/**
	 * returns a role
	 */
	public function get( $value, $index = "uid", array $options = [] ) {

		$db = \Conference::db();

		$db->query( "select r.* from `c7_roles` where r.${index}=:value" );
		
		$db->bindValue( ":value", $value );

		$db->execute();

		$db->fetch( $index, $options );
	}

	/**
	 * returns the existance of a role
	 */
	public function has( $value, $index = "uid", array $options = [] ) {

		return (bool) $this->has( $value, $index, $options );
	}

	/**
	 * deletes roles
	 * wont delete any when the value is empty
	 */
	public function delete( $value, $index = "uid", array $options = [] ) {

		if ( !$value )
			return false;
		
		$db = \Conference::db();

		$db->query( "delete from `c7_roles` where ${index}=:value" );

		$db->bindValue( ":value", $value );

		$db->execute();

		return $db->fetch();
	}

	/**
	 * updates a role
	 */
	public function update( int $uid, array $params ) {

		$db = \Conference::db();

		$db->query( "update `c7_roles` as r set" );

		foreach( $params as $key => $value )
			$db->query( $key . "= :" . $key );
		
		$db->query( "where u.uid=:uid" );
		
		foreach( $params as $key => $value )
			$db->bindValue( ":" . $key, $value );
		
		$db->bindValue( ":uid", $uid );

		$db->execute();

		return $db->fetch();
	}
}

//_____________________________________________________________________________________________
//