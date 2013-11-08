<?php

/**
 * Site
 *
 * @package	application
 * @subpackage	site
 * @category	core
 * @author	David Grevink
 * @link	http://websitesapp.com/
 */

WSLoader::load_dictionary('languages');
WSLoader::load_helper('html');
WSLoader::load_helper('system');
WSLoader::load_base('menu');
WSLoader::load_base('templates');
WSLoader::load_base('log');

class Site extends WSController {

	var $language = DEFAULT_LANGUAGE;
	var $page	  = null;

	var $infos 	  = null;

	var $menu_id = null;
	var $menu    = null;

	var $need_login = false;

	function Site() {
		parent::WSController();

		$this->language = $this->_get_language();								// Current page language
		$this->menu		= new WSMenu($this->language);
		$this->page		= $this->_get_page();									// Current page path name (short version)

		$this->menu_id  = $this->menu->get_id($this->page, $this->language);	// Current page's menu id
		$this->infos    = $this->menu->get($this->menu_id);						// Current menu data for this page

		$this->infos['REQUEST_URI'] = $_SERVER['REQUEST_URI'];

		$this->template = new Template;
		$this->template->caching = $this->config->get('caching', 'system') && ($this->menu->menu[$this->menu_id]['cached'] == 1);

		$this->template->cache_lifetime = $this->config->get('cache_lifetime', 'system');;
		$this->cacheKey = md5($_SERVER['REQUEST_URI']);
	}

	function index() {
		// Logout if necessary
		if (isset($_POST['logout'])) {
			$this->auth->logout();
			$this->_auth_display_login();
		}

		if ($this->auth->getAuth()) {
			if (isset($_POST['username'])) {
				//	d($this->auth->getStatus());
			}
		}

		// Check if the current user has access to this page, and if not, redirect
		$this->_check_access();

		// Set locale according to the page language
		switch ($this->language) {
			case 'fr':
				setlocale(LC_ALL, array('fr_FR.utf8', 'fr_FR'));
				break;
			default:
		}

		// Get the page layout
		$layout = $this->template->getLayout($this->language, $this->menu->get_layout_id($this->menu_id));
		if (!$layout) {
			$this->redirect('NOLAYOUT');
			die();
		}

		// If the current page is NOT cached, process contents
		if (!$this->template->isCached(WS_APPLICATION_FOLDER . '/views/layouts/' . $layout['filename'], $this->cacheKey)) {

			// Set language cookie if not set
			if (!isset($_COOKIE['websites_language'])) {
				setcookie('websites_language', $this->language, time()+60*60*24*30,'/');
			}
			else {
				// Redirect if we came from another site and the language cookie is set
				if (count($this->params) == 0) {
					header('Location: /' . $_COOKIE['websites_language'] . '/');
					die();
				}
				// If we are inside an existing site page, do not redirect
				else {
					if ($this->language != $_COOKIE['websites_language']) {
						setcookie('websites_language', $this->language, time()+60*60*24*30,'/');
					}
				}
			}

			if ($this->template->caching) {
				header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + $this->template->cache_lifetime));
				header('Pragma: cache');
				header('Cache-Control: max-age=' . $this->template->cache_lifetime . ', must-revalidate');
			}

			// System paths
			$this->template->assign('ws_root', 			$this->config->get(DEPLOYMENT, 'config', 'html_root'));
			$this->template->assign('ws_app', 			$this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . rtrim($this->application_folder, '/') );
			$this->template->assign('ws_app_lib', 		$this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . rtrim($this->application_folder, '/') . '/lib' );
			$this->template->assign('ws_lib', 			$this->config->get(DEPLOYMENT, 'config', 'html_lib'));

			// Current page
			$cp = explode('/', $this->page);
			$this->template->assign('ws_cpath', 		'ws-' . implode('-', $cp));
			$this->template->assign('ws_cparent',		'ws-' . $cp[0]);

			// Titles
			$this->template->assign('ws_title',			$this->menu->menu[$this->menu_id]['label']);
			$this->template->assign('ws_title_short',	$this->menu->menu[$this->menu_id]['slabel']);
			if ($this->menu->menu[$this->menu_id]['parent'] != 0) {
				$this->template->assign('ws_title_parent',	$this->menu->menu[$this->menu->menu[$this->menu_id]['parent']]['label']);
				$this->template->assign('ws_title_parent_link',	'/' . $this->language . '/' . $this->menu->menu[$this->menu->menu[$this->menu_id]['parent']]['path']);
			}
			else {
				$this->template->assign('ws_title_parent',	$this->menu->menu[$this->menu_id]['label']);
				$this->template->assign('ws_title_parent_link',	'/' . $this->language . '/' . $this->menu->menu[$this->menu_id]['path']);
			}
			$this->template->assign('ws_company',			$this->config->get('company'));

