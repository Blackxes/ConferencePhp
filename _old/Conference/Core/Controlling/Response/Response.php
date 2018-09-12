<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	render response

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Controlling\Response;

//_____________________________________________________________________________________________
class Response extends \Conference\Core\Rendering\Renderable {

	//_________________________________________________________________________________________
	public function __construct(
		array $markup = array(), array $options = array(), string $template = "", int $type = 200 )
	{
		$config = $GLOBALS["Conference"]["Rendering"];
		
		$this->type = (string) $type;
		$this->template = ($template) ? $template : $config["baseTemplate"];
		$this->markup = array_merge( $markup );
		$this->options = array_merge( $options );
	}
}

//_____________________________________________________________________________________________
//