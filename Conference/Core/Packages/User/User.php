<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * user model
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Packages\User;

use \Conference\Core\Classes;

//_____________________________________________________________________________________________
class User extends Classes\ParameterBag {

	/**
	 * construction
	 * 
	 * @param array $params - user parameter
	 * @param boolean $anonymous - guest or logged in user
	 */
	public function __construct( array $params = array(), bool $anonymous = true ) {
		
		parent::__construct();

		// rMerge to write $params at first line and not at the end - because i can :)
		$this->rMerge( null, $params, array(
			"uid" => null,
			"first_name" => "",
			"last_name" => "",
			"password" => "",
			"email" => "",
			"remember_token" => "",
			"super_user" => false,

			"anonymous" => $anonymous,
			"roles" => null
		));

		// $this->Manager( )
	}

	/**
	 * returns users roles
	 */
	public function roles() {

		return \Conference::service( "rolesManager" )->getUsersRoles( $this->get("uid") );
	}
}

//_____________________________________________________________________________________________
//