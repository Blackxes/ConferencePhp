<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * Todo: edit
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace \Conference\Core\Rendering;

abstract class Block extends namespace\RenderObject {
	
	/**
	 * construction
	 */
	public function __construct() {

		parent::__construct([
			"templates" => [],
			"options" => []
		]);
	}

	/**
	 * prepares the block
	 */
	abstract public function prepare();

	/**
	 * preprocessing operations
	 */
	public function preprocess() {

		# in here apply operations onto the markup
		# the content, manipulate whatever you like

		# this is the final hook you have to change the content
		# before it gets rendered
		
		return $this;
	}
}