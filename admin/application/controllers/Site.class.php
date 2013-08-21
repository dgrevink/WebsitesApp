<?php

WSLoader::load_dictionary('languages');
WSLoader::load_dictionary('translations');
WSLoader::load_helper('system');
WSLoader::load_helper('database');
WSLoader::load_base('menu');
WSLoader::load_base('templates');
WSLoader::load_base('log');

$userlanguage = '';

class Site extends WSController {

	var $language = DEFAULT_LANGUAGE;
	var $infos 	  = null;
	var $smarty = null;
	var $current_page = null;
	var $current_page_title = null;
	var $parameters = null;

	function Site() {
		parent::WSController();

		global $menus;

		$this->menu = new WSMenu($menus);
		
		# Get database parameters from application
		$app_config = new WSConfig;
		$app_config->load(WS_ADMINISTERED_APPLICATION_FOLDER . '/config/');
		$this->app_config = $app_config;

		foreach ($app_config->get('deployments') as $dep) {
			foreach($app_config->get($dep) as $key => $value) {
				if (strstr($key, 'db_')) $this->config->params['config'][$dep][$key] = $value;
			}
		}
		$this->config->params['config']['deployment'] = $app_config->get('deployment');
		$this->config->setdb($this->config->get('deployment'));
		
		$this->template = new Template;

		$this->template->assign('company', $app_config->get('company'));
		$this->template->assign('brand', $this->config->get('brand'));
		$this->template->assign('brand_website', $this->config->get('brand-website'));
		$this->template->assign('brand_website_text', $this->config->get('brand-website-text'));
		
		$this->template->assign('maintenance_style', $app_config->get('maintenance')?"style='background: none; background-color: salmon;'":'');

	}

	function index() {
		global $userlanguage;

		if ($this->auth->session['idle'] <= ($this->auth->session['timestamp'] + 1)) {
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'User logged in successfully !' );
		}
		
		$user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
		if (!$user) {
			die();
		}
		$this->language = $user->language;
		$userlanguage = $user->language;
		$user_group = $user->find_parent('groups');
		$this->parameters['modules'] 	= unserialize($user_group->rights);
		$this->parameters['tables'] 	= unserialize($user_group->datarights);
		
		// Check if can log in in maintenance mode
		if (!isset($this->parameters['modules'][WSR_PARAMS_CAN_MAINTENANCE_CONNECT]) && $this->app_config->get('maintenance') ) {
			$this->auth->logout();
			$this->auth->start();
			header( 'Location: /admin/' );
			die();
		}
		
		switch ($this->language) {
			case 'fr':
				setlocale(LC_ALL, array('fr_FR.utf8', 'fr_FR'));
				break;
			default:
		}

		// Get the current language
		$this->current_language = (!isset($this->params[0]))?$this->app_config->get('default_language'):$this->params[0];

		# Set available languages	
		$languages = array();
		foreach($this->app_config->get('languages') as $language) {
			$languages[] = array(
				'code' => $language,
				'label' => WSDLanguages::getLanguageName($language),
				'selected' => ($language == $this->current_language)?'selected':''
			);
		}
		$this->template->assign('languages', $languages);

		
		// Set the session timeout
		$this->template->assign('timeout_sessions', $this->config->get('timeout_sessions', 'system'));




		# Menu - Fill in menu
		$menu = array();
		$menu3 = array();
		$page = isset($this->params[1])?$this->params[1]:'';
		$subpage = isset($this->params[2])?$this->params[2]:'';
		$state = ($page=='')?'uk-active':'none';
		$menu[] = array(
			'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $this->current_language ,
			'name' => WSDTranslations::getLabel('SYSTEM_MENU_DASHBOARD'),
			'icon' => "icon-dashboard",
			'state' => $state,
			'system' => 0,
			'children' => array()
			);



