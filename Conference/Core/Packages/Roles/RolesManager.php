<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * user management
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Packages\Roles;

use \Conference\Core\Classes;

//_____________________________________________________________________________________________
class RolesManager {

	/**
	 * roles
	 * 
	 * @var array
	 */
	private $roles;

	/**
	 * construction
	 */
	public function __construct() {

		$this->roles = array();
	}

	/**
	 * returns all existing roles
	 * 
	 * @return array - the roles
	 */
	public function getRoles() {

		if ( !empty($this->roles) )
			return $this->roles;
		
		$query = array();

		$query[] = "SELECT * FROM `roles`";

		if ( $stmt->execute() )
			die( print_r($stmt->errorInfo()) );

		while( $role = $stmt->fetchObject() )
			$this->roles[$role->uid] = $role;
		
		return $this->roles;
	}

	/**
	 * returns roles of an user
	 * 
	 * @param int $uid - user id
	 * 
	 * @return array
	 */
	public function getUsersRoles( ?int $uid ) {
		
		if ( is_null($uid) )
			return array();

		$query = array();

		$query[] = "SELECT r.* FROM `roles` as r";	
		$query[] = "LEFT JOIN `user_roles` as ur";
		$query[] = "ON ur.user_id = :user_id";
		$query[] = "WHERE ur.role_id = r.uid";
		
		$stmt = \Conference::db()->prepare( implode(" ", $query) );

		$stmt->bindValue( ":user_id", $uid );

		if ( !$stmt->execute() )
			die( print_r($stmt->errorInfo()) );
		
		$roles = array();

		while( $role = $stmt->fetchObject() )
			$roles[$role->uid] = $role;

		return $roles;
	}
}

//_____________________________________________________________________________________________
//