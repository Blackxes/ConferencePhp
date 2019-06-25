<?php
/**********************************************************************************************
 * 
 * @File create a token authentication
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\User\Auth;

use \Conference\Core\State;

class Token {

	/**
	 * token
	 * 
	 * @var string
	 */
	private $token;

	/**
	 * state of this tokens verification
	 * 
	 * @var boolean
	 */
	private $verified;

	/**
	 * state of this tokens validation
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
	 * 
	 * @param string $token
	 */
	public function __construct( string $token ) {

		$this->token = $token;
		$this->verified = false;

		$state = $this->verify();

		$this->valid = $state->get("payload");
		$this->error = $this->valid ? null : $state->get("message");
	}

	/**
	 * verifies this token
	 */
	public function verify() {

		if ( !$this->token )
			return new State\Response( "Invalider Token", "error", false );
		
		$manager = \Conference::service( "user.manager" );
		$user = $manager->get( $this->token, "remember_token" );

		if ( !$user )
			return new State\Response( "Es wurde kein Benutzer mit dem hinterlegtem Token gefunden.", "error", false );
		
		return new State\Response( null, "ok", true );
	}

	/**
	 * returns the verification state
	 */
	public function verified() {
		$this->verified;
	}

	/**
	 * returns the validation state
	 */
	public function valid() {
		$this->valid;
	}

	/**
	 * returns the errors
	 */
	public function getError() {
		return $this->error;
	}
}