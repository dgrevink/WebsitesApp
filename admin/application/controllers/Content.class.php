<?php

WSLoader::load_base('log');
WSLoader::load_base('history');
WSLoader::load_base('metadata');
WSLoader::load_helper('encoding');
WSLoader::load_base('templates');
WSLoader::load_helper('file');
WSLoader::load_helper('forms-advanced-uikit');
WSLoader::load_helper('system');

$userlanguage = '';

/**
 *  Content Manager
 *
 *  @package admin
 *
 */
class Content extends WSController {

	public $module_name = 'Content';
	public $module_version = '2.0';

	private $page_status_select = array(
		'fr' => array(
			0 => 'Publi&eacute;',
			2 => 'Cach&eacute;',
			1 => 'Brouillon',
			3 => 'Page 404 (Introuvable)',
	//		4 => 'Page de Login - Erreur',
			5 => "Page de Login"
		),
		'en' => array(
			0 => 'Published',
			2 => 'Hidden',
			1 => 'Draft',
			3 => '404 Page (Not found)',
	//		4 => 'Page de Login - Erreur',
			5 => "Login page"
		)
	);
	
	private $page_status_select_icons = array(
		0 => 'published.png',
		2 => 'hidden.png',
		1 => 'editing.png',
		3 => '404.png',
//		4 => 'login-bad.png',
		5 => "login.png"
	);
	
	private $access_levels = null;


	function Content($smarty=null, $language=null, $current_language=null, $params=null, $parameters=null, &$auth=null) {
		parent::WSController();

		$this->smarty 			= (isset($this->smarty)				? $this->smarty				: null);
		$this->language 		= (isset($this->language)			? $this->language			: null);
		$this->current_language = (isset($this->current_language)	? $this->current_language	: null);
		$this->params 			= (isset($this->params)				? $this->params				: null);
		$this->parameters 		= (isset($this->parameters)			? $this->parameters			: null);
		$this->auth 			= (isset($this->auth)				? $this->auth				: null);
		
		$this->smarty 			= ($smarty==null			? $this->smarty				: $smarty);
		$this->language 		= ($language==null			? $this->language			: $language);
		$this->current_language = ($current_language==null	? $this->current_language	: $current_language);
		$this->params 			= ($params==null			? $this->params				: $params);
		$this->parameters 		= ($parameters==null		? $this->parameters			: $parameters);
		$this->auth				= ($auth==null				? $this->auth				: $auth);
	}

