<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	render response with http status code 403

	access denied - nothing to see here

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Controlling\Response;

//_____________________________________________________________________________________________
class Http403Response extends namespace\Response {

	//_________________________________________________________________________________________
	public function __construct() {

		parent::__construct();

		$this->code = 403;
		
		$this->markup["page-head"]["title"] = "Access denied";
		$this->markup["page-body"]["page-title"] = "Access denied";
	}
}

//_____________________________________________________________________________________________
//