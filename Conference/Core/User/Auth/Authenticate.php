<?php
/**********************************************************************************************
 * 
 * @File takes care of the user authentification
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\User\Auth;

use \Conference\Core\User;
use \Conference\Core\State;

class Authenticate {

	/**
	 * user instance
	 * 
	 * @var \Conference\Core\User\User
	 */
	private $user;

	/**
	 * state of initialization
	 * 
	 * @var boolean
	 */
	private $initialized;

	/**
	 * construction
	 */
	public function __construct() {
		
		$this->user = new User\User();
		$this->initialized = false;
	}

	/**
	 * initializes the authentification and builds up the user instance
	 */
	public function init() {

		$this->user = $this->authenticate();
		$this->initialized = true;

		return $this->user;
	}

	/**
	 * processes authentication methods to build an user
	 */
	private function authenticate() {

		$methods = [ "authByToken", "authByLogin" ];

		foreach( $methods as $i => $method ) {

			$resp = \call_user_func_array([ $this, $method ], [] );

			# when the payload is null its not this methods context
			# eg. when no token is defined the "authByToken" method cant do anything
			# therefore its not there context but there might be a login attempt
			if ( $resp->get("payload") == null )
				continue;
			
			$this->user->replace( $resp->get("payload")->all() );

			break;
		}
		
		# when no method succeeded - the user is anonymous
		return $this->user;
	}

	/**
	 * authenticates the user by a token
	 */
	public function authByToken() {

		$token = $_COOKIE["token"];

		if ( !$token )
			return new State\Response( "No token found. Wrong context", "ok", null );
		
		$user = \Conference::service( "user.manager" )->get( $token, "remember_token" );

		# when no user is found unset the cookie
		if ( !$user ) {
			unset( $_COOKIE[$token] );
			return new State\Response( "Found token could'nt be assoiciated to any user. Token has been unset.", "ok", null );
		}

		# update token
		$this->initToken( $user );
		
		return new State\Response( null, "ok", new User\User($user->all()) );
	}
	
	/**
	 * authenticates the user by a login
	 */
	public function authByLogin() {

		$post = \Conference::request()->get("post");

		// if ( !$post->get("user_login") )
			// return new State\Response( "No login attempt. Wrong context", "ok", null );
		
		list( $email, $pw ) = $post->get( ["email", "password"], true );
		$login = new namespace\Login( (string) $email, (string) $pw );

		if ( !$login->valid() )
			return new State\Response( $login->getError(), "error", false );
		
		# create token and store it
		$this->initToken( \Conference::service("user.manager")->get($email, "email", ["single" => true]) );

		return new State\Response( null, "ok", true );
	}

	/**
	 * initializes the remember token for the given user
	 */
	public function initToken( User\User $user ) {

		$token = \password_hash( (string) rand(), PASSWORD_BCRYPT );

		$params = [ "remember_token" => $token ];
		$manager = \Conference::service( "user.manager" );

		if ( $manager->update($uid, $params) )
			return false;

		setcookie( "token", $token, time() + 3600, "/" );

		return true;
	}

	/**
	 * returns the authenticated user
	 * Note: when refering to a user in this system
	 * it doesnt mean its a logged in user and therefore can be anonymous!
	 */
	public function getUser() {

		if ( !$this->initialized )
			throw new \Exception( "Initialize the Authenticate instance first. Then try to pull the user instance again.");
		
		return $this->user;
	}
}