	function _index() {
		if (!$this->_check_rights(WSR_CONTENTS)) {
			return false;
		}

		global $userlanguage;

		$smarty_contents = new Template;

		$smarty_contents->assign('current_language', $this->current_language);

		# Get available languages in app	
		$config = new WSConfig;
		$config->load( dirname(__FILE__) . '/../../../application/config/');
		$languages = array();
		foreach($config->get('languages') as $language) {
			$languages[] = array(
				'code' => $language,
				'label' => WSDLanguages::getLanguageName($language),
				'selected' => ($language == $this->current_language)?'selected':''
			);
		}
		$this->languages = $languages;
		
		if (!isset($this->params[2]) || (!is_numeric($this->params[2]))) {
			$smarty_contents->assign('listing_type', 'list');
			$smarty_contents->assign('menu_code', $this->getmenu());
			$smarty_contents->assign('buttons_code', $this->getmenubuttons());
			$smarty_contents->assign('page_total', MyActiveRecord::Count('contents', "language = '" . $this->current_language . "'"));
		}
		else {
			$smarty_contents->assign('listing_type', 'detail');
			$id = $this->params[2];
			$item = MyActiveRecord::FindById('contents', $id);
			
			$smarty_contents->assign('titledisplay',$item->title);
				
			$smarty_contents->assign('id', 				generate_hidden('id', $id));
			$smarty_contents->assign('recordid', 		$id);
			$smarty_contents->assign('language', 		generate_hidden('language', $this->current_language));
			$smarty_contents->assign('title', 			generate_input_text( 'title', WSDTranslations::getLabel('CONTENT_TITLE'), $item->title, "", false, WSDTranslations::getLabel('CONTENT_TITLE_DESCRIPTION') ));
			$smarty_contents->assign('titleshort', 		generate_input_text( 'titleshort', WSDTranslations::getLabel('CONTENT_TITLESHORT'), $item->titleshort, "", false, WSDTranslations::getLabel('CONTENT_TITLESHORT_DESCRIPTION') ));
			$smarty_contents->assign('path', 			generate_input_text( 'path', $this->_get_path($item->id, $this->current_language, true), $item->path, "", false, WSDTranslations::getLabel('CONTENT_PATH_DESCRIPTION') ));
			$smarty_contents->assign('hidden', 			generate_select( 'hidden', WSDTranslations::getLabel('CONTENT_TYPE'), $this->page_status_select[$userlanguage], $item->hidden, false, WSDTranslations::getLabel('CONTENT_TYPE_DESCRIPTION') ) );
			$smarty_contents->assign('cached', 			generate_input_checkbox( 'cached', WSDTranslations::getLabel('CONTENT_CACHED'), $item->cached, WSDTranslations::getLabel('CONTENT_CACHED_DESCRIPTION') ));


			$menus = explode(',', $item->menus);
			$all_menus = array(
				1 => $config->get('menu_name_1'),
				2 => $config->get('menu_name_2'),
				3 => $config->get('menu_name_3'),
				4 => $config->get('menu_name_4')
			);
			$smarty_contents->assign('menus', 			generate_select( 'menus', WSDTranslations::getLabel('CONTENT_MENUS'), $all_menus, $menus, false, WSDTranslations::getLabel('CONTENT_MENUS_DESCRIPTION'), true ) );

			$smarty_contents->assign('sitemap', 		generate_input_checkbox( 'sitemap', WSDTranslations::getLabel('CONTENT_SITEMAP'), $item->sitemap, WSDTranslations::getLabel('CONTENT_SITEMAP_DESCRIPTION') ));

			$smarty_contents->assign('params', 			generate_input_text( 'params', WSDTranslations::getLabel('CONTENT_PARAMS'), $item->params, "", false, WSDTranslations::getLabel('CONTENT_PARAMS_DESCRIPTION') ));

			$access = explode(',', str_replace(' ', '', $item->access));
			$groups = array();
			foreach(MyActiveRecord::FindAllMD('groups', 'id, title, language') as $group) {
				if ($group->id == '-1') {
					$group->title = '** ' . $group->title . ' **';
				}
				$groups[$group->id] = $group->title;
			}
			asort($groups);
			$smarty_contents->assign('access', 			generate_select( 'access', WSDTranslations::getLabel('CONTENT_ACCESS'), $groups, $access, false, WSDTranslations::getLabel('CONTENT_ACCESS'), true ) );
			
			foreach ($this->languages as $lang) {
				if ($lang['code'] != $this->current_language) {
					$field_name = 'contents_' . $lang['code'] . '_id';
					$current_language_pages = array('-1' => WSDTranslations::getLabel('CONTENT_LANGUAGE_PAGES')) + $this->getMenuItemsAsArray(0, $lang['code']);
					$smarty_contents->assign('language_page_' . $lang['code'],  generate_select( $field_name, 'Page (' . $lang['label'] . ')', $current_language_pages, $item->{$field_name}, false, WSDTranslations::getLabel('CONTENT_LANGUAGE_PAGES_LEFT') . $lang['label'] . WSDTranslations::getLabel('CONTENT_LANGUAGE_PAGES_RIGHT') . MyActiveRecord::Count('contents', "language = '" . $lang['code'] . "'") . WSDTranslations::getLabel('CONTENT_LANGUAGE_PAGES_LAST') ) );
				}
			}

			$smarty_contents->assign('complete_path', 	$this->_get_path($item->id, $this->current_language));
			$smarty_contents->assign('content_1', 		$item->content_1);
			$smarty_contents->assign('content_2', 		$item->content_2);
			$smarty_contents->assign('content_3', 		$item->content_3);
			$smarty_contents->assign('content_4', 		$item->content_4);
			$smarty_contents->assign('content_5', 		$item->content_5);
			$smarty_contents->assign('comment', 		$item->comment);
			$smarty_contents->assign('seodescription', 	generate_input_text( 'seodescription', WSDTranslations::getLabel('CONTENT_SEODESCRIPTION'), $item->seodescription, "", false, WSDTranslations::getLabel('CONTENT_SEODESCRIPTION_DESCRIPTION') ));
			$smarty_contents->assign('seokeywords', 	generate_text_area( 'seokeywords', WSDTranslations::getLabel('CONTENT_SEOKEYWORDS'), $item->seokeywords, 8, 100, '', false, WSDTranslations::getLabel('CONTENT_SEOKEYWORDS_DESCRIPTION')  ));
			
			$creator = MyActiveRecord::FindById('users', $item->creator_id);
			if ($creator) {
				$smarty_contents->assign('creator', 		$creator->username);
			}
			$smarty_contents->assign('create_date', 	dbdate2human($item->create_date, 'd-m-Y \&\a\g\r\a\v\e\; H:i:s'));
			$modifier = MyActiveRecord::FindById('users', $item->modifier_id);
			if ($modifier) {
				$smarty_contents->assign('modifier', 		$modifier->username);
			}
			$smarty_contents->assign('modify_date', 	dbdate2human($item->modify_date, 'd-m-Y \&\a\g\r\a\v\e\; H:i:s'));

			$smarty_contents->assign('WSR_CONTENTS_ACCESS',		($this->_check_rights(WSR_CONTENTS_ACCESS)) );
			$smarty_contents->assign('WSR_CONTENTS_CONTENT',	($this->_check_rights(WSR_CONTENTS_CONTENT)) );
			$smarty_contents->assign('WSR_CONTENTS_LAYOUT',		($this->_check_rights(WSR_CONTENTS_LAYOUT)) );
			$smarty_contents->assign('WSR_CONTENTS_SEO', 		($this->_check_rights(WSR_CONTENTS_SEO)) );
			$smarty_contents->assign('WSR_CONTENTS_METADATA',	($this->_check_rights(WSR_CONTENTS_METADATA)) );
			$smarty_contents->assign('WSR_CONTENTS_VERSIONS',  ($this->_check_rights(WSR_CONTENTS_VERSIONS)) );

			// Get layouts for layout selector
			$current_page = MyActiveRecord::FindById('contents', $id);
			$template = new Template();
			$layouts = $template->getLayouts( $this->current_language );
			$smarty_contents->assign('layouts', $layouts);

			$smarty_contents->assign('current_page', $current_page);

			// list blocks
			$blocks = MyActiveRecord::FindAllMD('blocks', 'id, language, title', "language = '" . $this->current_language . "'", 'title asc');
			$smarty_contents->assign('blocks', activerecord2smartyarray($blocks));

			// ads
			$ads = MyActiveRecord::FindAllMD('banners', 'id, language, title', "language = '" . $this->current_language . "'", 'title asc');
			$smarty_contents->assign('ads', activerecord2smartyarray($ads));
		
			// forms
			$forms = MyActiveRecord::FindAllMD('formsdefinitions', 'id, language, title', "language = '" . $this->current_language . "'", 'title asc');
			$smarty_contents->assign('forms', activerecord2smartyarray($forms));

			// list widgets
			$widgets_temp = glob(WS_ADMINISTERED_APPLICATION_FOLDER . "/controllers/W*.class.php");
			$widgets = array();
			foreach ($widgets_temp as $w) {
				$content = file_get_contents($w);
				if (widget_get_element('ACTIVE', $content) != 'YES') continue;
				$widgets[] = array(
					'id'		=> basename($w),
					'rname'		=> widget_get_element('RNAME', $content),
					'name'		=> widget_get_element('NAME', $content),
					'path'		=> widget_get_element('PATH', $content),
					'note'		=> widget_get_element('NOTE', $content),
					'version'	=> widget_get_element('VERSION', $content),
					'active'	=> (widget_get_element('ACTIVE', $content) != 'YES')?false:true
				);
			}
			$smarty_contents->assign('widgets', $widgets);

		}
		
		return $smarty_contents->fetch( 'contents-index-' . $this->language . '.tpl' );
	}
	

