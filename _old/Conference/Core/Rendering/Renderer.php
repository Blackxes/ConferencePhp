<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	// Todo: edit description

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\Rendering;

use Conference\Core\Controlling\Response;

//_____________________________________________________________________________________________
class Renderer {

	protected $markup;
	protected $template;
	protected $options;

	//_________________________________________________________________________________________
	public function __construct() {
		
		$this->template = "";
		$this->markup = array();
		$this->options = array();
	}

	//_________________________________________________________________________________________
	// render the given response
	//
	// param1 (\Conference\Core\Controlling\Response\Response) expects the returned response
	//
	// return boolean
	//		true - rendering succeeded
	//		false - when the parser logged errors
	//
	public function render( Response\Response $r ) {

		// post processing
		$processed = $this->postProcessing( $r->markup, $r->options );
		
		$parser = \Conference::service( "templax" );
		$content = $parser->parse( $r->template, $processed["markup"], $processed["options"] );
		
		echo $content;

		return true;
	}

	//_________________________________________________________________________________________
	// post markup creation after the controller has been parsed
	public function postProcessing( array $markup, array $options ): array {

		$body = &$markup["page-body"];
		// $content = &$markup["page-body"]["content-items"];
		
		$body["logs"] = \Conference::service( "logfile" )->buildLogs( "open" );

		return [ "markup" => $markup, "options" => $options ];
	}

	//_________________________________________________________________________________________
	// prepares the base values for the controller
	public function getPrepared(): array {

		$markup = $GLOBALS["Conference"]["Rendering"]["baseMarkup"];
		$options = $GLOBALS["Conference"]["Rendering"]["baseOptions"];

		$markup["page-body"]["main-menu"] = \Conference::service( "menuBuilder" )->build( "main_menu" );

		// simplify main container
		$body = &$markup["page-body"]["content-items"];

		// return the bases
		return [ "markup" => $markup, "options" => $options ];
	}
}

//_____________________________________________________________________________________________
//