			// Meta
			$this->template->assign('ws_description', 		$this->menu->get_seo('description', $this->menu_id));
			$this->template->assign('ws_keywords', 			$this->menu->get_seo('keywords', $this->menu_id));
			$this->template->assign('ws_author', 			$this->config->get('author'));
			$this->template->assign('ws_release', 			$this->config->get('release'));
			$this->template->assign('ws_version', 			$this->config->get('version'));

			// Current page language
			$this->template->assign('ws_language', 			$this->language);

			// Fill out the language selector with all languages configured for this site
			$language_list = array();
			foreach($this->config->get('languages') as $item) {
				$cp = '/' . $this->config->get(DEPLOYMENT, 'config', 'html_root') . $item . '/';
				$field_name = 'contents_' . $item . '_id';

				if ($item != $this->language) {
					if ($this->menu->dbmenu[$this->menu_id]->{$field_name} != -1) {
						$page = MyActiveRecord::FindById('contents', $this->menu->dbmenu[$this->menu_id]->{$field_name});
						if ($page) {
							$cp = $page->fullpath;
						}
					}
				}
				$languages[] = array(
					'code'  => $item,
					'label' => WSDLanguages::getLanguageName($item),
					'state' => ($this->language == $item?'selected':''),
					'path'  => $cp
				);

			}
			$this->template->assign('ws_language_bar', $languages);

			// Contextual, 1-level menu
			$this->template->assign("ws_context_menu_1", $this->menu->get_children_flat_menu($this->menu_id, 1));
			$this->template->assign("ws_context_menu_2", $this->menu->get_children_flat_menu($this->menu_id, 2));
			$this->template->assign("ws_context_menu_3", $this->menu->get_children_flat_menu($this->menu_id, 3));
			$this->template->assign("ws_context_menu_4", $this->menu->get_children_flat_menu($this->menu_id, 4));

			// Menu Sets 1/2/3/4, full menu
			$this->template->assign("ws_menu_1", $this->menu->get_menu(0, $this->menu_id, 1));
			$this->template->assign("ws_menu_2", $this->menu->get_menu(0, $this->menu_id, 2));
			$this->template->assign("ws_menu_3", $this->menu->get_menu(0, $this->menu_id, 3));
			$this->template->assign("ws_menu_4", $this->menu->get_menu(0, $this->menu_id, 4));

			// Analytics ( hide GA code if deployment not in production )
			$this->template->assign('ws_analytics', ($this->config->get('deployment') != 'production')?'':base64_decode($this->config->get('uacct')));

			// Custom Headers
			// 1 - add the ones defined in the CMS parameters
			// 2 - add the automatic ones: RSS
			$code = base64_decode($this->config->get('headers'));
			$code .= $this->_get_feed_links();
			if ($this->config->get('deployment') != 'production') {
				$code .= "\t<meta name='robots' content='noindex' />";
			}
			$this->template->assign('ws_headers', $code);

			// Deployment ( to show or hide beta sign )
			$code = "<div id='ws-beta'>";
			$code .= "<div id='ws-edit-page'><a href='/admin/fr/content/" . $this->menu_id . "' target='_blank'>Modifier...</a></div>";
			$code .= "&nbsp;</div>";
			$this->template->assign('ws_beta', ($this->config->get('deployment') == 'production')?'':$code);

			// Fillout all variable settings
			foreach(MyActiveRecord::FindAll('settings', "language = '{$this->language}'") as $setting) {
				$value = $setting->value;
				if (valid_email($value)) $value = 'mailto:' . $value;
				// take into account that titleseo are unique accross languages
				$setting_name = substr($setting->titleseo, 0, (strstr($setting->titleseo, '-')==0)?strlen($setting->titleseo):strstr($setting->titleseo, '-')-1);
				$this->template->assign('setting_' . $setting_name, $value);
			}