	function getmenubuttons() {
		$code = '';
		if ($this->_check_rights(WSR_CONTENTS_ADD)) {
			$code .= "<a href='#' class='uk-button uk-button-primary disposable' onclick='javascript:addMenu(0); return false;'>Nouvelle page...</a> ";
		}
		if ($this->_check_rights(WSR_CONTENTS_ORDER)) {
			$code .= "<a href='#' class='uk-button uk-hidden-small menu-orderer'>Ordonner</a> ";
			$code .= "<a href='#' style='display: none;' class='uk-button menu-orderer-saver'>Sauver ordre</a> ";
			$code .= "<a href='#' style='display: none;' class='uk-button menu-orderer-cancel'>Annuler</a>";
		}
		return $code;
	}
	function getmenu() {
		$this->groups  = MyActiveRecord::FindAll('groups');

		$body = "<div id='menus'>";
		$body .= '<ul>';
		$body .= $this->getMenuItemsHTML();
		$body .= '<ul>';
		$body .= '</div>';
		
		return $body;
	}

	function getMenuItemsHTML($parent = 0) {
		global $userlanguage;

		$records = MyActiveRecord::FindAll('contents', "contents_id = " . (int) $parent . " and language ='" . $this->current_language . "'", 'porder asc');
		$u = MyActiveRecord::FindAll('users');
		
		if (!is_array($records)) {
			return false;
		}

		$code = '';
		
		foreach ($records as $item) {
			$children = $this->getMenuItemsHTML($item->id);
			
			$path = $this->_get_path($item->id, $this->params[0]);

			$temp = array();
			$temp['id'] = $item->id;

			$access = explode(',', str_replace(' ', '', $item->access));
			$access_display = '';
			foreach($access as $a) {
				$access_display .= @$this->groups[$a]->title . ' ';
			}

			$tempinfo = array();

			// Column 1: Page name with path
			$language_links = '';
			foreach($this->languages as $lang) {
				if($lang['code'] != $this->current_language) {
					$current_field_name = 'contents_' . $lang['code'] . '_id';
					if ($item->{$current_field_name} != '-1') {
						$language_links = "\n" . $lang['code'] . ' => ' . $this->_get_path($item->{$current_field_name}, $lang['code']);
					}
				}
			}
			$language_links = trim($language_links, "\n");
			if (!$this->_check_rights(WSR_CONTENTS_EDIT)) {
				$tempinfo[] = $item->title;
			}
			else {
				$tempinfo[] = "<a href='" . $item->id . "'>" . shorten_text($this->_beautify($item->path), 32) . "</a>&nbsp;<span class='menu-path' title='" . $language_links . "'>(" . $path . ")</span>";
			}

			// Column 2: Info
			$tempinfo[]
			            = "<div class='menu-info'>"
				        . "<img src='/admin/application/lib/images/status/" . ($this->page_status_select_icons[$item->hidden])
						. "' title='Statut: " . ($this->page_status_select[$userlanguage][$item->hidden]) 
						. "\nMenu(s): " . $item->menus 
						. "\nSitemap: " . ($item->sitemap==1?'Oui':'Non') 
						. "\nStatifi&eacute;: " . (file_exists(dirname(__FILE__) . '/../../../' . $path . '/index.html')?'Oui':'Non') 
						. "\nParams: " . $item->params
						. "\nModification: " . date($item->modify_date) 
						. "\nPar: " . @$u[$item->modifier_id]->username
						. "'/>"
		 				. (($access_display == 'Tous ')?'':" <img src='/admin/application/lib/images/status/security.png' title='Acces: " . $access_display . "'/>")
				 		. " " . $item->hits . ""
		 				. "</div>";
			//$tempinfo[] = $access_display;

			// Column 3: Commands
			if ($this->_check_rights(WSR_CONTENTS_ADD)) {
				$actions = "<div class='menu-actions'>";
				$actions .= "<img class='button-add-menu-item' onclick='javascript:addMenu(" . $item->id . ");' src='/admin/application/lib/images/icons/icon-plus.gif' />";
				$actions .= "<img class='button-delete-menu-item' onclick='javascript:deleteMenu(" . $item->id . ");' src='/admin/application/lib/images/icons/icon-remove.gif' />";
				$actions .= "<img class='button-delete-menu-item' onclick=\"javascript:showPage('" . $item->fullpath . "');\" src='/admin/application/lib/images/icons/icon-view.gif' />";
				$actions .= "</div>";
				$tempinfo[] = $actions;
			}

			// Assemble
			$temp['info'] = $tempinfo;
			
			$code .= "<li id='list_" . $item->id . "'>";
				$code .= "<div>";
					$code .= $tempinfo[0] . $tempinfo[1] . @$tempinfo[2];
				$code .= "</div>";
				if ($children != '') {
					$code .= "<ul>";
						$code .= $children;
					$code .= "</ul>";
				}
			$code .= "</li>";
			
		}
		
		return $code;
	}


	

	
	function _get_path($id, $language, $omit_last = false) {
		if (!isset($this->menurecords)) {
			$this->menurecords = MyActiveRecord::FindAll('contents');
		}
		
		$out = false;
		$path = array();
		while ($out == false) {
			$out = ($this->menurecords[$id]->contents_id == 0);
			$path[]= $this->menurecords[$id]->path;
			$id = $this->menurecords[$id]->contents_id;
		}

		$path[] = $language;
		
		$path = array_reverse($path);
		
		if ($omit_last) {
			array_pop($path);
		}
		
		return '/' . implode('/', $path) . '/';
	}
	
