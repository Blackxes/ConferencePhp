<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	model for a form field! not a form item

	Author: Alexander Bassov
	Email: blackxes@gmx.de
	Github: https://www.github.com/Blackxes

/*********************************************************************************************/

namespace Conference\Core\FormBuilder\Models;

use \Conference\Core\FormBuilder;

//_____________________________________________________________________________________________
class FormField {

	private $attributes;

	private $title;
	private $target;

	private $config;

	//_________________________________________________________________________________________
	public function __construct( string $title, string $target, array $attributes = array(),
		array $config = array() )
	{

		$this->attributes = array_merge( $GLOBALS["Buildorm"]["Defaults"]["FormFields"]["attributes"], $attributes );
		$this->config = array_merge( $GLOBALS["Buildorm"]["Defaults"]["FormFields"]["configuration"], $config );

		$this->title = $title;
		$this->target = $target;
	}

	//_________________________________________________________________________________________
	// basic setter/getter
	//
	public function setAttributes( array $attributes ) { $this->attributes = $attributes; }
	public function setAttribute( string $attribute, $value ) { $this->attributes[$attribute] = $value; }
	public function setTitle( string $title ) { $this->title = $title; }
	public function setTarget( string $target ) { $this->target = $target; }
	public function setConfigs( array $config ) { $this->config = $config; }
	public function setConfig( string $key, $value ) { $this->config[$key] = $value; }
	//
	public function getAttributes(): array { return $this->$attributes; }
	public function getAttribute( string $attribute ) { return $this->attributes[$attribute]; }
	public function getTitle(): string { return $this->title; }
	public function getTarget(): stirng { return $this->target; }
	public function getConfigs(): array { return $this->config; }
	public function getConfig( $key ) { return $this->config[$key]; }
	//
}


//_____________________________________________________________________________________________
//