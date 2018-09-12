<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	interface for a controller

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Controlling\Controller;

use Conference\Core\Controlling\Response;

//_____________________________________________________________________________________________
interface ControllerInterface {

	//_________________________________________________________________________________________
	public function __construct();

	//_________________________________________________________________________________________
	// render the main entrance of the controller
	//
	// param1 (&array) expects the markup
	// param2 (&array) expects the options
	//
	public function index( array $markup, array $options ): Response\Response;
}

//_____________________________________________________________________________________________
//