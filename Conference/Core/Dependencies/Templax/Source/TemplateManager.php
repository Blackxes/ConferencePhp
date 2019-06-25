<?php

//_____________________________________________________________________________________________
/**********************************************************************************************
 * 
 * manages the templates within templax
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

namespace Templax\Source;

use \Templax\Source\Models;

require_once( TEMPLAX_ROOT . "/Source/Classes/ParameterBag.php" );
require_once( TEMPLAX_ROOT . "/Source/Models/Template.php" );

//_____________________________________________________________________________________________
class TemplateManager extends Classes\ParameterBag {

	/**
	 * stores file content
	 * 
	 * @var Classes\ParameterBag
	 */
	private $fileCache;

	/**
	 * options
	 * 
	 * Todo: implement rest options
	 * 
	 * [Not Implemented] @silent - errors are suppressed and wont be thrown nor displayed 
	 * @dir - default directory where templates may lie
	 * 
	 * @var array
	 */
	private $options;

	/**
	 * construction
	 * 
	 * @param array $templates - initial templates
	 * @param array $options - options
	 */
	public function __construct( array $templates = [], array $options = [] ) {

		parent::__construct();

		$this->fileCache = new Classes\ParameterBag();
		$this->options = new Classes\ParameterBag([
			"dir" => "",
			"file" => "",
			"prefix" => "",
			"silent" => false,
			"cache" => true
		]);
		$this->options->merge( null, $options );

		$this->registerFull( $templates );
	}

	/**
	 * returns the (extracted) template content from a file
	 * 
	 * when passing the $file param keep in mind that the "dir" key of the options
	 * will be prepended when defined!
	 * 
	 * @param string $file - template file
	 * @param array $args - arguments of this template
	 * 	marker - the marker surrounding the template
	 * 		if marker null when the content from the file itself is interpreted as the template
	 * 
	 * @return false - when the file doesnt exists
	 * @return string - the file content
	 */
	public function extractTemplateContent( array $args ) {

		$args = array_merge( $this->options->all(), $args );
		$fileContent = $this->fileContent( $this->filePath($args), $args );

		if ( !$fileContent )
			return false;
		
		// either extract whole file or simply an extract of it
		$content = "";

		if ( !is_null($args["marker"]) ) {

			preg_match( "/<{$args['marker']}>(.*)?<\/{$args['marker']}>/si" , $fileContent, $match );
			$content = $match[1];
		}

		else
			$content = $fileContent;

		return $content;
	}

	/**
	 * returns the correct filepath based on the given arguments
	 */
	public function filePath( array $args ) {

		// when no directory is defined then simply use the file
		if ( !isset($args["dir"]) )
			return (string) $args["prefix"] . (string) $args["file"];
		
		// else when the directory is defined prepend it to the file name
		return (string) ($args["dir"] . (string) $args["prefix"] . (string) $args["file"]);
	}

	/**
	 * retuns the file content
	 * 
	 * the difference between a simple file_get_contents and this function is
	 * this function caches the file content when desired
	 * 
	 * @param string $file - the file
	 * @param array $args - options
	 * 	cache - shall this file be cached for later use?
	 * 	dir - the directory of the file with a following shlash (x:path/to/dir/)
	 * 
	 * @return false - when file doesnt exists
	 * @return string - the file content
	 */
	public function fileContent( string $file, array $args ) {

		// check cache
		if ( $this->fileCache->has($file) )
			return $this->fileCache->get($file);

		if ( empty($file) || !file_exists($file) )
			return false;
		
		$content = preg_replace("/\r\n/", "", preg_replace("/\s{2,}/", "", file_get_contents( $file )) );

		# remove html comments
		$content = preg_replace( "/<!--(.*?)-->/", "", $content );

		if ( !$args["cache"] )
			return $content;
		
		return $this->fileCache->set($file, $content)->get($file);
	}

	/**
	 * registers a template via raw values and returns the registered template
	 * 
	 * @param string $key - template key
	 * @param string $value - template value
	 * @param array $markup - default markup that will be applied before parsing
	 * @param array $options - default option that will be applied before parsing
	 * 
	 * @return null - when registration values are invalid
	 * @return \Templax\Models\Template - reference to the created template
	 */
	public function &registerRaw( string $key, string $value, array $markup = [], array $options = [] ) {
		
		if ( !$key )
			return null;

		$this->set( $key, new Models\Template( $key, $value, $markup, $options ) );
		
		return $this->ref($key);
	}

	/**
	 * registers a template via array definition
	 * 
	 * @param array $args - arguments of the template
	 */
	public function &registerArray( array $args ) {

		$preped = array_merge([ "key" => "", "value" => "", "markup" => [], "options" => [] ], $args );

		return call_user_func_array( [$this, "registerRaw"], $preped );
	}

	/**
	 * registers a set of templates via full configuration
	 * 
	 * the $args array shall contain the following keys
	 * 	[
	 * 		"templates" => [your_template_definitions]
	 * 		"options" => [your_options_for_the_registrations]
	 * 	]
	 * 
	 * # the individual template definition
	 * it can either be a simple string containing the key which also serves as the template marker
	 * or an array containing the following
	 * 
	 * 	template_key [
	 * 		(optional @see below) "key" => "template_key"
	 * 		(optional @see below) "marker" => "your_marker",
	 * 		(optional) "file" => "your_file",
	 * 		(optional) (markup) "markup" => [your_default_markup],
	 * 		(optional) (options) "options" => [your_default_options]
	 * 	]
	 * # key - is optional when the key of the template configuration is defined
	 * # marker - is optional when the key is missing or the marker differs from the key
	 * 
	 * ****************************************************************************************
	 * 
	 * @param array $args - the template registration arguments
	 * 
	 * @return boolean - true on success else false
	 */
	public function registerFull( array $args ) {

		if ( empty($args["templates"]) )
			return true;
		
		$options = array_merge( $this->options->all(), (array) $args["options"] );
		$base = array_merge( ["key" => "", "value" => "", "markup" => [], "options" => []], $options );

		foreach( $args["templates"] as $key => $config ) {

			// build the template registration
			$reg = $base;

			// first up check wether the key is defined as index
			if ( is_string($key) ) {
				$reg["key"] = $reg["marker"] = $key;
			}
			
			// or the key is defined as value of the key
			else if ( is_string($config) ) {
				$reg["key"] = $reg["marker"] = $config;
			}

			// now the definition / use the given one when array
			if ( is_array($config) ) {
				$reg = array_merge( $reg, $config );

				if ( !$reg["marker"] )
					$reg["marker"] = $reg["key"];
			}

			// get template value
			$reg["value"] = (string) @$this->extractTemplateContent($reg);
			
			// verify and register
			if ( !$options["silent"] && !$reg["key"] || !$this->registerArray($reg) )
				return false;
		}

		return true;
	}
}

//_____________________________________________________________________________________________
//