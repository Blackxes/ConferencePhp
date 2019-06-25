<?php 
/**********************************************************************************************
 * 
 * @File user ressources
 * 
 * @Author: Alexander Bassov
 * @Email: alexander.bassov@trentmann.com
 * 
/*********************************************************************************************/

namespace Conference\Core\User;

class UserRepository {

	/**
	 * construction
	 */
	public function __construct() {
	}

	/**
	 * returns all addresses of the user
	 */
	public function addresses() {

		$db = \Conference::db();

		# fields
		$fields = [
			"u.uid as user_id", "u.first_name", "u.last_name", "u.email",
			"a.uid", "a.recipient", "a.street", "a.street_number", "a.zip", "a.city", "a.state", "a.country",
			"at.name as address_type", "at.key as address_type_key"
		];

		$db->query( "select u.uid as user_id, u.first_name, u.last_name, u.email," );
		$db->query( implode(", ", $fields) );
		$db->query( "from `addresses` as a" );
		
		$db->query( "left join `address_types` as at on at.uid=a.address_type_id" );
		$db->query( "left join `c7_user` as u on u.uid=a.user_id" );
		
		$db->query( "where a.user_id=:uid" );

		$db->bindValue( ":uid", $uid );

		$db->execute();
		
		return $db->fetch( $index, $options );
	}

	/**
	 * returns user roles
	 */
	public function roles( int $uid, string $index = "uid", array $options = [] ) {

		$db = \Conference::db();

		$db->query( "select r.* from `c7_roles` as r" );
		$db->query( "left join `c7_user_roles` as ur on ur.user_id=:uid" );
		$db->query( "where r.uid=ur.role_id" );

		$db->bindValue( ":uid", $uid );

		$db->execute();

		return $db->fetch( $options );
	}
}