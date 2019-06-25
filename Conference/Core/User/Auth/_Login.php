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

use \Conference\Core\Classes;
use \Conference\Core\User;

//_____________________________________________________________________________________________
class Login {

	/**
	 * the remember token
	 */
	private $token;

	/**
	 * stores the last logged in user
	 * 
	 * @var null|\Conference\Core\User\User
	 */
	private $lastUser;

	/**
	 * construction
	 */
	public function __construct() {
		
		$this->token = ( new namespace\RememberToken() )->init();
	}

	/**
	 * logs in a user by an email
	 * 
	 * HeadComment:
	 */
	public function byEmail( string $email ) {

		$manager = \Conference::service( "user.manager" );
		$user = $manager->get( $email, "email" );
		
		if ( $user->get("anonymous") )
			$manager->logout( $user->get("uid") );

		return true;
	}

	/**
	 * logs in a user by a instance
	 * 
	 * HeadComment:
	 */
	public function byUser( namespace\User $user ) {

		# Idea: throw ContextException( "cant login anonymous user" )
		if ( $user->get("anonymous") )
			return false;

		return $this->login( $user->get("uid") );
	}

	/**
	 * returns an user instance based on the remembering and logging status
	 * 
	 * HeadComment:
	 */
	public function init() {

		# try to login by attempt or token
		if ( $this->byAttempt($_POST) || $this->byToken($this->token->get()) )
			return $this->lastUser;
		
		# else anonymous user
		return new User\User();
	}
	
	/**
	 * logs in a user by a login attempt
	 * 
	 * HeadComment:
	 */
	public function byAttempt( array $params ) {

		if ( !$params["user_login"] )
			return false;
		
		$this->lastUser = new User\User(
			\Conference::service( "user.manager" )->get( $params["email"], "email", ["single" => true, "wrapInBag" => false] )
		);

		if ( !$this->lastUser || $this->lastUser->get("anonymous") )
			return \Conference::userLog( "Email oder Passwort ist fehlerhaft.", "error" );

		# set cookie and register in user
		$uuid = $this->lastUser->get( "uid" );
		$token = $this->token->registerUserToken( $uuid );
		$this->token->initCookie( $token );

		return true;
	}

	/**
	 * returns a user instance based on the given token
	 * 
	 * HeadComment:
	 */
	public function byToken( string $_token = null ) {

		$token = ( is_null($_token) ) ? $_token : $this->token->get();
		$manager = \Conference::service( "user.manager" );
		$this->lastUser = $manager->get( $token, "remember_token", ["single" => true, "wrapIngBag" => false] );

		if ( !$this->lastUser )
			return false;

		# reset token timer
		$this->token->resetCookie( $token );
		$this->token->updateUserToken( $this->lastUser->get("uid"), $token );

		return true;
	}
	
	/**
	 * verifies password
	 * 
	 * HeadComment:
	 */
	public function verify( $value, string $password, string $index = "uid" ) {

		$user = \Conference::service( "user.manager" )->get( $value, $index, ["single" => true] );

		return ( !$user ) ? false : \password_verify( $password, $user->get("password") );
	}
}

//_____________________________________________________________________________________________