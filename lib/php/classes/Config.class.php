<?php

/**
 * WSConfig
 *
 * @package system
 * @subpackage	config
 * @category	config
 * @author	David Grevink
 * @link	http://websitesapp.com
 */
class WSConfig {

	var $params = array();

	/**
	 * Class Constructor
	 *
	 * @access	public
	 * @return	object instance
	 */
	function WSConfig() {
	}
	
	/**
	 * Loads a set of configuration options
	 *
	 * @access	public
	 * @param	string	$folder absolute path to config folder to load
	 */
	function load($folder = WS_CONFIG_FOLDER) {
		require($folder . 'config.php');
		$this->params['config'] = $config;

		require($folder . 'system.php');
		$this->params['system'] = $system;

		require($folder . 'routes.php');
		if (isset($uroute)) {
			$this->params['routes'] = array_merge($route, $uroute);
		}
		else {
			$this->params['routes'] = $route;
		}
	}
	
	/**
	 * Returns the specified configuration item
	 *
	 * @access	public
	 * @param	string	$item		item name to get info from
	 * @param	string	$section	item section name, default is config for config.php
	 * @param	string	$subitem	item sub section name, default is ''
	 * @return	item or NULL if not found.
	 */
	function get($item, $section='config', $subitem='') {
		// If no subitem specified, get directly the item
		if ($subitem == '') {
			return isset($this->params[$section][$item])?$this->params[$section][$item]:NULL;
		}
		else {
			return isset($this->params[$section][$item][$subitem])?$this->params[$section][$item][$subitem]:NULL;
		}
	}
	
	/**
	 * Returns the specified configuration item as an array
	 *
	 * @access	public
	 * @param	string	$item		item name to get info from
	 * @return	item
	 */
	function getarray($item) {
		return $this->params[$item];
	}
	
	/**
	 * Sets the database connection parameters for ActiveRecord
	 *
	 * @access	public
	 * @param	string	$dep	deployment ID to set db access for
	 */
	function setdb($dep) {
		$db_users = explode(',', $this->get($dep, 'config', 'db_user'));
		array_trim_recursive($db_users);
		$db_user = $db_users[rand(0,count($db_users)-1)];
		$dsn = 'mysql://'
				. $db_user
				. ":" 
				. $this->get($dep, 'config', 'db_password') 
				. '@' 
				. $this->get($dep, 'config', 'db_server') 
				. '/' 
				. $this->get($dep, 'config', 'db_name');
		@define('MYACTIVERECORD_CONNECTION_STR', $dsn);
	}
	
	/**
	 * Saves the site's configuration
	 * Used as a dispatcher for _save_system() and _save_config()
	 * Configuration files are plain .php files.
	 * They are generated using smarty templates.
	 * Templates are located in lib/views/config/
	 *
	 * @access	public
	 * @param	string	$destination	Destination directory where to save the output file
	 * @param	string	$item			'system', 'config' or 'all', defaults to 'all'
	 * @return	TRUE or FALSE if the operation succeeded or not.
	 */
	function save($destination, $item='all') {
		WSLoader::load_base('templates');
		$t = new Template;
		$t->template_dir = WS_SYSTEM_FOLDER . '/views/config/';

		switch ($item) {
			case 'system':
				return $this->_save_system($destination, $t);
			break;
			case 'config':
				return $this->_save_config($destination, $t);
			break;
			case 'route':
				return $this->_save_routes($destination, $t);
			break;
			case 'all':
				return ( ($this->_save_system($destination, $t)) && ($this->_save_config($destination, $t)) && ($this->_save_routes($destination, $t)) );
			break;
			default:
				return FALSE;
			break;
		}
	}
	
	/**
	 * Saves the site's system.php configuration file
	 * Configuration files are plain .php files.
	 * They are generated using smarty templates.
	 * Templates are located in lib/views/config/
	 *
	 * @access	private
	 * @param	string		$destination	Destination directory where to save the output file
	 * @param	Template	$t				Template object used for file generation
	 * @return	TRUE or FALSE if the operation succeeded or not.
	 */
	function _save_system($destination, $t) {
		$t->assign('system', $this->params['system']);
		$code = $t->fetch('system.tpl');

		return (file_put_contents($destination . 'system.php', $code));
	}
	
	/**
	 * Saves the site's config.php configuration file
	 * Configuration files are plain .php files.
	 * They are generated using smarty templates.
	 * Templates are located in lib/views/config/
	 *
	 * @access	private
	 * @param	string		$destination	Destination directory where to save the output file
	 * @param	Template	$t				Template object used for file generation
	 * @return	TRUE or FALSE if the operation succeeded or not.
	 */
	function _save_config($destination, $t) {
		$t->assign('config', $this->params['config']);
		$t->assign('local', $this->params['config']['local']);
		$t->assign('beta', $this->params['config']['beta']);
		$t->assign('production', $this->params['config']['production']);
		$t->assign('languages', $this->params['config']['languages']);

		
		$template_page_ids = '';
		foreach($this->params['system']['reasonable_languages'] as $language) {
			if (isset($this->params['config']['template_page_id_' . $language])) {
				$template_page_ids .= '$config[\'template_page_id_' . $language . "'] = '" . $this->params['config']['template_page_id_' . $language] . "';";
			}
		}

		$t->assign('template_page_ids', $template_page_ids);

		$code = $t->fetch('config.tpl');

		return (file_put_contents($destination . 'config.php', $code));
	}

	/**
	 * Saves the site's route.php configuration file
	 * Configuration files are plain .php files.
	 * They are generated using smarty templates.
	 * Templates are located in lib/views/config/
	 *
	 * @access	private
	 * @param	string		$destination	Destination directory where to save the output file
	 * @param	Template	$t				Template object used for file generation
	 * @return	TRUE or FALSE if the operation succeeded or not.
	 */
	function _save_routes($destination, $t) {
		if (isset($this->params['uroutes'])) {
			$t->assign('uroute', $this->params['uroutes']);
			$code = $t->fetch('routes.tpl');
			return (file_put_contents($destination . 'routes.php', $code));
		}
		else {
			return true;
		}

	}
	
}


/* End of file Config.class.php */
/* Location: ./lib/php/classes/Config.class.php */