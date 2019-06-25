<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * contains the content configuration for the element building
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

// global namespace

//_____________________________________________________________________________________________
// Constants
class page_elements {

	const FRONTEND_SLIDER_IMAGES = array(
		array( "source" => "https://loremflickr.com/320/240" ),
		array( "source" => "https://loremflickr.com/320/240" ),
		array( "source" => "https://loremflickr.com/320/240" ),	
	);
}

//_____________________________________________________________________________________________
// body top
function page_elements_body_top() {
	
	$markup = array(
		"template" => "conren_body_top",
		"templateSelect-template" => array(
			"account_simple" => array(
				"label" => "blablabla"
			)
		)
	);

	return $markup;
}

//_____________________________________________________________________________________________
// returns the markup for the categories
function page_elements_categories() {

	$markup = array();

	return $markup;
}

//_____________________________________________________________________________________________
// returns the markup for the frontend slider
function page_elements_frontend_slider() {
	
	// image markup
	$markup = array(
		"template" => "conren_slider",
		"templateSelect-template" => array(
			"slider-id" => "frontend-slider",
			"images" => array()
		)
	);

	foreach( page_elements::FRONTEND_SLIDER_IMAGES as $i => $config ) {

		$markup["templateSelect-template"]["images"][] = array(
			"source" => $config["source"],
			"alt" => (string) $config["alt"]
		);
	}

	return $markup;
}

//_____________________________________________________________________________________________


