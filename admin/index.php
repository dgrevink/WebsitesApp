<?php
	$system_folder 		= '../lib/';
	$application_folder = '../admin/application/';
	$config_folder      = '../admin/application/config/';

	define('WS_EXT',				'.'. pathinfo(__FILE__, PATHINFO_EXTENSION));
	define('WS_APPLICATION_FOLDER', pathinfo( __FILE__, PATHINFO_DIRNAME ) . '/' . $application_folder);
	define('WS_CONFIG_FOLDER',  	pathinfo( __FILE__, PATHINFO_DIRNAME ) . '/' . $config_folder );

	define('WS_ADMINISTERED_APPLICATION_FOLDER', WS_APPLICATION_FOLDER . '/../../application/');
	define('WS_ADMIN', true);

	define('GMAPS_KEY', "ABQIAAAAIi-Y20zOoEg_fUTptVUoIBTDsmkqSNXVlxGpkegMqYnjjivPFhQo4rOo-o6dumzy1sZ9VVQbiGr6lQ");

	include( $system_folder . '/php/setup.php' );
