<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * HeadComment:
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Rendering;

use \Conference\Core\Classes;

//_____________________________________________________________________________________________
class RenderObject extends Classes\ParameterBag {
	
	/**
	 * construction
	 */
	public function __construct( string $template = null, array $markup = null, array $hooks = null ) {

		parent::__construct([
			"template" => $template,
			"markup" => $markup,
			"hooks" => $hooks,
			"content" => null
		]);
	}

	/**
	 * prepares the object to be used to render it
	 * parsed content is stored in content
	 * 
	 * @return $this
	 */
	public function prepare() {
		
		list( $template, $markup, $hooks ) = $this->get( ["template", "markup", "hooks"], true, false );

		# set content
		$this->set( "content", \Conference::service( "templax" )->parse( $template, $markup, $hooks ) );

		return $this;
	}
}

//_____________________________________________________________________________________________
//