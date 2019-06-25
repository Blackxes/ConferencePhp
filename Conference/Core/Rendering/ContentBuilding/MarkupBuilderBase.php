<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * Todo: edit
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Rendering\ContentBuilding;

//_____________________________________________________________________________________________
abstract class MarkupBuilderBase {

	/**
	 * construction
	 * 
	 * @param array $templates - template configuration for \Templax\Templax::registerFull()
	 */
	public function __construct( array $templates = [] ) {

		if ( !empty($templates) )
			\Conference::service( "templax" )->registerFull( $templates );
	}

	/**
	 * prepares the markup in this context
	 */
	abstract public function prepare( array &$markup );
}

//_____________________________________________________________________________________________
//