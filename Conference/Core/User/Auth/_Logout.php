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

//_____________________________________________________________________________________________
class Logout {

	/**
	 * construction
	 */
	public function __construct() {}

	/**
	 * loggs a user out
	 * 
	 * HeadComment:
	 */
	static public function logout( int $_uid = null ) {

		$manager = \Conference::service( "user.manager" );
		$uid = (int) ( is_null($_uid) ? \Conference::currentUser()->get("uid") : $manager->get($_uid) );
		
		# update database
		$db = \Conference::db();

		$db->query( "update `c7_user` as u set remember_token=null" );
		$db->query( "where u.uid=:uid" );

		$db->bindValue( ":uid", $uid );

		$db->execute();
		
		# unset remember token
		setcookie( "token", null, time() - 1, "/" );

		return $db->fetch();
	}
}

//_____________________________________________________________________________________________