		# Menu - Fill in specified modules
		if (isset($this->parameters['modules'][WSR_CONTENTS])) {
			$menu[] = array(
				'path' => '#',
				'name' => WSDTranslations::getLabel('SYSTEM_MENU_CONTENT'),
				'icon' => "icon-th",
				'state' => ($page == 'content')?'uk-active':'none',
				'system' => 0,
				'children' => array(
					array(
						'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $this->current_language . '/content',
						'name' => "Pages",
						'state' => ($page == 'content')?'uk-active':'none',
						'system' => 0
					)
				)
			);
		}
		if (isset($this->parameters['modules'][WSR_FILES])) {
			$menu[] = array(
				'path' => '#',
				'name' => WSDTranslations::getLabel('SYSTEM_MENU_MEDIA'),
				'icon' => "icon-folder-close-alt",
				'state' => ($page == 'files')?'uk-active':'none',
				'system' => 0,
				'children' => array(
					array(
						'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $this->current_language . '/files/files/',
						'name' => "Files",
						'state' => ($subpage == 'files')?'uk-active':'none',
						'system' => 0
					),
					array(
						'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $this->current_language . '/files/images/',
						'name' => "Images",
						'state' => ($subpage == 'images')?'uk-active':'none',
						'system' => 0
					),
					array(
						'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $this->current_language . '/files/media/',
						'name' => "Media",
						'state' => ($subpage == 'media')?'uk-active':'none',
						'system' => 0
					)
				),
			);
		}
		if (isset($this->parameters['modules'][WSR_FORMS])) {
			$formsd = MyActiveRecord::FindAll('formsdefinitions', "language = '{$this->current_language}'");
			$forms = array();
			$forms[] = array(
				'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $this->current_language . '/forms/',
				'name' => WSDTranslations::getLabel('SYSTEM_MENU_FORMS_HANDLE'),
				'state' => ($page == "forms" && $subpage == '')?'uk-active':'none',
				'system' => 0
			);
			foreach($formsd as $form) {
				$forms[] = array(
					'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $this->current_language . '/forms/' . $form->id,
					'name' => $form->title,
					'state' => ($subpage == "{$form->id}")?'uk-active':'none',
					'system' => 0
				);
			}
			$menu[] = array(
				'path' => '#',
				'name' => WSDTranslations::getLabel('SYSTEM_MENU_FORMS'),
				'icon' => "icon-check",
				'state' => ($page == 'forms')?'uk-active':'none',
				'system' => 0,
				'children' => $forms
			);
		}
		if (isset($this->parameters['modules'][WSR_NEWSLETTERS])) {
			$menu[] = array(
				'path' => "#",
				'name' => WSDTranslations::getLabel('SYSTEM_MENU_NEWSLETTERS'),
				'icon' => "icon-envelope-alt",
				'state' => ($page == 'newsletters')?'uk-active':'none',
				'system' => 0,
				'children' => array(
					array(
						'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $this->current_language . '/newsletters',
						'name' => WSDTranslations::getLabel('SYSTEM_MENU_NEWSLETTERS_HANDLE'),
						'system' => 0,
						'state' => ($page == 'newsletters')?'uk-active':'none',
					)
				)
			);
		}
		if (isset($this->parameters['modules'][WSR_DATA])) {
			if ($this->_db_ok()) {
				$tables = MyActiveRecord::FindAll('tabledefinitions', null, 'system asc, porder asc, title asc');
				$allowed_tables = unserialize($user_group->datarights);
				foreach ($tables as $key => $module) {
						if (array_key_exists($module->name, $allowed_tables)) {
							if (@$this->params[1] != 'blocks') {
								$page = isset($this->params[2])?$this->params[2]:'';
								$state = ($page == $module->name)?'uk-active':'none';
								$found = false;
								foreach ($menu as $kmi => $mi) {
									if ($mi['name'] == $module->{'menu_' . $userlanguage}) {
										$found = true;
										$menu[$kmi]['children'][] = array(
											'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . ($module->monolanguage=='1'?$module->default_language:$this->current_language) . '/tables/' . $module->name,
											'name' => $module->title,
											'state' => $state,
											'system' => 0,
											'system' => ($module->system == 1)
										);
									}
								}
								if (!$found) {
									$menu[] = array(
										'path' => '#',
										'name' => $module->{'menu_' . $userlanguage},
										'state' => 'none',
										'icon' => 'icon-archive',
										'system' => 0,
										'children' => array(
											array(
												'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . ($module->monolanguage=='1'?$module->default_language:$this->current_language) . '/tables/' . $module->name,
												'name' => $module->title,
												'state' => $state,
												'system' => ($module->system == 1)
											)
										)
									);
								}
							}
						}
				}
			}
		}