	function savemenu() {
		$return = array();
		if (!$this->_check_rights(WSR_CONTENTS_ORDER)) {
			echo 'KO';
			die();
		}
		else {
			$this->porder = 0;
			$porder = 1;
			$norder = explode('&',$_POST['data']);
			foreach($norder as $item) {
				list($id,$parent_id) = explode('=',$item);
				$id = preg_replace("/[^0-9]/", '', $id);
				
				$record = MyActiveRecord::FindById('contents', $id);
				if (is_numeric($parent_id)) {
					$record->contents_id = $parent_id;
				}
				else {
					$record->contents_id = 0;
				}
				$record->porder = $porder;
				$record->save();
				$record->fullpath = $this->_get_path($record->id, $record->language);
				$record->save();
				
				$porder++;			
			}
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Saved menu order.' );
			echo 'OK';
			die();
		}
	}
	
	function repositionMenuItems($array, $parent) {
		$this->porder++;
		$record = MyActiveRecord::FindById('contents', $array['id']);
		$record->contents_id = $parent;
		$record->porder = $this->porder;
		$record->save();
		$record->fullpath = $this->_get_path($record->id, $record->language);
		$record->save();
		if (isset($array['children'])) {
			foreach($array['children'] as $child) {
				$this->repositionMenuItems($child, $array['id']);
			}
		}
	}
	
