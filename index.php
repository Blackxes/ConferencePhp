<?php

//_____________________________________________________________________________________________
/**********************************************************************************************

	.. and the story begins ..

	Author: Alexander Bassov
	Email: blackxes@gmx.de
	Github: https://www.github.com/Blackxes

/*********************************************************************************************/

require_once ( "./bootstrap.php" );

//_____________________________________________________________________________________________
	// load and initialize system
	\Conference\Core\Bootstrap\Bootstrap::Init();

	$response = \Conference::service( "router" )
		->handleRequest();
	
	\Conference::service( "renderer" )
		->render( $response );

//_____________________________________________________________________________________________
//

// Todo: rewrite renderer / templating like in WordJump
// [DONE] Todo: permission rescriction / rewrite controller handler !!
// [DONE] Todo: finish router / it should use routinus to get the correct route from the db
// [DONE] Todo: build templating to enable dynamic page building based on the (context)