		$menu[] = array(
			'path' => '#',
			'name' => "",
			'icon' => "",
			'state' => '',
			'system' => 0,
			'children' => array()
		);
	
		if (isset($this->parameters['modules'][WSR_PARAMS])) {
			$page = isset($this->params[1])?$this->params[1]:'';
			$menu[] = array(
				'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $this->current_language . '/parameters',
				'name' => "Param&egrave;tres",
				'icon' => "icon-adjust",
				'state' => ($page == 'parameters')?'uk-active':'',
				'children' => array()
			);
		}

		// loop through mainmenu to set selected states and page title properly.
		foreach($menu as &$topitem) {
			if ($topitem['state'] == 'uk-active') {
				$this->template->assign('current_short_page_title', $topitem['name']);
			}
			if (count($topitem['children']) > 0) {
				foreach($topitem['children'] as $subitem) {
					if ($subitem['state'] == 'uk-active') {
						$topitem['state'] = 'uk-active';
						$this->template->assign('current_short_page_title', $topitem['name'] . '/' . $subitem['name']);
					}
				}
			}
		}

		$this->template->assign('db_ok', $this->_db_ok());

		$this->template->assign('menu1', $menu);
		
		$this->current_page = (isset($this->params[1]))?$this->params[1]:'accueil';
		
		foreach ($menu as $menu_entry) {
			if (strstr($menu_entry['path'], $this->current_page)) {
				$this->current_page_title = $menu_entry['name'];
				break;
			}
		}
				
		$contents = '';
		
		$module = null;

		switch ($this->current_page) {
			case 'accueil':
				include('Dashboard.class.php');
				$module = new Home($this->template, $this->language, $this->current_language, $this->params, $this->parameters, $this->auth);
			break;
			case 'content':
				include('Content.class.php');
				$module = new Content($this->template, $this->language, $this->current_language, $this->params, $this->parameters, $this->auth, $this->auth);
			break;
			case 'newsletters':
				include('Newsletters.class.php');
				$module = new Newsletters($this->template, $this->language, $this->current_language, $this->params, $this->parameters, $this->auth);
			break;
			case 'parameters':
				include('Parameters.class.php');
				$module = new Parameters($this->template, $this->language, $this->current_language, $this->params, $this->parameters, $this->auth);
			break;
			case 'files':
				include('Files.class.php');
				$module = new Files($this->template, $this->language, $this->current_language, $this->params, $this->parameters, $this->auth);
			break;
			case 'forms':
				include('Forms.class.php');
				$module = new Forms($this->template, $this->language, $this->current_language, $this->params, $this->parameters, $this->auth);
			break;
			case 'help':
				include('Help.class.php');
				$module = new Help($this->template, $this->language, $this->current_language, $this->params, $this->parameters, $this->auth);
			break;
			default:
				include('Tables.class.php');
				$module = new Tables($this->template, $this->language, $this->current_language, $this->params, $this->parameters, $this->auth);
			break;
		}
		$contents = $module->_index();

		$this->template->assign('current_page', humanize($this->current_page));
//		$this->template->assign('current_short_page_title', $module->module_name);

		$this->template->assign('module_name', $module->module_name);
		$this->template->assign('module_version', $module->module_version);

		// Get the site menu items and load it in a JS variable for access accross all modules
		//d($this->get_site_menus(0, $this->current_language));
		$this->template->assign('site_menus', json_encode($this->get_site_menus(0, $this->current_language)));
		
		$this->template->assign('contents', $contents);
		
		if (!$contents) {
			die('404');
		}
		

		// Fill language independent information
		$this->template->assign('html_root', 	$this->config->get(DEPLOYMENT, 'config', 'html_root'));
		$this->template->assign('html_lib', 	$this->config->get(DEPLOYMENT, 'config', 'html_lib'));
		$this->template->assign('html_app', 	$this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . rtrim($this->application_folder, '/') );
		$this->template->assign('language', 	$this->language);
		
		$this->template->assign('author', 			$this->config->get('author'));
		$this->template->assign('release', 			$this->config->get('release'));
		$this->template->assign('version', 			$this->config->get('version'));
		$this->template->assign('username', 			$user->title);
		$this->template->assign('username_id', 		$user->id);

