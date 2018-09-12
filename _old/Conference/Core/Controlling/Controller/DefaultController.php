<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	default controller when nothing is called

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Controlling\Controller;

use Conference\Core\Controlling\Response;

//_____________________________________________________________________________________________
class DefaultController extends namespace\ControllerBase {

	//_________________________________________________________________________________________
	public function __construct() {}

	//_________________________________________________________________________________________
	public function index( array $markup = array(), array $options = array() ): Response\Response {

		return new Response\Response( $markup, $options );
	}
}

//_____________________________________________________________________________________________
//