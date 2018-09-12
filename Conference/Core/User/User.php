<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * user model
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\User;

//_____________________________________________________________________________________________
class User {

	/**
	 * uid of the user
	 * 
	 * @var int|null
	 */
	public $uid;

	/**
	 * creation date of the user
	 * 
	 * @var int|null
	 */
	public $crdate;

	/**
	 * last time this user has been updated
	 * 
	 * @var int|null
	 */
	public $updated;

	/**
	 * describes if this user is active
	 * active user are visible in the system but not able to log in or interact
	 * 
	 * @var boolean|null
	 */
	public $active;

	/**
	 * describes if this user is hidden
	 * hidden user are invisible to the system and not accessable
	 * 
	 * @var boolean|null
	 */
	public $hidden;

	/**
	 * the salt hashed password
	 * 
	 * @var string|null
	 */
	public $password;

	/**
	 * firstname
	 * 
	 * @var string|null
	 */
	public $firstname;

	/**
	 * lastname
	 * 
	 * @var string|null
	 */
	public $lastname;

	/**
	 * email address
	 * 
	 * @var string|null
	 */
	public $email;

	/**
	 * describes wether the current user is a super user
	 * 
	 * @var boolean|null
	 */
	public $isSuper;

	/**
	 * describes wether this user is anonymous / a guest
	 * 
	 * @var boolean
	 */
	public $anonymous;

	/**
	 * construction
	 */
	public function __construct() {

		$this->anonymous = true;
	}

	/**
	 * defines this object by the given object and returns $this
	 * properties have to be public
	 * 
	 * @return $this
	 */
	public function defineByObject( $obj ) {

		foreach( $obj as $key => $value )
			if ( property_exists($this, $key) )
				$this->$key = $value;
			
		// anonymity
		$this->anonymous = is_null($uid);

		return $this;
	}
}

//_____________________________________________________________________________________________
//