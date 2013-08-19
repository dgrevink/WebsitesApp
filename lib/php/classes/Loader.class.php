<?php

/**
 * WSLoader
 * Loader Class
 *
 * @package system
 *
 */
class WSLoader {

	function WSLoader() {
	}

	/**
	 * Loads a Website system class
	 * @param $item The item to load
	 */
	function load($item) {
		require_once(WS_SYSTEM_FOLDER . "php/classes/" . ucfirst($item) . ".class.php");
		$class = 'WS' . ucfirst($item);
		$this->$item = new $class;
	}
	
	/**
	 * Loads a Website base class
	 * 
	 * Base classes are the system classes.
	 * 
	 * Can be called statically:
	 * <code>
	 * WSLoader::load_base('log');
	 * </code>
	 * @param $item The item to load ( config, controller, log, menu, router, security, system and templates )
	 */
	function load_base($item) {
		switch ($item) {
			case 'constants':
				require_once(WS_SYSTEM_FOLDER . "/php/classes/Constants.class.php");
			break;
			case 'config':
				require_once(WS_SYSTEM_FOLDER . "/php/classes/Config.class.php");
			break;
			case 'controller':
				require_once(WS_SYSTEM_FOLDER . "/php/classes/Controller.class.php");
			break;
			case 'log':
				require_once(WS_SYSTEM_FOLDER . "/php/classes/Log.class.php");
			break;
			case 'history':
				require_once(WS_SYSTEM_FOLDER . "/php/classes/History.class.php");
			break;
			case 'metadata':
				require_once(WS_SYSTEM_FOLDER . "/php/classes/Metadata.class.php");
			break;
			case 'menu':
				require_once( WS_SYSTEM_FOLDER . '/php/classes/Menu.class.php');
			break;
			case 'router':
				require_once( WS_SYSTEM_FOLDER . '/php/classes/Router.class.php' );
			break;			
			case 'security':
				require_once( WS_SYSTEM_FOLDER . '/php/classes/Security.class.php' );
			break;			
			case 'system':
				require_once( WS_SYSTEM_FOLDER . '/php/classes/System.class.php' );
			break;			
			case 'turbo':
				require_once( WS_SYSTEM_FOLDER . '/php/classes/Turbo.class.php' );
			break;
			case 'templates':
				WSLoader::load_support('smarty');
				require_once( WS_SYSTEM_FOLDER . '/php/classes/Template.class.php' );
			break;			
			case 'templates-v2':
				WSLoader::load_support('raintpl');
				require_once( WS_SYSTEM_FOLDER . '/php/classes/Template-v2.class.php' );
			break;			
			case 'settings':
				require_once( WS_SYSTEM_FOLDER . '/php/classes/Settings.class.php' );
			break;
		}
	}
	
	/**
	 * Loads a Website support set of files
	 *
	 * A support class is a 3rd party set of PHP functions which are available to the integrator
	 *
	 * Can be called statically:
	 * <code>
	 * WSLoader::load_support('activerecord');
	 * </code>
	 * @param $item The item to load ( json, mail, yaml, activerecord and smarty )
	 */
	function load_support($item) {
		switch ($item) {
			case 'json':
				require_once(WS_SYSTEM_FOLDER . "php/support/json/json.php");
			break;
			case 'mail':
				require_once(WS_SYSTEM_FOLDER . "php/support/mail/htmlMimeMail.php");
			break;
			case 'yaml':
				require_once(WS_SYSTEM_FOLDER . "php/support/yaml/spyc.php");
			break;
			case 'activerecord':
				require_once( WS_SYSTEM_FOLDER . '/php/support/myactiverecord/MyActiveRecord.class.php' );
			break;
			case 'smarty':
				require_once( WS_SYSTEM_FOLDER . '/php/support/smarty/Smarty.class.php');
			break;
			case 'raintpl':
				require_once( WS_SYSTEM_FOLDER . '/php/support/raintpl/rain.tpl.class.php');
			break;
			case 'xls':
				require_once( WS_SYSTEM_FOLDER . '/php/support/xls/excel.php');
			break;
			case 'twitter':
				require_once( WS_SYSTEM_FOLDER . '/php/support/twitter/twitter.class.php');
			break;
			case 'posterous':
				require_once( WS_SYSTEM_FOLDER . '/php/support/posterous/posterous-api.php');
			break;
			case 'phpthumb':
				require_once( WS_SYSTEM_FOLDER . '/php/support/phpthumb/ThumbLib.inc.php');
			break;
			case 'phpthumb-filters':
				require_once( WS_SYSTEM_FOLDER . '/php/support/phpthumb/phpthumb_functions.class.php');
				require_once( WS_SYSTEM_FOLDER . '/php/support/phpthumb/phpthumb_filters.class.php');
			break;
			case 'flickr':
				require_once( WS_SYSTEM_FOLDER . '/php/support/phpflickr/phpFlickr.php');
			break;
			case 'captcha':
				require_once( WS_SYSTEM_FOLDER . '/php/support/recaptcha/recaptchalib.php');
			break;
			case 'rss':
				require_once( WS_SYSTEM_FOLDER . '/php/support/rss/lastRSS.php');
			break;
			case 'domparser':
				require_once( WS_SYSTEM_FOLDER . '/php/support/domparser/simple_html_dom.php');
			break;
		}
	}
	
