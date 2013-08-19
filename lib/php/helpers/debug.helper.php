<?php

/**

	site_error_handler ($errno, $errstr, $errfile, $errline)
	debug( $object, $simple = false )
	o_O( $object, $simple = false )
	d( $object, $simple = false )
	
*/

function site_error_handler ($errno, $errstr, $errfile, $errline)
{
	global $application_folder;
	global $_SERVER;

	if (!($errno & error_reporting())) return; 

    if(!defined('E_STRICT'))            define('E_STRICT', 2048);
    if(!defined('E_RECOVERABLE_ERROR')) define('E_RECOVERABLE_ERROR', 4096);
    

	if ( SITE_DEBUG_LOG ) {
		$handle = fopen("{$application_folder}/logs/site.log", "a");
		if (!$handle) {
			debug("Bad File Handle for LOG");
		}
	}

	$timestamp = date("d/m/Y@H:i:s");

	$ip = (isset($_SERVER['REMOTE_ADDR']))?$_SERVER['REMOTE_ADDR']:'localhost';

	$string = "$timestamp - $ip S:$errno/F:" . basename($errfile) . "[$errline] $errstr ($errfile)";
	switch ($errno) {
			case FATAL:
				if ( SITE_DEBUG_LOG ) { fwrite( $handle, $string); }
				if ( SITE_DEBUG) debug($string);
				exit(1);
				break;
			case ERROR:
			case WARNING:
			default:
				if ( SITE_DEBUG_LOG ) { fwrite( $handle, $string); }
				if ( SITE_DEBUG) debug($string);
				break;
	}
	
	if ( SITE_DEBUG_LOG )
		fclose($handle);
}

function debug( $object, $simple = false ) {
	if ($simple) {
		print_r($object);
	}
	else {
		echo "<div class='ws-debug' style='text-align: left; padding-left: 80px;'><pre>";
		var_dump($object);
		echo "</pre></div>";
	}
}

function o_O( $object, $simple = false ) {
	debug($object, $simple);
}

function d($object, $simple=false) {
	debug($object, $simple);
}


?>