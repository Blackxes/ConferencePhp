<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	configuration and setups

	@Author: Alexander Bassov
	@Email: blackxes@gmx.de
	@Github: https://www.github.com/Blackxes

/*********************************************************************************************/



//_____________________________________________________________________________________________
// development | live
$GLOBALS["Conference"]["Environment"] = "development";

//_____________________________________________________________________________________________
// general information
$GLOBALS["Conference"]["General"] = array(
	"version" => "1.0.0",
	"prefix" => "conf_",
	"domain" => "http://localhost/php/Conference"
);

//_____________________________________________________________________________________________
// some basic folder paths
$GLOBALS["Conference"]["FolderPaths"] = array();

//_____________________________________________________________________________________________
// database configurations
$GLOBALS["Conference"]["Database"] = array(
	"database" => "conference",
	"host" => "localhost",
	"user" => "root",
	"password" => ""
);

//_____________________________________________________________________________________________
// core services
$GLOBALS["Conference"]["Service"]["Services"] = array(

	// libraries / building / etc.
	"logfile" => "\Logfile\Logfile",
	"routinus" => "\Routinus\Routinus",
	"templax" => "\Templax\Templax",
	"formBuilder" => "\Buildorm\Buildorm",
	"menuBuilder" => "\Packages\Menu\Services\MenuBuilderService",

	// handler / classes / etc
	"router" => "\Conference\Core\Controlling\Router",
	"user" => "\Conference\Core\User\UserHandler",
	"userContentBuilder" => "\Conference\Core\Backend\Services\UserContentBuilderService",
	"renderer" => "\Conference\Core\Rendering\Renderer",
	"logfile" => "\Conference\Core\Service\LogfileBuilder",

	// custom services
	"productManager" => "\Packages\Product\Services\ProductManagerService",
	"productContentBuilder" => "\Packages\Product\Services\ProductContentBuilderService",

	"attributeManager" => "\Packages\Attribute\Services\AttributeManagerService",
	"attributeContentBuilder" => "\Packages\Attribute\Services\AttributeContentBuilderService",

	"categoryManager" => "\Packages\Category\Services\CategoryManagerService",
	"categoryContentBuilder" => "\Packages\Category\Services\CategoryContentBuilderService",
	
	"companyManager" => "\Packages\Company\Services\CompanyManagerService",
	"companyContentBuilder" => "\Packages\Company\Services\CompanyContentBuilderService",
);

//_____________________________________________________________________________________________
// service configurations
$GLOBALS["Conference"]["Service"]["Configurations"] = array(
	"formBuilder" => array(
		"forms" => array(
			"userlogin" => "\Conference\Core\Forms\UserloginForm",
			"userRegister" => "\Conference\Core\Forms\UserRegistrationForm",

			"company-register" => "\Packages\Company\Forms\CompanyRegisterForm",
			"company-edit" => "\Packages\Company\Forms\CompanyEditForm",

			"attribute-register" => "\Packages\Attribute\Forms\AttributeRegisterForm",
			"attribute-edit" => "\Packages\Attribute\Forms\AttributeEditForm",

			"category-register" => "\Packages\Category\Forms\CategoryRegisterForm",
			"category-edit" => "\Packages\Category\Forms\CategoryEditForm",

			"product-register" => "\Packages\Product\Forms\ProductRegisterForm",
			"product-edit" => "\Packages\Product\Forms\ProductEditForm",
		)
	)
);

//_____________________________________________________________________________________________
// service templates
$GLOBALS["Conference"]["Service"]["ServiceTemplates"] = array();

//_____________________________________________________________________________________________
// routing configuration
$GLOBALS["Conference"]["Routing"]["defaultRoute"] = "/products";

//_____________________________________________________________________________________________

//
// render relevant configurations
//

//_____________________________________________________________________________________________
// templates and markups
$GLOBALS["Conference"]["Templating"]["TemplatePath"] = CONFERENCE_ROOT . "/Conference/Core/Rendering/Layouts";
$GLOBALS["Conference"]["Templating"]["Templates"] = array(
	"base-page" => array( "path" => $GLOBALS["Conference"]["Templating"]["TemplatePath"] . "/components.html", "marker" => "base_page" ),
	"page-head" => array( "path" => $GLOBALS["Conference"]["Templating"]["TemplatePath"] . "/components.html", "marker" => "page_head" ),
	"page-body" => array( "path" => $GLOBALS["Conference"]["Templating"]["TemplatePath"] . "/components.html", "marker" => "page_body" ),
	"simplepage" => $GLOBALS["Conference"]["Templating"]["TemplatePath"] . "/simplepage.html",

	// menu
	"menu" => array( "path" => $GLOBALS["Conference"]["Templating"]["TemplatePath"] . "/menu.html", "marker" => "menu" ),
	"breadcrumps" => array( "path" => $GLOBALS["Conference"]["Templating"]["TemplatePath"] . "/menu.html", "marker" => "menu" ),

	// messaging - logfiles - etc what has 
	"log-listing" => array( "path" => $GLOBALS["Conference"]["Templating"]["TemplatePath"] . "/messaging.html", "marker" => "logfile_log_listing" ),
);

//_____________________________________________________________________________________________
// base markup
$GLOBALS["Conference"]["Templating"]["BaseMarkup"] = array(
	"page-head" => array(
		"title" => "",
		"stylesheets" => array(
			"main" => array( "stylesheet" => $GLOBALS["Conference"]["General"]["domain"] . "/Conference/Assets/Css/main.css" )
		)
	),
	"page-body" => array(
		"domain" => $GLOBALS["Conference"]["General"]["domain"],
		"main-menu" => "", // post processing
		"logs" => "", // post processing
		"head-image-source" => $GLOBALS["Conference"]["General"]["domain"] . "/Conference/Assets/Images/header.jpeg",
		"page-title" => "Conference Default",
		"content-items" => array(),
		"scripts" => array(
			"main" => array( "script" => $GLOBALS["Conference"]["General"]["domain"] . "/Conference/Assets/Js/main.js" )
		)
	)
);

//_____________________________________________________________________________________________
// base layouts
$GLOBALS["Conference"]["Rendering"] = array(
	"baseTemplate" => "base-page",
	"baseMarkup" => $GLOBALS["Conference"]["Templating"]["BaseMarkup"],
	"baseOptions" => array()
);

//_____________________________________________________________________________________________