	/**
	 * Loads a Website helper
	 * 
	 * Helpers are sets of functions grouped in categories to aid the integrator
	 *
	 * Can be called statically:
	 * <code>
	 * WSLoader::load_helper('debug');
	 * </code>
	 * @param $item The item to load ( debug, database, file, html, encoding, misc, forms, forms-advanced, system and auth )
	 */
	function load_helper($item) {
		switch($item) {
			case 'debug':
				require_once( WS_SYSTEM_FOLDER . '/php/helpers/debug.helper.php');
			break;
			case 'database':
				require_once( WS_SYSTEM_FOLDER . '/php/helpers/database.helper.php');
			break;
			case 'file':
				require_once( WS_SYSTEM_FOLDER . '/php/helpers/file.helper.php');
			break;
			case 'image':
				require_once( WS_SYSTEM_FOLDER . '/php/helpers/image.helper.php');
			break;
			case 'html':
				require_once( WS_SYSTEM_FOLDER . '/php/helpers/html.helper.php');
			break;
			case 'encoding':
				require_once( WS_SYSTEM_FOLDER . '/php/helpers/encoding.helper.php');
			break;
			case 'misc':
				require_once( WS_SYSTEM_FOLDER . '/php/helpers/misc.helper.php');
			break;
			case 'net':
				require_once( WS_SYSTEM_FOLDER . '/php/helpers/net.helper.php');
			break;
			case 'forms':
				require_once( WS_SYSTEM_FOLDER . '/php/helpers/forms.helper.php');
			break;
			case 'forms-advanced':
				require_once( WS_SYSTEM_FOLDER . '/php/helpers/forms-advanced.helper.php');
			break;
			case 'system':
				require_once( WS_SYSTEM_FOLDER . '/php/helpers/system.helper.php');
			break;
			case 'auth':
				set_include_path(get_include_path() . PATH_SEPARATOR . WS_SYSTEM_FOLDER . "php/support/auth");
				require_once( 'Auth.php');
				require_once( 'DB.php');
			break;

		}
	}
	
	/**
	 * Loads a Website dictionary
	 *
	 * Dictionaries are classes containing series of important values that can be used by the integrator
	 *
	 * Can be called statically:
	 * <code>
	 * WSLoader::load_dictionary('languages');
	 * </code>
	 * @param $item The item to load ( charsets, colors, countries, languages and mimes )
	 */
	function load_dictionary($item) {
		switch($item) {
			case 'charsets':
				require_once( WS_SYSTEM_FOLDER . '/php/dictionaries/Charsets.dictionary.php');
			break;
			case 'colors':
				require_once( WS_SYSTEM_FOLDER . '/php/dictionaries/Colors.dictionary.php');
			break;
			case 'countries':
				require_once( WS_SYSTEM_FOLDER . '/php/dictionaries/Countries.dictionary.php');
			break;
			case 'languages':
				require_once( WS_SYSTEM_FOLDER . '/php/dictionaries/Languages.dictionary.php');
			break;
			case 'translations':
				require_once( WS_SYSTEM_FOLDER . '/php/dictionaries/Translations.dictionary.php');
			break;
			case 'mimes':
				require_once( WS_SYSTEM_FOLDER . '/php/dictionaries/Mimes.dictionary.php');
			break;
		}
	}
}

?>