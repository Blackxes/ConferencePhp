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

use \Conference\Core\Classes;

//_____________________________________________________________________________________________
class User extends Classes\ParameterBag {

	/**
	 * construction
	 * 
	 * @param array $params - user parameter
	 * @param boolean $anonymous - guest or logged in user
	 */
	public function __construct( array $params = array(), bool $anonymous = null ) {
		
		parent::__construct();

		// rMerge to write $params at first line and not at the end - because i can :)
		$this->rMerge( null, $params, [
			"uid" => null,
			"first_name" => "",
			"last_name" => "",
			"password" => "",
			"email" => "",
			"remember_token" => "",
			"super_user" => false,
			"anonymous" => true,
			"roles" => null
		]);

		# try to define anonymous when not defined
		if ( is_null($anonymous) && !$this->isNull("uid") )
			$this->set( "anonymous", false );
	}

	/**
	 * returns users roles
	 */
	public function roles() {

		return \Conference::service( "roles.manager" )->userRoles( (int) $this->get("uid") );
	}
}

//_____________________________________________________________________________________________
//