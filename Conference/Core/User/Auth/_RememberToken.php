<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * middleware to perform user logins
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\User\Verification;

//_____________________________________________________________________________________________
class RememberToken {

	/**
	 * remember token
	 */
	private $token;

	/**
	 * construction
	 */
	public function __construct() {

		$this->token = null;
	}

	/**
	 * generates a new remember token
	 * 
	 * HeadComment:
	 */
	static public function generate() {

		return \password_hash( (string) rand(), PASSWORD_BCRYPT );
	}

	/**
	 * returns the token stored in the cookies
	 * 
	 * HeadComment:
	 */
	public function get() {

		return $this->token;
	}

	/**
	 * initialization
	 * 
	 * HeadComment:
	 */
	public function init() {

		$this->token = $_COOKIE["token"];

		return $this;
	}


	/**
	 * returns true/false wether a token exists or not
	 * 
	 * HeadComment:
	 */
	public function isset() {

		return is_null( $this->token );
	}

	/**
	 * registers a new remember token
	 * 
	 * HeadComment:
	 */
	public function registerUserToken( int $uid ) {
		
		$this->updateUserToken( $uid, $this->generate() );
		
		return $this->token;
	}

	/**
	 * initializes the remember token cookie
	 * 
	 * HeadComment:
	 */
	public function initCookie( $token = null ) {

		$_token = (is_null($token)) ? $this->token : $token;
		
		return setcookie( "token", (is_null($token)) ? $this->token : $token, time() + 3600, "/" );
	}

	/**
	 * resets the token timer to start from the beginning again
	 * 
	 * HeadComment:
	 */
	public function resetCookie( string $token ) {

		return $this->initCookie( $token );
	}

	/**
	 * updates the remember token on a user
	 * 
	 * HeadComment:
	 */
	public function updateUserToken( int $uid, string $token ) {

		$params = [
			"remember_token" => $token
		];
		$manager = \Conference::service( "user.manager" );

		if ( $manager->update($uid, $params) )
			return false;
		
		$this->token = $manager->get( $uid, "uid", ["single" => true] )->get( "remember_token" );

		return true;
	}

	/**
	 * returns the token from the current user
	 * 
	 * HeadComment:
	 */
	public function userToken() {

		$user = \Conference::currentUser();

		return $user->get( "remember_token" );
	}
}

//_____________________________________________________________________________________________