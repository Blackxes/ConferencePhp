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
class TemplateMarkupBuilder extends namespace\MarkupBuilderBase {

	/**
	 * construction
	 */
	public function __construct() {

		parent::__construct();

		$this->templates = [ "category_side_menu_category_list", "property_details" ];

		\Conference::service( "templax" )->init([
			"templates" => $this->templates,
			"options" => ["file" => CONREN_PATH_TEMPLATES_ABS . "/post-templates.html"]
		]);
	}

	/**
	 * prepares the markup
	 * 
	 * HeadComment:
	 */
	public function prepare( array &$markup ) {

		foreach( $this->templates as $i => $template ) {
			
			$default = [ "id" => $template, "template" => $template, "templateSelect-template" => [
				"_options" => ["parse" => false]
			]];
			$markup["templates"][$template] = \method_exists($this, $template) ? $this->$template($default) : $default;
		}

		return $this;
	}
}

//_____________________________________________________________________________________________
//