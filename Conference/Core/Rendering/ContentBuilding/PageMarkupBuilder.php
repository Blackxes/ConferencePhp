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
class PageMarkupBuilder extends namespace\MarkupBuilderBase {

	/**
	 * construction
	 */
	public function __construct() {

		parent::__construct([
			"templates" => [ "page_spacer", "main_logo", "searchbar", "conren_slider", "conren_settings", "conren_footer", "conren_panel" ],
			"options" => [ "file" => CONREN_PATH_TEMPLATES_ABS . "/page.html" ]
		]);
	}
	
	/**
	 * prepares the markup
	 * 
	 * HeadComment
	 */
	public function prepare( array &$markup ) {

		// page top
		$markup["page-top"]["logo"] = function() { return [
			"template" => "main_logo",
			"templateSelect-template" => array(
				"source" => CONREN_PATH_UPLOADS . "/emhashop-logo.png",
				"alt" => "Emhashop-logo",
				"link" => $GLOBALS["CONREN"]["General"]["domain"]
			)
		];};
		$markup["page-top"]["searchBar"] = [
			"template" => "searchbar",
			"templateSelect-template" => [
				"action" => $GLOBALS["CONREN"]["General"]["domain"] . "/products/search",
				"method" => "get",
				"target" => "_self",
				"source" => CONREN_PATH_ASSETS . "/Icons/search.svg"
			]
		];
		$markup["page-top"]["userTeaser"] = \Conference::service( "user.markupbuilder" )->userTeaser();

		// pre top content
		$markup["pre-top-content"]["conrenSettings"] = function() { return [
			"template" => "conren_settings",
			"templateSelect-template" => array(
				"menu-items" => array(
					"properties" => array( "label" => "Eigenschaften", "link-type" => "properties", "link" => $GLOBALS["CONREN"]["General"]["domain"] . "/property/register" ),
					"products" => array( "label" => "Produkte", "link-type" => "products", "link" => $GLOBALS["CONREN"]["General"]["domain"] . "/products" ),
					"companies" => array( "label" => "Gesellschaften", "link-type" => "companies", "link" => $GLOBALS["CONREN"]["General"]["domain"] . "/companies" ),
				)
			)
		];};

		// top content
		// $tcontent["frontendSlider"] = $this->frontendSlider();
		// $tcontent["categoryMenu"] = \Conference::service( "categoryContentBuilder" )->getRootCategoryList();

		// side menu
		$markup["side-menu"]["side-menu"] = \Conference::service( "category.markupbuilder" )->sideMenu();

		// footer
		$markup["footer"]["main"] = function() { return [
			"template" => "conren_footer",
			"templateSelect-template" => array(
				"links" => array(
					array( "link" => "imprint", "label" => "Impressum" ),
					array( "link" => "contact", "label" => "Kontakt" ),
					array( "link" => "about", "label" => "About" ),
				)
			)
		];};

		// panel to several pages within the website
		$markup["post-top"]["panel"] = [
			"template" => "conren_panel",
			"templateSelect-template" => [
				"menu" => [
					[ "title" => "Services", "link" => "admin/services" ],
					[ "title" => "News Manager", "link" => "news/manager" ],
					[ "title" => "Gesellschaften", "link" => "companies" ],
					[ "title" => "Produkte", "link" => "products" ],
					[ "title" => "Kategorien", "link" => "categories" ],
					[ "title" => "Profil", "link" => "user/profile" ],
					[ "title" => "Random Product", "link" => "product/details?p=" . (\Conference::service("product.manager")->random()->get("article_name")) ]
				]
			]
		];

		return $this;
	}
}

//_____________________________________________________________________________________________
//

