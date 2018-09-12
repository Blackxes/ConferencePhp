<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	content building service for the user

	Author: Alexander Bassov
	Email: blackxes@gmx.de
	Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Backend\Services;

//_____________________________________________________________________________________________
class UserContentBuilderService {

	//_________________________________________________________________________________________
	public function __construct() {

		\Templax\Templax::templateManager()->registerTemplateSet(array(
			"userProfile" => array( "path" => $GLOBALS["Conference"]["Templating"]["TemplatePath"] . "/user.html", "marker" => "user_profile" )
		));
	}
	
	//_________________________________________________________________________________________
	// builds the content for the user profile
	//
	// param1 (int) expects the user id
	//
	// return string - the build profile
	//
	public function buildProfile( $uid = false ) {

		// get current of requested user
		$user = ( $uid === false )
			? \Conference::userHandler()->getUserById( \Conference::user()->getUid() )
			: \Conference::userHandler()->getUserById( $uid );
		
		// builds markup
		$markup = array(
			"username" => $user->username,
			"email" => $user->email,
			"company" => \Conference::service( "companyManager" )->getCompany( $user->company_uid )->label,
			"logout-link" => $GLOBALS["Conference"]["General"]["domain"] . "/user/logout"
		);

		$content = \Templax\Templax::parse( "userProfile", $markup );

		return $content;
	}
}

//_____________________________________________________________________________________________
//