		$db = $this->app_config->get($this->app_config->get('deployment'));

		$this->template->assign('database', 			$db['db_name']);
		$this->template->assign('deployment', 			strtoupper($this->app_config->get('deployment')));
		
		$this->template->display( 'index-home-' . $this->language . '.tpl', false );
	}
	
	function logout() {
		WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'User logged out.' );
		$this->auth->logout();
		$this->auth->start();
		die();
	}
	
	function redirect($error) {
		echo $error;
		die();
	}
	
	function _db_ok() {
		return (is_resource(MyActiveRecord::Connection()));
	}
	
	function get_help($path) {
		$apath = '/' . implode('/',$path);
		$text = null;
		switch ($apath) {
			case '/':
				$record = MyActiveRecord::FindFirst('notes', "path = '__ACCUEIL__'");
				$text_id = $record->id;
				$text = $record->content;
			break;
			case '/fr/content':
				$record = MyActiveRecord::FindFirst('notes', "path = '__CONTENU__'");
				$text_id = $record->id;
				$text = $record->content;
			break;
		}

		if (!$text) {
			foreach(MyActiveRecord::FindAll('notes') as $note) {
				if ($note->path != '') {
					if (stristr($apath, $note->path) !== FALSE) {
						$text_id = $note->id;
						$text = $note->content;
					}
				}
			}
		}
		
		if ($text) {
			return $text;
		}
		else {
			return '';
		}
	}
	
	function _auth_display_login() {
		$reason = end($this->params);
		if ($reason == 'timeout') {
			$this->auth_status = -1;
		}
		$app_config = new WSConfig;
		$app_config->load(WS_ADMINISTERED_APPLICATION_FOLDER . '/config/');

		$status = '';
		if ($app_config->get('maintenance')) {
			$this->template->assign('maintenance_style', "style='background: none; background-color: salmon;'");
			$status = "Site désactivé.";
			$this->template->assign('status', $status);
		}

		$this->template->assign('html_root', 	$this->config->get(DEPLOYMENT, 'config', 'html_root'));
		$this->template->assign('html_lib', 	$this->config->get(DEPLOYMENT, 'config', 'html_lib'));
		$this->template->assign('html_app', 	$this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . rtrim($this->application_folder, '/') );
		$this->template->assign('language', 	$this->language);
		
		$this->template->assign('company',			$app_config->get('company'));

		$this->template->assign('author', 			$this->config->get('author'));
		$this->template->assign('release', 			$this->config->get('release'));
		$this->template->assign('version', 			$this->config->get('version'));

		$this->template->assign('current_page_title', 'Connection');
		$this->template->assign('current_short_page_title', 'Connection');
		$this->template->assign('current_page', 'login');
		
		$this->template->assign('username', $this->auth_username);
		if ($this->auth_status == AUTH_IDLED) {
			$this->template->assign('status', WSDTranslations::getLabel('SYSTEM_EXPIRED_SESSION') . $status);
			WSLog::admin( WS_WARNING, 'NO_USER', 0, 'Attempt to connect to a session expired CMS: ' . $this->auth_username );
		}
		if ($this->auth_status == AUTH_WRONG_LOGIN) {
			$this->template->assign('status', WSDTranslations::getLabel('SYSTEM_WRONG_CREDENTIALS') . $status);
			WSLog::admin( WS_WARNING, 'NO_USER', 0, 'User failed login attempt - login provided: ' . $this->auth_username );
		}

	    $this->template->display('login-' . $this->language . '.tpl');
	    die();
	}

	function get_site_menus($parent = 0, $language) {
		$records = MyActiveRecord::FindAll('contents', "contents_id = " . (int) $parent . " and language ='" . $language . "'", 'porder asc');
		
		$ar = array();

		foreach ($records as $item) {
			$children = $this->get_site_menus($item->id, $language);
			$path = $item->fullpath;
			if ($item->fullpath != '') {
				$ar[$path] = $path . ' (' . html_entity_decode($item->title, ENT_NOQUOTES, "UTF-8") . ')';
			}
			if (is_array($children)) {
				$ar = $ar + $children;
			}
		}
		return $ar;
	}


}


?>