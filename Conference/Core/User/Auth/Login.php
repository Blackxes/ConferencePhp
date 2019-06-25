<?php
/**********************************************************************************************
 * 
 * @File creates a login authentication
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

namespace Conference\Core\User\Auth;

use \Conference\Core\State;

class Login {

	/**
	 * login email
	 * 
	 * @var string
	 */
	private $email;

	/**
	 * login password
	 * 
	 * @var string
	 */
	private $pw;

	/**
	 * state of this logins verification
	 * 
	 * @var boolean
	 */
	private $verified;

	/**
	 * state of this logins validation
	 * 
	 * @var boolean
	 */
	private $valid;

	/**
	 * contains errors when the verifcation failed
	 * 
	 * @var string
	 */
	private $error;

	/**
	 * construction
	 */
	public function __construct( string $email, string $pw ) {

		$this->email = $email;
		$this->pw = $pw;
		$this->verified = false;

		$state = $this->verify();

		$this->valid = $state->get("payload");
		$this->error = $this->valid ? null : $state->get("message");
	}

	/**
	 * verifies the login credentials
	 */
	public function verify() {

		# the verification only happens once
		# so set verified to true right away to assure
		$this->verified = true;

		# check email and pw
		if ( !$this->email || !$this->pw )
			return new State\Response( "Die Email oder das Passwort sind invalide.", "error", false );
	
		# verify email with pw
		$manager = \Conference::service( "user.manager" );
		$user = $manager->get( $this->email, "email", ["single" => true] );

		if ( !$user ) return new State\Response( "Es wurde kein Benutzer mit der Email '$this->email' gefunden.", "error", false );
		
		if ( !password_verify($this->pw, $user->get("password")) )
			return new State\Response( "Das Passwort ist inkorrekt.", "error", false );
		
		return new State\Response( null, "ok", true );
	}

	/**
	 * returns the verified state
	 * 
	 * @return boolean - the state
	 */
	public function verified() {
		return $this->verified;
	}

	/**
	 * returns the valid state
	 */
	public function valid() {
		return $this->valid;
	}

	/**
	 * returns the errors
	 */
	public function getError() {
		return $this->error;
	}
}