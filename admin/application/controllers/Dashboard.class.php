<?php

# Home Module
# Loads and displays the accueil-index-[language].html file from the views directory.
#

WSLoader::load_dictionary('languages');
WSLoader::load_helper('misc');

class Home extends WSController {

	public $module_name = 'Dashboard';
	public $module_version = '2.0';

	var $user = null;

	function Home($smarty=null, $language=null, $current_language=null, $params=null, $parameters=null, &$auth=null) {
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
		if (!$this->user) {
			$this->user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
			$this->user->init();
		}

		$smarty_contents = new Template;

		// Initialize stats for each language defined in the cms
		
		//   get defined language from application
		$app_config = new WSConfig;
		$app_config->load(WS_ADMINISTERED_APPLICATION_FOLDER . '/config/');
		
		$stats = array();
		foreach($app_config->get('languages') as $language) {
			$stat = array(
				'pages_total' 	=> 0,
				'pages_hidden' 	=> 0,
				'pages_scrap'	=> 0,
				'pages_hits'	=> 0,
				'blocks'		=> 0,
				'forms'			=> 0,
				'news'			=> 0,
				'news_running'  => 0,
				'news_stopped'  => 0,
				'language'		=> WSDLanguages::getLanguageName($language),
				'graph'			=> ''
			);
			$stats[$language] = $stat;
		}
		
		// Page stats
		if ($this->user->has_module(WSR_CONTENTS)) {
			$smarty_contents->assign('WSR_CONTENTS', true);
			$pages = MyActiveRecord::FindAll('contents', null, 'hits desc');
			$hits = 0;
			foreach($pages as $page) {
				if (in_array($page->language, $app_config->get('languages'))) {
					$hits += $page->hits;
				}
			}
			$counter = 0;
			foreach($pages as $page) {
				if (in_array($page->language, $app_config->get('languages'))) {
					switch ($page->hidden) {
						case 2:
							$stats[$page->language]['pages_hidden']++;
						break;
						case 1:
							$stats[$page->language]['pages_scrap']++;
						break;
						default:
							$stats[$page->language]['pages_total']++;
						break;
					}
					$stats[$page->language]['pages_hits']+= $page->hits;
					if ($counter <= 10) {
						if ($hits != 0) {
							$stats[$page->language]['graph'] .= "<li><a href='" . $page->fullpath . "' target='_blank'>" . shorten_text($page->title, 30) . "</a> <a href='/admin/" . $page->language . "/content/" . $page->id . "'><img src='/admin/application/lib/images/status/editing.png' /></a><span class='bar' style='width: " . round($page->hits / $hits * 100) . "px;'>&nbsp;</span></li>";
							$counter++;
						}
					}
				}
			}
			foreach($app_config->get('languages') as $language) {
				$stats[$language]['graph'] = "<ul id='graph'>" . $stats[$language]['graph'] . '</ul>';
			}
		}

		// Bloc stats
		if ($this->user->has_module(WSR_BLOCKS)) {
			$smarty_contents->assign('WSR_BLOCKS', true);
			foreach(MyActiveRecord::FindAll('blocks') as $block) {
				if (in_array($block->language, $app_config->get('languages'))) {
					$stats[$block->language]['blocks']++;
				}
			}
		}

		// Form stats
		if ($this->user->has_module(WSR_FORMS)) {
			$smarty_contents->assign('WSR_FORMS', true);
			foreach(MyActiveRecord::FindAll('formsdefinitions') as $form) {
				if (in_array($form->language, $app_config->get('languages'))) {
					$stats[$form->language]['forms']++;
				}
			}
		}

		// Newsletter stats (complete)
		if ($this->user->has_module(WSR_NEWSLETTERS)) {
			$smarty_contents->assign('WSR_NEWSLETTERS', true);
			foreach(MyActiveRecord::FindAll('newsletter') as $news) {
				if (in_array($news->language, $app_config->get('languages'))) {
					$stats[$news->language]['news']++;
					if ($news->status == 0) {
						$stats[$news->language]['news_stopped']++;
					}
					else {
						$stats[$news->language]['news_running']++;
					}
				}
			}
		}

		// Data stats
		$tdefs = MyActiveRecord::FindAll('tabledefinitions', null, 'system asc, title asc');
		if ($this->user->has_module(WSR_DATA)) {
			$smarty_contents->assign('WSR_DATA', true);
			foreach($app_config->get('languages') as $language) {
				$stats[$language]['tables'] = "<ul id='dashboard-data-links'>";
				foreach ($tdefs as $t) {
					if (in_array($t->name, $this->user->tables) ) {
						if ( ($t->monolanguage) && ($t->default_language != $language) ) continue;
						$stats[$language]['tables'] .= "<li>";
						$stats[$language]['tables'] .= "<a href='/admin/" . $language . "/tables/" . $t->name . "/'>";
						$stats[$language]['tables'] .= $t->title;
						$stats[$language]['tables'] .= "</a>";
						$stats[$language]['tables'] .= "<span>" . nl2br($t->description) . "</span>";
						$stats[$language]['tables'] .= "</li>";
					}
				}
				$stats[$language]['tables'] .= "</ul>";
			}
		}

		// Admin data
		if ($this->user->is_admin()) {
			$smarty_contents->assign('admin', true);
			$users = MyActiveRecord::FindAll('users');
			$groups = MyActiveRecord::FindAll('groups');
			$smarty_contents->assign('admin_users', count($users));
			$smarty_contents->assign('admin_groups', count($groups));
			$smarty_contents->assign('admin_database_deployment', $app_config->get('deployment'));
			$db = $app_config->get($app_config->get('deployment'));
			$smarty_contents->assign('admin_database', $db['db_name']);
			$smarty_contents->assign('admin_database_user', $db['db_user']);
			$smarty_contents->assign('admin_database_server', $db['db_server']);
			
		}



		$smarty_contents->assign('stats', $stats);
		
		return $smarty_contents->fetch('accueil-index-' . $this->language . '.tpl' );
	}
	
}
