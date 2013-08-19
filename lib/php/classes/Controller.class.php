<?php

/**
 * @package system
 *
 */
class WSController extends WSLoader {

	var $strings 	= null;
	var $dictionary = null;
	var $router		= null;
	
	var $params		= null;
	
	var $auth = null;

	function WSController() {
	
		parent::WSLoader();
		$this->load =& $this;
	
		global $config;
		global $config_folder;
		global $application_folder;
		global $system_folder;
		global $router;
		// Directories
		$this->application_folder = $application_folder;
		$this->config_folder = $config_folder;
		$this->system_folder = $system_folder;

		// Configuration objects
		$this->config =& $config;

		// Essential classes		
		$this->router =& $router;

		// Load up params
		$this->params = array_slice($this->router->segments, 2);

		$this->load('config');
		$this->config->load();
	}
	
	function show_error($message) {
		return "<div class='ws-debug' style='text-align: left; padding-left: 80px;'><pre>" . $message . "</pre></div>";
	}
	
	function _auth_display_login() {} 	// Overload this to create a login form

	function _client_specific() {}		// Overload this to add all client_specific site patches

}

?>