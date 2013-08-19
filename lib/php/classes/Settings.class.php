<?php

/**
 * WSSettings
 *
 * @package system
 * @subpackage	settings
 * @category	settings
 * @author	David Grevink
 * @link	http://websitesapp.com
 */
class WSSettings {

	var $settings = null;

	/**
	 * Class Constructor
	 *
	 * @access	public
	 * @return	object instance
	 */
	function WSSettings() {
		$this->settings = MyActiveRecord::FindAll('settings');
	}
	
	/**
	 * Returns the specified settings item
	 *
	 * @access	public
	 * @param	string	$item		item name to get info from
	 * @param	string	$section	item section name, default is config for config.php
	 * @param	string	$subitem	item sub section name, default is ''
	 * @return	item or NULL if not found.
	 */
	function get($item) {
		foreach($this->settings as $setting) {
			if ($setting->titleseo == $item) return $setting->value;
		}
		return null;
	}
	
}


/* End of file Settings.class.php */
/* Location: ./lib/php/classes/Settings.class.php */