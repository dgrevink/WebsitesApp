<?php
	$system_folder 		= 'lib/';
	$application_folder = 'application/';
	$layout_folder		= 'application/views/layouts/';
	$config_folder      = 'application/config/';

	define('WS_EXT',				'.'. pathinfo(__FILE__, PATHINFO_EXTENSION));
	define('WS_APPLICATION_FOLDER', pathinfo( __FILE__, PATHINFO_DIRNAME ) . '/' . $application_folder);
	define('WS_CONFIG_FOLDER',  	pathinfo( __FILE__, PATHINFO_DIRNAME ) . '/' . $config_folder );
	define('WS_HTML_LAYOUT_FOLDER', '/' . $layout_folder );

	define('GMAPS_KEY', "");

	include( $system_folder . '/php/setup.php' );
