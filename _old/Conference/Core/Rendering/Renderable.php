<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	abstract class that provides derived classes the needed member to be treated
	as a render response

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Rendering;

//_____________________________________________________________________________________________
abstract class Renderable {

	public $code;
	public $type;
	public $template;
	public $markup;

	//_________________________________________________________________________________________
	public function __construct( $template, $markup, $type ) {
		
		$this->code = "200";
		$this->template = $template;
		$this->markup = $markup;
		$this->type = $type;
	}
}

//_____________________________________________________________________________________________
//