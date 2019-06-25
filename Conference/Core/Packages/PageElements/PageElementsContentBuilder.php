<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * builds basic page elements - these are "hard coded" to the current required page look
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\Packages\PageElements;

//_____________________________________________________________________________________________
class PageElementsContentBuilder {

	/**
	 * package id
	 */
	const PACKAGE_ID = "page_elements";

	/**
	 * render elements of page
	 */
	const PAGE_ELEMENTS = array(
		"body_top",
		"categories",
		"frontend_slider",
		"products"
	);

	/**
	 * construction
	 */
	public function __construct() {

		// register templates for the elements
		\Conference::service( "templax" )->registerTemplateSet( array(
			"conren_slider" => array( "file" => _CONREN_TEMPLATEPATH . "/page-elements.html", "marker" => "conren_slider" ),
			"conren_element_listing" => array( "file" => _CONREN_TEMPLATEPATH . "/page-elements.html", "marker" => "conren_element_listing" ),
			"conren_body_top" => array( "file" => _CONREN_TEMPLATEPATH . "/page-elements.html", "marker" => "conren_body_top" ),

			// elements from other files
			"account_simple" => array( "file" => _CONREN_TEMPLATEPATH . "/account.html", "marker" => "account_simple" ),
			"shopping_cart_simple" => array( "file" => _CONREN_TEMPLATEPATH . "/shopping-cart.html", "marker" => "shopping_cart_simple" )
		));

		// include the content configurations
		require_once( CONREN_PACKAGE_FOLDER . "/PageElements/PageElementsContentConfiguration.php" );
	}

	public function getPageBody() {
		
		// get content creation functions
		$id = PageElementsContentBuilder::PACKAGE_ID;
		$creator = preg_grep( "/{$id}_/", get_defined_functions()["user"] );
		$markup = array();

		// loop defined elements and get content
		foreach( PageElementsContentBuilder::PAGE_ELEMENTS as $i => $name ) {

			$index = array_search("{$id}_{$name}", $creator);

			if ( $index !== false )
				$markup[] = (array) $creator[$index]();
		}
		
		return $markup;
	}
}

//_____________________________________________________________________________________________