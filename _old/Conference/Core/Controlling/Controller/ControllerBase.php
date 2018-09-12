<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	base class for controller

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Controlling\Controller;

use Conference\Core\Controlling\Response;

//_____________________________________________________________________________________________
abstract class ControllerBase implements namespace\ControllerInterface {
	
	//_________________________________________________________________________________________
	public function constructor() {}

	//_________________________________________________________________________________________
	// base entrance of the controller when no entrance is defined
	// 
	// param1 (&array) expects the base markup
	//
	// return \Conference\Core\Controlling\Response\Response
	//		the response object
	//
	public function index( array $markup, array $options ): Response\Response {
		
		return new Response\Response( $markup, $options );
	}
}

//_____________________________________________________________________________________________
//