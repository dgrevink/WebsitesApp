<?php

# System Class
#
# Part of the Websites Open Source Project

class System {
	
	var $config = null;
	var $database = null;

	function System($config = null) {
		global $application_folder;
		
		$this->application_folder = $application_folder;

		$this->config = $config;
	}
	
	function check_offline() {
		if ($this->config->get('maintenance')) {
			$this->error_page(WS_FATAL, base64_decode($this->config->get('maintenance_text')), false);
		}
	}
	
	# Check if connection to database is possible and if database structure is correct
	function check_database($tables) {
		$message = '';
		if ($this->config->get('db_use','system') == true) {
			# Load database definition
			//$this->database = Spyc::YAMLLoad( WS_CONFIG_FOLDER . '/database.yaml');
			require( WS_CONFIG_FOLDER . '/database.php' );
			$this->database = $database;

			$resource = MyActiveRecord::Connection();

			if (mysql_errno() != 0) {
				$this->error_page(WS_FATAL, "MySQL error ".mysql_errno().": ".mysql_error());
				die();
			}
			
			if (trim($this->config->get(DEPLOYMENT, 'config', 'db_server')) == '') {
				$this->error_page(WS_FATAL, 'No db specified');
				die();
			}

			$ok = true;
			$missing_tables = array();
			foreach ($tables as $table) {
				$table_ok = MyActiveRecord::TableExists($table);
				if (!$table_ok) {
					$missing_tables[] = $table;
				}
				$ok = $ok && $table_ok;
			}
			if (!$ok) {
				$message .= 'Database structure is not right.<br/>';
				$message .= 'The following tables are missing: ' . implode(', ', $missing_tables);
//				//if ($this->config->get('setup', 'system')) $message .= $this->_db_create_tables();
				$this->error_page(WS_ERROR, $message);
				die();
			}
		}
	}
	
	function check_permissions() {
		if ($this->config->get('deployment') != 'local') {
			$message = 'File write permissions need to be set for the for the following directories for user ' . get_current_user() . ':<br/><br/>';
			$ok = true;
			if ( !is_writable(WS_APPLICATION_FOLDER . '/cache') ) {
				$message .= '&nbsp;&nbsp;&nbsp;- ' . WS_APPLICATION_FOLDER . '/cache<br/>';
				$ok = false;
			}
			if ( !is_writable(WS_APPLICATION_FOLDER . '/logs') ) {
				$message .= '&nbsp;&nbsp;&nbsp;- ' . WS_APPLICATION_FOLDER . '/logs<br/>';
				$ok = false;
			}
			if ( !is_writable(WS_APPLICATION_FOLDER . '/config') ) {
				$message .= '&nbsp;&nbsp;&nbsp;- ' . WS_APPLICATION_FOLDER . '/config<br/>';
				$ok = false;
			}
			if ( !is_writable(WS_ROOT . 'public') ) {
				$message .= '&nbsp;&nbsp;&nbsp;- ' . WS_APPLICATION_FOLDER . '/public<br/>';
				$ok = false;
			}
			if (!$ok) {
				$this->error_page(WS_FATAL, $message);
			}
		}
	}
	
	function error_page($severity, $message, $technical = true) {
		$t = new Template();

//		raintpl::$tpl_dir = "lib/views/"; 
//		raintpl::$cache_dir	= WS_APPLICATION_FOLDER . "/cache/cache/"; 
//		raintpl::$base_url = null;

        $t->template_dir = WS_SYSTEM_FOLDER . "/views/"; 
        $t->compile_dir  = WS_SYSTEM_FOLDER . "/cache/templates_c/"; 
        $t->config_dir   = WS_SYSTEM_FOLDER . "/cache/configs/"; 
        $t->cache_dir    = WS_SYSTEM_FOLDER . "/cache/cache/"; 
        
        $t->assign('message', $message);
        $t->assign('deployment', DEPLOYMENT);
        $t->assign('technical', $technical);
        
        $t->assign('ws_company', $this->config->get('company'));

		$t->assign('html_root', $this->config->get(DEPLOYMENT, 'config', 'html_root'));
		$t->assign('html_lib', 	$this->config->get(DEPLOYMENT, 'config', 'html_lib'));
		$t->assign('html_app', 	$this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . rtrim($this->application_folder, '/') );

      	switch($severity) {
      		case WS_FATAL:
      		case WS_ERROR:
      		case WS_WARNING:
      		default:
      			$t->display('error-fr.html', false);
      		break;
      	}
      	die();
	}
	
	function clean() {
		// Clean History
		$now = time();
		$then = $now - $this->config->get('timeout_history','system');
		$order = MyActiveRecord::Query('delete from wsthistory where unix_timestamp(ddate) < ' . $then);
		
		// Clean Logs
		//$site_log = file_get_contents(WS_APPLICATION_FOLDER . '/logs/site.log');
		//$lines = 
		//die($this->config->get('timeout_history','system'));
		
	}

	function _db_create_tables() {
		$message = "<br/><br/>An attempt has been made to create the missing tables:<br/><br/><span style='color: green;'>";
		foreach ($this->database['tables'] as $table) {
			if ( !MyActiveRecord::TableExists($table['name'])) {
				MyActiveRecord::Query($table['sql']);
				$message .= '&nbsp;&nbsp;&nbsp;- ' . $table['name'] . '<br/>';
			}
		}
		$message .= '</span>';
		return $message;
	}

}