	function addmenu() {

		if (!$this->_check_rights(WSR_CONTENTS_ADD)) {
			echo 'KO';
			exit();
		}
		$parent = $_POST['parent'];
		$title = $_POST['title'];
		$language = $_POST['language'];
		
		$path = normalize_string($title);

		$new = MyActiveRecord::Create('contents');
		$new->contents_id = (int) $parent;
		$new->title = htmlentities($title, ENT_QUOTES, "utf-8" );
		$new->titleshort = $new->title;
		$new->path = $path;
		$new->language = $language;
		$new->porder = 999999;

		$config = new WSConfig;
		$config->load( dirname(__FILE__) . '/../../../application/config/');

		if ($parent != 0) {
			$parent_menu = MyActiveRecord::FindById('contents', (int) $parent);
			$new->layout 				= $parent_menu->layout;
			$new->placeholder_1 		= $parent_menu->placeholder_1;
			$new->placeholder_1_param 	= $parent_menu->placeholder_1_param;
			$new->placeholder_2 		= $parent_menu->placeholder_2;
			$new->placeholder_2_param 	= $parent_menu->placeholder_2_param;
			$new->placeholder_3 		= $parent_menu->placeholder_3;
			$new->placeholder_3_param 	= $parent_menu->placeholder_3_param;
			$new->placeholder_4 		= $parent_menu->placeholder_4;
			$new->placeholder_4_param 	= $parent_menu->placeholder_4_param;
			$new->placeholder_5 		= $parent_menu->placeholder_5;
			$new->placeholder_5_param 	= $parent_menu->placeholder_5_param;
			$new->placeholder_6 		= $parent_menu->placeholder_6;
			$new->placeholder_6_param 	= $parent_menu->placeholder_6_param;
			$new->placeholder_7 		= $parent_menu->placeholder_7;
			$new->placeholder_7_param 	= $parent_menu->placeholder_7_param;
			$new->placeholder_8 		= $parent_menu->placeholder_8;
			$new->placeholder_8_param 	= $parent_menu->placeholder_8_param;
			$new->placeholder_9 		= $parent_menu->placeholder_9;
			$new->placeholder_9_param 	= $parent_menu->placeholder_9_param;
		}
		else {
			// get the content page id to be used as defaults
			$template_id = $config->get('template_page_id_' . $language);
			$template = MyActiveRecord::FindById('contents', $template_id);
			if ($template) {
				$new->content_1 = 'used ' . $template_id;
				$new->layout 				= $template->layout;
				$new->placeholder_1 		= $template->placeholder_1;
				$new->placeholder_1_param 	= $template->placeholder_1_param;
				$new->placeholder_2 		= $template->placeholder_2;
				$new->placeholder_2_param 	= $template->placeholder_2_param;
				$new->placeholder_3 		= $template->placeholder_3;
				$new->placeholder_3_param 	= $template->placeholder_3_param;
				$new->placeholder_4 		= $template->placeholder_4;
				$new->placeholder_4_param 	= $template->placeholder_4_param;
				$new->placeholder_5 		= $template->placeholder_5;
				$new->placeholder_5_param 	= $template->placeholder_5_param;
				$new->placeholder_6 		= $template->placeholder_6;
				$new->placeholder_6_param 	= $template->placeholder_6_param;
				$new->placeholder_7 		= $template->placeholder_7;
				$new->placeholder_7_param 	= $template->placeholder_7_param;
				$new->placeholder_8 		= $template->placeholder_8;
				$new->placeholder_8_param 	= $template->placeholder_8_param;
				$new->placeholder_9 		= $template->placeholder_9;
				$new->placeholder_9_param 	= $template->placeholder_9_param;
			}
			else {
				$template = new Template();
				$layouts = $template->getLayouts( $new->language );
				$first_layout = array_shift($layouts);
	
				$new->layout = basename($first_layout['filename'], '.html');
				$new->placeholder_1 = 'content';
				$new->placeholder_1_param = 'content-1';
				$new->content_1 = 'no template id found';
				$new->placeholder_2 = 'empty';
				$new->placeholder_3 = 'empty';
				$new->placeholder_4 = 'empty';
				$new->placeholder_5 = 'empty';
				$new->placeholder_6 = 'empty';
				$new->placeholder_7 = 'empty';
				$new->placeholder_8 = 'empty';
				$new->placeholder_9 = 'empty';
			}
			
		}

		$user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
		if (!$user) {
			echo 'KO';
			exit();
		}
		$new->creator_id = $user->id;
		$new->modifier_id = $user->id;
		$new->create_date = MyActiveRecord::DbDateTime();
		$new->modify_date = MyActiveRecord::DbDateTime();
		
		if (!$new->save()) {
			WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not create menu ' . $new->title . '.');
			echo 'KO';
			exit();
		}
		else {
			$new->fullpath = $this->_get_path($new->id, $new->language);
			$new->save();
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Created menu #' . $new->id . ' ( ' . $new->title . ' ) ');
			echo 'OK';
			exit();
		}
	}
	
