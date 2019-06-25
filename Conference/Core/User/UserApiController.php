<?php
/**********************************************************************************************
 * 
 * user model
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Conference\Core\User;

use \Conference\Core\Controller;
use \Conference\Core\Rendering;
use \Conference\Core\Http\Response;

class UserApiController extends Controller\ControllerBase {

	/**
	 * construction
	 */
	public function __construct( string $template = null, array $markup = [], array $hooks = [] ) {

		parent::__construct( $template, $markup, $hooks );
	}

	/**
	 * checks if the user login is valid
	 */
	public function verifyUserLogin() {

		$manager = \Conference::service( "user.manager" );
		$post = \Conference::request()->get("post");

		$user = $manager->get( $post->get("email"), "email", ["single" => true] );

		if ( !$user )
			return new Response\JsonResponse([ "givenEmail" => $post->get("email") ], "error", "Der Benutzer dieser Email konnte nicht gefunden werden." );

		# verify
		$result = password_verify( $post->get("password"), $user->get("password") );

		return ( !$result )
			? new Response\JsonResponse( null, "error", "Das Passwort ist falsch." )
			: new Response\JsonResponse([ "userid" => $user->get("uid") ], "ok", "Erfolgreich eingeloggt." );
	}

	/**
	 * loggs a specific user into the system
	 */
	public function loginUserById() {

		$manager = \Conference::service( "user.manager" );
		$post = \Conference::request()->get("post");

		# check user existance
		$user = $manager->get( $post->get("userid") );
		list( $email, $pw ) = $post->get([ "email", "password"], true );

		if ( $manager->has($email, "email") )
			return new Response\JsonResponse( ["email" => $email], "error", "Der Benutzer dieser Email konnte nicht gefunden werden." );
		
		# login
		$login = new namespace\Auth\Login( $email, $pw );

		if ( !$login->verify() )
			return new Response\JsonResponse( null, "error", $login->get("message") );

		$manager->loginUser( $login );
	}
}