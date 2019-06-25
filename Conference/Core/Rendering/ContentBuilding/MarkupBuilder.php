<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * HeadComment:
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Rendering\ContentBuilding;

//_____________________________________________________________________________________________
class MarkupBuilder {

	/**
	 * default markup
	 * 
	 * @var array
	 */
	private $markup;

	/**
	 * markup preparation queue
	 */
	private $queue;

	/**
	 * construction
	 */
	public function __construct() {

		$this->markup = null;
		$this->queue = [ "root", "page", "template", "form", "element" ];
	}

	/**
	 * returns the markup / prepares markup when not already defined
	 * 
	 * HeadComment:
	 */
	public function getMarkup() {

		if ( is_null($this->markup) );
			return $this->prepare();

		return $this->markup;
	}

	/**
	 * prepares the markup
	 * 
	 * HeadComment:
	 */
	public function prepare( bool $rebuild = false ) {

		if ( !is_null($this->markup) && !$rebuild )
			return $this->markup;

		# build markup by passing the result markup down the queue
		$this->markup = [];

		foreach( $this->queue as $builder ) {
			if ( \Conference::hasService("markupbuilder." . $builder) )
				\Conference::service( "markupbuilder." . $builder )->prepare( $this->markup );
		}

		return $this->markup;
	}
}

//_____________________________________________________________________________________________
//