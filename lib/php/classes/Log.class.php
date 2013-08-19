<?php

/*
	define ("WS_FATAL",	E_ERROR);
	define ("WS_ERROR",	E_WARNING);
	define ("WS_WARNING",	E_NOTICE);
	define ("WS_INFO",	1);
*/

WSLoader::load_helper('file');

class WSLog {
	function admin( $level, $user, $code, $message ) {
		if (defined('WS_ADMINISTERED_APPLICATION_FOLDER')) {
			append_file(WS_ADMINISTERED_APPLICATION_FOLDER . '/logs/admin.log', 
			      date("d/m/y H:i:s") . "\t" . $_SERVER['REMOTE_ADDR'] . "\t"
				. $level   ."\t"
				. $user    ."\t"
				. $code    ."\t"
				. $message . "\n"
			);
		}
	}

	function app( $level, $user, $code, $message ) {
			append_file(WS_APPLICATION_FOLDER . '/logs/site.log', 
			      date("d/m/y H:i:s") . "\t" . $_SERVER['REMOTE_ADDR'] . "\t"
				. $level   ."\t"
				. $user    ."\t"
				. $code    ."\t"
				. $message . "\n"
			);
	}
}

?>