<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * manages the user of the system
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\User;

//_____________________________________________________________________________________________
class UserManager {

	/**
	 * current user
	 */
	private $user;
	
	/**
	 * construction
	 */
	public function __construct() {

		echo 2;

		exit;

		$this->init();
	}

	/**
	 * initializes the user manager
	 * 
	 * @return boolean
	 */
	public function Init() {

		// check session for logged in user and autologin him/her in
		$session = &\Conference::request()->session;
		
		if ( isset($session["CONREN"]["user"]) )
			$this->instaLogin( $session["CONREN"]["user"] );
		
		// define anonymous user
		else
			$this->user = new namespace\User();
		
		return true;
	}

	/**
	 * logs a user in without password check
	 */
	private function instaLogin( $id ) {

		$user = $this->getUser();
	}

	/**
	 * returns the current user
	 * 
	 * @param int $id - the user id
	 * 
	 * @return \Conference\Core\User\User|null
	 */
	public function getUser( $id = null ) {

		return $this->user;
	}
}

//_____________________________________________________________________________________________
//