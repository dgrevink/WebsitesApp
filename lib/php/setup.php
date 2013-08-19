<?php
	/**
	 * Websites Open Source Code Project
	 *
	 * Powerful PHP CMS
	 * Written by David Grevink under permission by Ideva
	 * http://code.google.com/p/websites/
	 * Licensed under the GNU General Public License v3 (See gpl-3.0.txt for details)
	 *
	 * @package system
	 *
	 */

	# Includes
	define( "WS_SYSTEM_FOLDER", substr(dirname(__FILE__),0, strlen(dirname(__FILE__)) - 3) );
	define( "WS_ROOT", WS_SYSTEM_FOLDER . '../');

	header('Content-Type: text/html; charset=utf-8');
	ini_set('memory_limit', -1);
	ini_set ('max-execution_time', '36000' );
	ini_set("post_max_size", "64M");
	ini_set("upload_max_filesize", "64M");
	

	require_once( WS_SYSTEM_FOLDER . '/php/classes/Loader.class.php');

	WSLoader::load_helper('debug');

	// Detect if there is a cached page, bypass the complete system if so
	WSLoader::load_base('turbo');

	WSLoader::load_support('activerecord');

	WSLoader::load_base('constants');
	WSLoader::load_base('config');
	WSLoader::load_base('controller');
	WSLoader::load_base('security');
	WSLoader::load_base('router');
	WSLoader::load_base('system');

	WSLoader::load_helper('misc');
	WSLoader::load_helper('file');
	WSLoader::load_helper('auth');
	
	$model_classes = array();

	$config = new WSConfig();
	$config->load();
	
	if (!defined('WS_ADMIN')) {
		# Load Model
		$dir = WS_APPLICATION_FOLDER . '/models/';
		foreach ( glob($dir . '*.class.php') as $item) {
			$file = basename($item);
        	if ( strstr($file, '.class.php') ) require( $dir . $file );
        	$model_classes[] = strtolower(file_basename($file));
		}
	}
	else {
		$app_config = new WSConfig;
		$app_config->load(WS_ADMINISTERED_APPLICATION_FOLDER . 'config/');

//		$config->params['config']['default_language'] = $app_config->get('default_language');
		$config->params['config']['languages'] = $app_config->get('languages');
		$config->params['config']['html_root'] = $app_config->get('html_root');

		foreach ($app_config->get('deployments') as $dep) {
			foreach($app_config->get($dep) as $key => $value) {
				if (strstr($key, 'db_')) $config->params['config'][$dep][$key] = $value;
			}
		}
		$config->params['config']['deployment'] = $app_config->get('deployment');

		$config->setdb($config->get('deployment'));

		# Load Admin Model
		$already_loaded = array();
		$dir = WS_APPLICATION_FOLDER . '/models/';
		foreach ( glob($dir . '*.class.php') as $item) {
			$file = basename($item);
        	if ( strstr($file, '.class.php') ) {
        		require( $dir . $file );
        		$already_loaded[] = $file;
	        	$model_classes[] = strtolower(file_basename($file));
        	}
		}

		// Load application model
		$dir = WS_ADMINISTERED_APPLICATION_FOLDER . '/models/';
		foreach ( glob($dir . '*.class.php') as $item) {
			$file = basename($item);
        	if ( strstr($file, '.class.php') && !in_array($file, $already_loaded) ) require( $dir . $file );
        	$model_classes[] = strtolower(file_basename($file));
		}
		
	}
	define( "DEFAULT_LANGUAGE", $config->get('default_language'));
	define( "DEPLOYMENT",       $config->get('deployment'));

	define( "ROOT",        		$config->get(DEPLOYMENT, 'config', 'root'));
	define( "HTML_ROOT",        $config->get(DEPLOYMENT, 'config', 'html_root'));
	define( "LIB",				$config->get(DEPLOYMENT, 'config', 'lib'));
	define( "HTML_LIB",         $config->get(DEPLOYMENT, 'config', 'html_lib'));
	
	
	define( "SITE_DEBUG", 		$config->get('debug', 'system'));				# Set to true for debugging mode
	define( "SITE_DEBUG_LOG",	$config->get('debug_log', 'system') );			# If debug is on, false disables log file generation and activates screen debug

	# Data Source Name: This is the universal connection string
	$db_users = explode(',', $config->get(DEPLOYMENT, 'config', 'db_user'));
	array_trim_recursive($db_users);
	$db_user = $db_users[rand(0,count($db_users)-1)];
	$dsn = 'mysql://'
			. $db_user 
			. ":" 
			. $config->get(DEPLOYMENT, 'config', 'db_password')
			. '@' 
			. $config->get(DEPLOYMENT, 'config', 'db_server') 
			. '/' 
			. $config->get(DEPLOYMENT, 'config', 'db_name');

	define('MYACTIVERECORD_CACHE_SQL', true);

	if (!defined('WS_ADMIN')) define('MYACTIVERECORD_CONNECTION_STR', $dsn);

	# Activate Error Reporting if specified
	#	set_error_handler("site_error_handler");
	if ( !SITE_DEBUG ) {
		error_reporting(0);
	}
	else {
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_CORE_ERROR | E_COMPILE_ERROR | E_COMPILE_WARNING | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE );
		#error_reporting(E_ALL );
		#error_reporting(6143);
		ini_set("display_errors","1");

		if (SITE_DEBUG_LOG) define('MYACTIVERECORD_LOG_SQL_TO', $application_folder . 'logs/sql.log');
	}

	# Load up and instantiate security broker.
	# Past this point, all input should be sanitized
	$security = new Security();

	
	# Load up the router
	# This class controls which class we should use based on the requested URI
	$router = new Router();

	if ($router->error) {
		require_once($application_folder . 'controllers/Site.class.php');
		$site = new Site;
		$site->redirect($router->error);
	}

	# Load up the system class
	# This class checks:
	#   - Database connection
	#   - Session management
	#	- Cleans up logs and histories
	$system = new System($config);
	if (!defined('WS_ADMIN')) $system->check_offline();
	if (!defined('WS_ADMIN')) $system->check_database($model_classes);
	if (!defined('WS_ADMIN')) $system->check_permissions();
	if (!defined('WS_ADMIN')) $system->clean();

	# Instantiate the current controller based on the router
	$controller = new $router->controller();

	$a = null;
	if ($config->get('security', 'system') == true) {
		 $options = array(
		   array(
		     'type'    => 'DB',
		     'options' => array(
				"table" => $config->get('security_table', 'system')?$config->get('security_table', 'system'):'users',
				'dsn' => $dsn,
				"usernamecol" => "username", 
				"passwordcol" => "password",
				"sessionName" => $config->get('security_session', 'system')
		     )
		   )
		);
		
		$a = new Auth('Multiple', $options, 'loginFunction', true);
		
//		$a = new Auth("DB", $options, 'loginFunction', true);
		$a->setIdle($config->get('timeout_sessions', 'system'));
		$controller->auth =& $a;
		$a->start();
	}
	
	// Cache control: clean up all image files if the cache lifetime has been reached
	$now = time();
	$when = filemtime(WS_APPLICATION_FOLDER . '/cache/cache/') + $config->get('cache_lifetime','system');
	if ( $now > $when ) {
		rm(WS_APPLICATION_FOLDER . '/cache/cache/*.jpg');
	}

	# Determine the method defined by the router
	$method = $router->method;
	if ( !method_exists($controller, $method) ) {
		echo 'Controller ' . $router->controller . ' does not support the ' . $method . '() method.';
		die();
	}

	$controller->$method();
	
	function loginFunction($username = null, $status = null, &$auth = null) {
		global $controller;
		global $method;
		
		$controller->auth_username = $username;
		$controller->auth_status = $status;
		$controller->auth =& $auth;
		
		$controller->_auth_display_login();
	}

	@mysql_close(MyActiveRecord::Connection());
	
//	d(get_defined_constants());
	
//	d(get_defined_vars());

//	d(get_declared_classes());


