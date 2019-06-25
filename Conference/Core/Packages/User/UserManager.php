<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * user management
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Packages\User;

//_____________________________________________________________________________________________
class UserManager {



	/**
	 * construction
	 */
	public function __construct() {

		$this->currentUser = new namespace\User();
		
		$this->Init();
	}

	/**
	 * initialization
	 */
	public function Init() {

		// check remembered cookie
		$this->loginRememberedUser();
		
		return $this;
	}

	/**
	 * returns the current user object
	 */
	public function getCurrentUser() {

		return $this->currentUser;
	}

	/**
	 * returns a user
	 * 
	 * @param mixed $value - the matched value for the filter
	 * @param string $filter - uid/email/first_name (?) any what you like to filter
	 * 
	 * @return \Conference\Core\Package\User\User
	 */
	public function getUser( $value, string $filter = "uid" ) {
		
		$query = array();
		$params = array();

		$query[] = "SELECT * FROM `user` WHERE :filter=:value";

		$stmt = \Conference::db()->prepare( implode(" ", $query) . ";" );

		$stmt->bindValue( ":filter", $filter );
		$stmt->bindValue( ":value", $value );
		
		if ( $stmt->execute() )
			die( print_r($stmt->errorInfo()[2]) );
			
		return $stmt->fetchObject();
	}

	/**
	 * returns a list of all users
	 */
	public function getUserList() {

		$query = array();

		$query[] = "SELECT u.uid, u.first_name, u.last_name, u.password, u.email, c.label as company, u.remember_token, u.super_user";
		$query[] = "FROM `user` AS u";
		$query[] = "LEFT JOIN `company` AS c on u.company_id = c.uid";

		$stmt = \Conference::db()->prepare( implode(" ", $query) . ";" );

		$db = \Conference::service( "db" );

		$db::execute( $stmt );
		
		return $db::fetchObject( $stmt );
	}

	/**
	 * logs a user in
	 * 
	 * @param int $id - the user id
	 * 
	 * @return boolean - true on success else false
	 */
	public function loginUser( int $id ) {

		if ( !$id || $id < 0 )
			return false;
		
		$token = password_hash( (string) rand(), PASSWORD_DEFAULT );

		// update database and set cookie
		$query = array();
		$query[] = "UPDATE `user` SET remember_token=:remember_token WHERE uid=:uid";

		$stmt = \Conference::db()->prepare( implode(" ", $query) . ";" );

		$stmt->bindValue( "remember_token", $token );
		$stmt->bindValue( "uid", $id );

		if ( !$stmt->execute() )
			die( print_r($stmt->errorInfo()[2]) );
		
		setcookie( "CONREN_REMEMBERME", $token, time() + 3600 );
		
		return true;
	}
	
	/**
	 * tries to login the user from the cookie token
	 */
	public function loginRememberedUser() {

		if ( !isset($_COOKIE["CONREN_REMEMBERME"]) )
			return false;
		
		$query = array();

		$query[] = "SELECT * FROM `user` WHERE remember_token=:remember_token";
		
		$stmt = \Conference::db()->prepare( implode(" ", $query) . ";" );

		$stmt->bindValue( "remember_token", $_COOKIE["CONREN_REMEMBERME"] );

		if ( !$stmt->execute() )
			die( print_r($stmt->errorInfo()[2]) );

		$user = $stmt->fetch(\PDO::FETCH_ASSOC);

		if ( empty($user) ) return false;
		
		$this->currentUser = new namespace\User( $user, false );

		setcookie( "CONREN_REMEMBERME", $this->currentUser->get("remember_token"), time() + 3600 );
		
		return true;
	}

	/**
	 * logs the current user out
	 * 
	 * @return boolean - true on success else false
	 */
	public function logoutUser() {

		var_dump( $this->currentUser->get("anonymous") );

		if ( $this->currentUser->get("anonymous") )
			return true;

		// update database
		$query = array();

		$query[] = "UPDATE `user` SET remember_token=NULL WHERE uid=:uid";

		$stmt = \Conference::db()->prepare( implode(" ", $query) );
		$stmt->bindValue( ":uid", $this->currentUser->get("uid") );

		if ( !$stmt->execute() )
			die( print_r($stmt->errorInfo()[2]) );
		
		// reset
		$this->currentUser = new namespace\User();
		
		return $this;
	}
	
	/**
	 * registers a new user
	 * 
	 * @param array $params - parameter values
	 * 	first_name => string
	 * 	last_name => string
	 * 	password => string (will be salt hashed before inserted so no hash beforehand necessary)
	 * 	email => string
	 * 	super_user => super user status
	 * @param array $roles - roles this user has
	 */
	public function registerUser( array $params, array $roles ) {

		if ( !$this->verifyUserRegistration($params, $roles) )
			return false;
		
		$query = array();

		$query[] = "INSERT INTO `user` VALUES";
		$paramsQueryString = array();

		foreach( $params as $name => $value )
			$paramsQueryString[] = $name . "=" . $value;
		
		$query[] = implode( ",", $paramsQueryString );

		$stmt = \Conference::db()->prepare( implode(" ", $query) );
		
		var_dump( "END REGISTRATION FUNCTIONALITY !" );

		exit;
	}

	/**
	 * verifies user registration
	 * 
	 * @param array $params - base parameter
	 * @param array $roles - roles of the user
	 */
	public function verifyUserRegistration( array $params, array $roles = array() ) {
		
		if ( empty($params) )
			return false;
		
		return true;
	}
}

//_____________________________________________________________________________________________