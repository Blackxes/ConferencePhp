<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	render response with http status code 404

	*insert gone pinguins*

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Controlling\Response;

//_____________________________________________________________________________________________
class Http404Response extends namespace\Response {

	//_________________________________________________________________________________________
	public function __construct() {

		parent::__construct();

		$this->code = 404;

		$this->markup["page-head"]["title"] = "Page not found";
		$this->markup["page-body"]["page-title"] = "Page not found";
	}
}

//_____________________________________________________________________________________________
//