			// According to the current layout, loop over all defined placeholders ( placeholder_[1-9] )
			// and fill them according to their type
			foreach($layout['placeholders'] as $placeholder ) {
				// $placeholder is the placeholder's name defined in the layout
				$this->template->assign($placeholder, $this->_handle_placeholder($placeholder));
			}

			// Fill all fixed placeholders ( fixed_* ) with blocs
			$blocks = MyActiveRecord::FindAll('blocks', "language = '" . $this->language . "' and position not like ''");
			foreach($blocks as $block) {
				$this->template->assign('placeholder_' . $block->position, $block->content);
			}

			// Add all client-specific site patches
			$this->_client_specific();

			// Activate compressor smarty plugins if set
			if ( $this->config->get('speedup', 'system') != 0 ) {
				$this->template->load_filter('output','join_javascript');
				// CSS Has still some bugs :(
				// $this->template->load_filter('output','join_css');
				$this->template->load_filter('output','trimwhitespace');
			}

		}

		// Increase page hit counter
		$this->menu->hit($this->menu_id);

		// Display generated page according to the page's layout
		$this->template->display( WS_APPLICATION_FOLDER . '/views/layouts/' . $layout['filename'], $this->cacheKey );
// 		$this->template->display( WS_APPLICATION_FOLDER . '/views/layouts/' . $layout['filename'], false );
	}

	function redirect($code) {
		switch ($code) {
			case '404':
				WSLog::app( WS_WARNING, 'Site.class', 404, $_SERVER['REQUEST_URI'] );
				// Find the 404 page if it exists
				$page = null;
				foreach($this->menu->menu as $key => $menu) {
					if ( ($menu['hidden'] == 3) && ($menu['language'] == $this->language) ) {
						$id = $key;
						$page = $this->menu->get( $key );
						$page = '/' . $this->language . $page['path'];
						break;
					}
				}
				header("HTTP/1.0 404 Not Found");
				if (!$page) {
					echo '404 - Page not found';
				}
				else {
					header('Location: ' . $page);
				}
				die();
			break;
			case '403': // Page forbidden access
				// Find the 403 page if it exists
				WSLog::app( WS_WARNING, 'Site.class', 403, $_SERVER['REQUEST_URI'] );
				$page = null;
				foreach($this->menu->menu as $key => $menu) {
					if ( ($menu['hidden'] == 5) && ($menu['language'] == $this->language) ) {
						$id = $key;
						$page = $this->menu->get( $key );
						$page = $page['path'];
						$page = substr($page, 1, strlen($page));
						break;
					}
				}
				//				header("HTTP/1.0 403 Unautorized");
				if (!$page) {
					echo '401 - No Login Page';
					die();
				}
				else {
						$this->menu_id  = $this->menu->get_id($page, $this->language);
						$this->infos    = $this->menu->get($this->menu_id);

						$this->infos['REQUEST_URI'] = $_SERVER['REQUEST_URI'];

						$this->template = new Template;
						$this->template->caching = $this->config->get('caching', 'system') && ($this->menu->menu[$this->menu_id]['cached'] == 1);
						$this->template->cache_lifetime = $this->config->get('cache_lifetime', 'system');;
						$this->cacheKey = md5($_SERVER['REQUEST_URI']);
				}
			break;
			default:
				WSLog::app( WS_WARNING, 'Site.class', $code, $_SERVER['REQUEST_URI'] );
				$message = 'Error code ' . $code . " on content /" . $this->language . $this->page . " (<i>" . $this->menu->menu[$this->menu_id]['label'] . "</i> - " . $this->menu_id . ")";
				global $system;
				$system->error_page(WS_FATAL, $message);
				die();
			break;
		}
	}

	function _get_language() {
		// Get the language we want to display
		$language = (!isset($this->params[0]))                 			? DEFAULT_LANGUAGE : strtolower($this->params[0]);
		$language = ($language == "")                            		? DEFAULT_LANGUAGE : $language;
		$language = (!in_array($language, $this->config->get('languages')))  ? null             : $language;
		return $language;
	}

	function _get_page() {
		$page = null;
		if ($this->language != null) {
			$default_page = $this->menu->get($this->menu->default_page_id);
			$default_page = @$default_page['path'][$this->language];
			$page = (!isset($this->params[1]))              		? $default_page : strtolower(implode('/', array_slice($this->params, 1)));
			$page = ($page == "")                   	    		? $default_page : $page;
			if (isset($this->params[count($this->params)-1])) {
				if ( (strstr($this->params[count($this->params)-1], '+')) || (strstr($this->params[count($this->params)-1], '!')) ) {
					$page = strtolower(implode('/', array_slice($this->params, 1, count($this->params)-2)));
				}
				if ($page == '') { $page = $default_page; }
			}
			$page = ($this->menu->get_id($page, $this->language))	? $page : null;
		}

		// Redirect if page is not found
		if ( ($this->language == null) || ($page == null) ) {
			if (MyActiveRecord::Count('contents') == 0) {
				$message = 'No page defined in website !';
				global $system;
				$system->error_page(WS_FATAL, $message);
				die();
			}
			$this->redirect(404);
		}
		return $page;
	}

	function _check_access() {
		$access = false;
		if (in_array('-1', $this->infos['access'])) {
			$access = true;
		}
		else {
			if (isset($_SESSION[$this->config->get('security_session', 'system')]['username'])) {
				if ($this->auth->session['challengekey'] == $_SESSION[$this->config->get('security_session', 'system')]['challengekey']) {
					$user = MyActiveRecord::FindFirst('users', "username = '" . $_SESSION[$this->config->get('security_session', 'system')]['username'] . "'");
					$group = $user->find_parent('groups');
					$access = (in_array($group->id, $this->infos['access']));
				}
			}
		}
		if (!$access) {
			$this->redirect(403);
		}
	}

	// Determines the placeholder type and returns the corresponding code	
	function _handle_placeholder($placeholder) {
		$data = '';
		$placeholder_params = explode('-',$this->menu->dbmenu[$this->menu_id]->$placeholder);
		$placeholder_type = $placeholder_params[0];
		$placeholder_value = '';
		$placeholder_param = $this->menu->dbmenu[$this->menu_id]->{$placeholder . '_param'};
		if (count($placeholder_params) > 1) {
			$placeholder_value = $placeholder_params[1];
		}
		switch ($placeholder_type) {
			case 'content':			# This is the page's content if defined
				$data = $this->_get_content($this->menu_id, $placeholder_value);
			break;
			case 'block':			# This is the page's content if defined
				$data = $this->_get_block($placeholder_value);
			break;
			case 'ad':			# This is a clickable ad
				$data = $this->_get_ad($placeholder_value);
			break;
			case 'widget':
				$data = $this->_get_widget($placeholder_value, $placeholder_param);
			break;
			case 'form':
				$data = $this->_get_form($placeholder_value);
			break;
			case 'empty':
				$data = '';
			break;
			default:
				$data = "<div class='debug'>+++INVALID_PLACEHOLDER_TYPE: " . $this->menu->dbmenu[$this->menu_id]->$placeholder . "+++</div>";
			break;
		}
		return $data;
	}

	// Returns the page content
	function _get_content($id, $content_id) {
		$text = "<div class='ws-debug'>+++CONTENT_NOT_FOUND+++</div>";
		// If content_id is not set, set it to 1
		if ($content_id == '') $content_id = 1;
		$content = $this->menu->dbmenu[$id]->{'content_'.$content_id};
		if (trim($content) != '') {
			$text = $content;
		}
		$text = encrypt_mailto($text);
		$text = encrypt_mail($text);
		return $text;
	}

	// Return the specified block
	function _get_block($id) {
		$text = "<div class='ws-debug'>+++BLOCK_NOT_FOUND+++</div>";
		$record = MyActiveRecord::FindById('blocks', $id);
		if ($record != null) {
			$text = encrypt_mailto($record->content);
			$text = encrypt_mail($text);
		}
		return $text;
	}

	// Returns the specified ad
	function _get_ad($id) {
		$data = "<div class='ws-debug'>+++AD_NOT_FOUND+++</div>";
		$record = MyActiveRecord::FindById('banners', $id);
		if ($record != null) {
			$data = "<div class='ad'><a href='" . HTML_ROOT . "/ads/redirect/" . $record->id . "' title='" . strip_tags($record->content) . "' target='_new'><img src='" . HTML_ROOT . strip_tags($record->file) . "' alt='" . $record->content . "'/></a></div>";
			if ( (!empty($record->imageback)) && (!empty($record->backgroundcolor)) ) {
				$this->template->assign('ws_ad_styling', "	background: #" . $record->backgroundcolor . " url(" . $record->imageback . ") no-repeat center top;");
			}
		}
		return $data;
	}

	// Returns the specified widget
	function _get_widget($id, $param) {
		$data = "<div class='ws-debug'>+++WIDGET_NOT_FOUND: " . $id . "+++</div>";
		if (file_exists(WS_APPLICATION_FOLDER . '/controllers/' . $id)) {
			$content = file_get_contents(WS_APPLICATION_FOLDER . '/controllers/' . $id);
			$widget_params = array();
			$widget_params['id']		= basename($id);
			$widget_params['rname']		= widget_get_element('RNAME', $content);
			$widget_params['path']		= widget_get_element('PATH', $content);
			$widget_params['note']		= widget_get_element('NOTE', $content);
			$widget_params['version']	= widget_get_element('VERSION', $content);
			$widget_params['active']	= (widget_get_element('ACTIVE', $content) == '')?false:true;
			$widget_params['language']  = $this->language;
			$parts = explode('.', $id);
			$widget_params['name']      = $parts[0];
			$widget_params['param']     = $param;
			$widget_params['auth']      = &$this->auth;
			$widget_params['infos']     = &$this->infos;
			$widget_params['template']  = &$this->template;
			$widget_params['url']  		= $this->params;
			$lastparam = end($this->params);
			$widget_params['urlparam']	= ($lastparam[0]=='+'?preg_split("~\+~", $lastparam, -1, PREG_SPLIT_NO_EMPTY):false);

			require_once(WS_APPLICATION_FOLDER . '/controllers/' . $id);

			$widget = new $widget_params['name'];
			$data = $widget->index($widget_params);
			if ( ($data == '404') || ($data == '403') ) {
				$this->redirect($data);
			}
		}
		return $data;
	}

	// Returns the specified form	
	function _get_form($id) {
		require_once (WS_APPLICATION_FOLDER . '/controllers/Forms.class.php');
		$forms = new Forms;
		return $forms->display($id);
	}

	// Sets need_login to true if a login window should appear.
	// Typically, this window should be a widget
	function _auth_display_login() {
		$this->need_login = true;
	}

	// This gives out a XML Sitemap to use with Google Webmaster tools.
	// Link http://[[SITENAME]]/Site/map
	// Specs: https://www.google.com/webmasters/tools/docs/en/protocol.html
	function map() {

		$records = MyActiveRecord::FindAll('contents');

		$code = array();
		$code[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$code[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$code[] = '  <url>';
		$code[] = '    <loc>http://' . $_SERVER['SERVER_NAME'] . '/</loc>';
		//		$code[] = '    <lastmod>2005-01-01</lastmod>';
		//		$code[] = '    <changefreq>monthly</changefreq>';
		$code[] = '    <priority>0.8</priority>';
		$code[] = '  </url>';

		$language = 'fr';

		foreach($this->config->get('languages') as $language) {
			$menu		= new WSMenu($language);

			$code[] = '  <url>';
			$code[] = '    <loc>http://' . $_SERVER['SERVER_NAME'] . '/' . $language . '/</loc>';
			//			$code[] = '    <lastmod>2005-01-01</lastmod>';
			//			$code[] = '    <changefreq>monthly</changefreq>';
			$code[] = '    <priority>0.7</priority>';
			$code[] = '  </url>';
			foreach ($menu->menu as $id => $entry) {
				$code[] = '  <url>';
				$code[] = '    <loc>http://' . $_SERVER['SERVER_NAME'] . '/' . $language . $entry['path'] . '/</loc>';
				$code[] = '    <lastmod>' . substr($records[$id]->modify_date, 0, 10) . '</lastmod>';
				//				$code[] = '    <changefreq>monthly</changefreq>';
				//				$code[] = '    <priority>0.8</priority>';
				$code[] = '  </url>';
			}

		}

		$code[] = '</urlset>';

		echo implode("\n", $code);
	}

	function _get_feed_links() {
		$code = '';
		$stables = MyActiveRecord::FindAll('tabledefinitions', "rss = 1");
		foreach ($stables as $table) {
			$code .= "<link rel='alternate' type='application/rss+xml' title='" . $table->title . "' href='http://" . $_SERVER['HTTP_HOST'] . "/feeds/get/" . $this->language . "/" . $table->id . "' />\n";
		}
		return $code;
	}

	// This is a client-specific function which gets always called.
	function _client_specific() {
		$file = WS_APPLICATION_FOLDER . '/controllers/Site_client_specific.php';
		if ( file_exists($file) ) {
			include ($file);
		}
	}

}