	function deletemenu() {
		if (!$this->_check_rights(WSR_CONTENTS_ADD)) {
			echo 'KO';
			exit();
		}
		$id = $_POST['id'];
		$node = MyActiveRecord::FindById('contents', $id);
		if ($node) {
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Begin menu delete sequence, starting at node #' . $node->id . ' ( ' . $node->title . ' ) ' );
			$this->processDeleteMenu($id);
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'End menu delete sequence' );
		}
		echo 'OK';
	}
	
	function processDeleteMenu($id) {
		$record = MyActiveRecord::FindById('contents', $id);
		if ($record) {
			$children = $record->find_children('contents');
			foreach($children as $child) {
				$this->processDeleteMenu($child->id);
			}
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Deleted menu node #' . $record->id . ' ( ' . $record->title . ' ) ' );
			WSHistory::drop('contents', $record->id);
			$record->delete();
		}
	}

	function save() {
		$record = MyActiveRecord::FindById('contents', $_POST['id']);
		if (!$record) {
			echo "<span class='error'>Ce menu n'existe pas !</span>";
			exit();
		}
		
		if ($this->_check_rights(WSR_CONTENTS_ACCESS)) {
			$record->title			= htmlentities($_POST['title'],				ENT_QUOTES, "utf-8" );
			$record->titleshort		= htmlentities($_POST['titleshort'],		ENT_QUOTES, "utf-8" );
			$record->hidden = $_POST['hidden'];
			$record->access = implode(',', $_POST['access']);
			$record->menus = implode(',', $_POST['menus']);
			$record->path			= $_POST['path'];
			$record->cached			= isset($_POST['cached'])?1:0;
			$record->sitemap		= isset($_POST['sitemap'])?1:0;
			$record->params			= trim($_POST['params']);

			$record->contents_ko_id	= isset($_POST['contents_ko_id'])?$_POST['contents_ko_id']:$record->contents_ko_id;
			$record->contents_ja_id	= isset($_POST['contents_ja_id'])?$_POST['contents_ja_id']:$record->contents_ja_id;
			$record->contents_zh_id	= isset($_POST['contents_zh_id'])?$_POST['contents_zh_id']:$record->contents_zh_id;
			$record->contents_nl_id	= isset($_POST['contents_nl_id'])?$_POST['contents_nl_id']:$record->contents_nl_id;
			$record->contents_fr_id	= isset($_POST['contents_fr_id'])?$_POST['contents_fr_id']:$record->contents_fr_id;
			$record->contents_en_id	= isset($_POST['contents_en_id'])?$_POST['contents_en_id']:$record->contents_en_id;
			$record->contents_es_id	= isset($_POST['contents_es_id'])?$_POST['contents_es_id']:$record->contents_es_id;
			
		}

		if ($this->_check_rights(WSR_CONTENTS_CONTENT)) {
//			$record->content = $_POST['fckeditor1'];
//			$html = ereg_replace("<(/)?(font|del|ins)[^>]*>","",$html);
//			$html = ereg_replace("<([^>]*)(lang|style|size|face)=(\"[^\"]*\"|'[^']*'|[^>]+)([^>]*)>","<\\1>",$html);
//			$html = ereg_replace("<([^>]*)(lang|style|size|face)=(\"[^\"]*\"|'[^']*'|[^>]+)([^>]*)>","<\\1>",$html);
//d($_POST);
			$html = $_POST['fckeditor1'];
			$record->content_1 = $html;
			$html = $_POST['fckeditor2'];
			$record->content_2 = $html;
			$html = $_POST['fckeditor3'];
			$record->content_3 = $html;
			$html = $_POST['fckeditor4'];
			$record->content_4 = $html;
			$html = $_POST['fckeditor5'];
			$record->content_5 = $html;
		}


		if ($this->_check_rights(WSR_CONTENTS_LAYOUT)) {
			$record->layout = $_POST['layout'];
			
			for($i=1;$i<=9;$i++) {
				if (isset($_POST['placeholder_'.$i.'_type'])) {
					$type = 'placeholder_' . $i;
					$value = 'placeholder_' . $i . '_value';
					$param = 'placeholder_' . $i . '_param';
					$record->$type = $_POST['placeholder_'.$i.'_type'];
					
					if (isset($_POST['placeholder_'.$i.'_value'])) {
						$record->$value = $_POST['placeholder_'.$i.'_value'];
					}
					else {
						$record->$value = 0;
					}

					if (isset($_POST['placeholder_'.$i.'_value_param'])) {
						$record->$param = $_POST['placeholder_'.$i.'_value_param'];
					}
				}
			}
		}		

		if ($this->_check_rights(WSR_CONTENTS_METADATA)) {
			if ($this->_check_rights(WSR_CONTENTS_SEO)) {
				$record->seodescription	= htmlentities($_POST['seodescription'], 	ENT_QUOTES, "utf-8" );
				$record->seokeywords	= htmlentities($_POST['seokeywords'], 		ENT_QUOTES, "utf-8" );
			}
			
			$record->comment = $_POST['fckeditor_comment'];
		}


		$user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
		if (!$user) {
			echo "<span class='error'>Security breach reported.</span>";
			exit();
		}
		$record->modifier_id = $user->id;
		$record->modify_date = MyActiveRecord::DbDateTime();
		
		if ($record->save()) {
			$record->fullpath = $this->_get_path($record->id, $record->language);
			$record->save();
			WSHistory::store('Content Record', 'contents', $record->id, $record);
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Saved menu node #' . $record->id . ' ( ' . $record->title . ' ) ' );
			echo '{ "type": "info", "message": "Les donn&eacute;es pour la page ' . $record->title . ' sont sauvegard&eacute;es !"}';
		}
		else {
			WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not save menu node #' . $record->id . ' ( ' . $record->title . ' ) ' );
			echo '{ "type": "error", "message": "Une erreur de sauvegarde de la page ' . $record->title . ' est survenue."}';
		}
	}
	
	function _beautify($path) {
		$path = ucfirst($path);
		$path = str_replace('-', ' ', $path);
		if ($path == '') $path = '###';
		return $path;
	}
	
	function getHistorySelect() {
		$code = array();
		$code[] = '<select>';
		$history = WSHistory::retrieve('contents', $this->params[0]);
		$code[] = "<option value='-1'>Choisissez une version de votre page...</option>";
		foreach($history as $item) {
			$code[] = "<option value='" . $item->id . "'>" . $item->ddate . '</option>';
		}
		$code[] = '</select>';
		echo implode('', $code);
	}
	
	function restoreHistory() {
		if ($this->_check_rights(WSR_CONTENTS_VERSIONS)) {
			if (WSHistory::restore( $this->params[0] )) {
				WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'History #' . $this->params[0] . ' retrieved for contents' );
			}
			else {
				WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not retrieve history #' . $this->params[0] . ' for contents' );
			}
		}
	}
	
	function _check_rights( $level ) {
		global $userlanguage;
		$user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
		$user_group = $user->find_parent('groups');
		$userlanguage = $user->language;

		$modules 	= unserialize($user_group->rights);
		$tables 	= unserialize($user_group->datarights);
		if (!isset($modules[$level])) {
			return false;
		}
		else {
			return true;
		}
	}


	function getMenuItemsAsArray($parent = 0, $language) {
		$records = MyActiveRecord::FindAll('contents', "contents_id = " . (int) $parent . " and language ='" . $language . "'", 'porder asc');
		if (!is_array($records)) {
			return false;
		}
		
		$ar = array();

		foreach ($records as $item) {
			$children = $this->getMenuItemsAsArray($item->id, $language);
			$path = $item->fullpath;
			if ($item->fullpath != '') {
				$ar[$item->id] = $path . ' (' . $item->title . ')';
			}
			if (is_array($children)) {
				$ar = $ar + $children;
			}
		}
		return $ar;
	}



}

