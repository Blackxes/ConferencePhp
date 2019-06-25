<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	configuration file

	Author: Alexander Bassov
	Email: blackxes@gmx.de
	Github: https://www.github.com/Blackxes

/*********************************************************************************************/



//_____________________________________________________________________________________________
// general
$GLOBALS["Buildorm"]["General"] = array(
	"version" => "1.0.0"
);

//_____________________________________________________________________________________________
// dependencies
$GLOBALS["Buildorm"]["Dependencies"] = array(
	"Logfile" => "\Logfile\Logfile",
	"Templax" => "\Templax\Templax"
);

//_____________________________________________________________________________________________
// Templates
$GLOBALS["Buildorm"]["Templates"] = array(
	"base" => array( "path" => BUILDORM_ROOT . "/Source/Templates/components.html", "marker" => "bd_form_base" ),
	"bd_form_field_label" => array( "path" => BUILDORM_ROOT . "/Source/Templates/components.html", "marker" => "bd_form_field_label" ),
	"bd_form_field_description" => array( "path" => BUILDORM_ROOT . "/Source/Templates/components.html", "marker" => "bd_form_field_description" ),
	"bd_form_field_title" => array( "path" => BUILDORM_ROOT . "/Source/Templates/components.html", "marker" => "bd_form_field_title" ),
	"bd_form_field_input" => array( "path" => BUILDORM_ROOT . "/Source/Templates/components.html", "marker" => "bd_form_field_input" ),
	"bd_form_field_select" => array( "path" => BUILDORM_ROOT . "/Source/Templates/components.html", "marker" => "bd_form_field_select" ),
	"html_attribute_list" => array( "path" => BUILDORM_ROOT . "/Source/Templates/components.html", "marker" => "html_attribute_list" )
);

//_____________________________________________________________________________________________
//