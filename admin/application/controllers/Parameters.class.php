<?php

WSLoader::load_base('log');

WSLoader::load_helper('system');
WSLoader::load_helper('forms-advanced-uikit');

class Parameters extends WSController {

	public $module_name = 'Parameters';
	public $module_version = '2.1';

	function Parameters($smarty=null, $language=null, $current_language=null, $params=null, $parameters=null, &$auth=null) {
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
		if (!$this->_check_rights(WSR_PARAMS)) {
			return false;
		}

		$config = new WSConfig;
		$config->load( dirname(__FILE__) . '/../../../application/config/' );

		$this->smarty->assign('current_page_title', 'Param&egrave;tres');

		$smarty_contents = new Template;
		$contents = null;

		$smarty_contents->assign('company', generate_input_text( 'company', 'Entreprise', $config->get('company'), '', false, "Le nom de votre site. Ceci appara&icirc;tra &agrave; tous les endroits pertinents de votre site (Titre, ... )" ));
		$smarty_contents->assign('contactmail', generate_input_text( 'contactmail', 'E-Mail de contact', $config->get('contactmail'), "", false, "Adresse utilis&eacute;e pour les formulaires de contact. Tous les messages provenant du site arriverons &agrave; cette adresse." ));

		// Set available languages	
		$languages = array();
		foreach($config->get('reasonable_languages', 'system') as $language) {
			$languages[$language] = WSDLanguages::getLanguageName($language);
		}
		$smarty_contents->assign('default_language', generate_select( 'default_language', 'Langue principale', $languages, $config->get('default_language'), false, "Langue utilis&eacute;e dans le site lorsque l'utilisateur entre dans le site directement." ) );
			
		$languages = array();
		foreach($config->get('reasonable_languages', 'system') as $language) {
			$languages[$language] = WSDLanguages::getLanguageName($language);
		};
		$smarty_contents->assign('languages', generate_checkbox_group( 'languages', 'Langues support&eacute;es', $languages, $config->get('languages'), '', false ) );

		$smarty_contents->assign('version', generate_input_text( 'version', 'Version', $config->get('version'), "", false, "N&deg; de version du site." ));
		$smarty_contents->assign('release', generate_input_text( 'release', 'Release', $config->get('release'), '', false, "Nom de code de cette version du site." ));
		$smarty_contents->assign('author', generate_input_text( 'author', 'Auteur', $config->get('author'), "", false, "Personne responsable du contenu du site." ));
		$smarty_contents->assign('uacct', generate_text_area( 'uacct', 'Google analytics', base64_decode($config->get('uacct')), 8, 100, '', false, "Code Google Analytics &agrave; utiliser dans votre site. Collez ici tout le code google analytics fourni par Google. Il sera automatiquement ajout&eacute; &agrave; toutes les pages du site." ));

		$smarty_contents->assign('headers', generate_text_area( 'headers', 'Headers', base64_decode($config->get('headers')), 8, 100, '', false, "Collez ici tous les codes HTML que vous d&eacute;sirez. Ils seront ins&eacute;r&eacute;s &agrave; chaque page dans la section &lt;header&gt;." ));
		$smarty_contents->assign('recaptcha_public', generate_input_text( 'recaptcha_public', 'Public Key', $config->get('recaptcha_public'), '', false, "Si vous utilisez un captcha sur le site, donnez ici la clé publique." ));
		$smarty_contents->assign('recaptcha_private', generate_input_text( 'recaptcha_private', 'Private Key', $config->get('recaptcha_private'), '', false, "Si vous utilisez un captcha sur le site, donnez ici la clé privée." ));

		$recaptcha_themes = array(
			'red' => 'Rouge',
			'blackglass' => 'Noir',
			'white' => 'Blanc',
			'clean' => 'Minimaliste'
		);
		$smarty_contents->assign('recaptcha_theme', generate_select( 'recaptcha_theme', 'Thème captcha', $recaptcha_themes, $config->get('recaptcha_theme'), false, "Thème du captcha." ) );

		// Routes
		$routes = array();
		$count = false;
		foreach($config->params['routes'] as $key => $value) {
			if ($count) {
				$routes[] = "'" . $key . "' = '" . $value . "'";
			}
			else {
				if ($key == 'default_method') {
					$count = true;
				}
			}
		}
		$smarty_contents->assign('routes', generate_text_area( 'routes', 'Routes', implode("\n", $routes), 8, 100, '', false, "Routes personnalisées du CMS, ne changez ces paramètres que si vous savez réellement ce que vous faites !" ));

		$smarty_contents->assign('speedup', generate_input_checkbox( 'speedup', 'Speedup', $config->get('speedup', 'system'), "Activer la compression automatique des fichiers javascript et CSS du site. Attention, fonctionne pas toujours."));
		$smarty_contents->assign('caching', generate_input_checkbox( 'caching', 'Caching', $config->get('caching', 'system')==2?true:false, "Activer la cache des pages du site sur le serveur." ));
		//$smarty_contents->assign('cache_lifetime', generate_input_text( 'cache_lifetime', 'Dur&eacute;e de vie', $config->get('cache_lifetime', 'system'), "", false, "Indique l'intervalle en secondes entre chaque rafra&icirc;chissement de la cache serveur activ&eacute;e au-dessus." ));
		$timeouts = array();
		$timeouts[60] = '1 min';
		$timeouts[180] = '3 min';
		$timeouts[300] = '5 min';
		$timeouts[600] = '10 min';
		$timeouts[900] = '15 min';
		$timeouts[1800] = '30 min';
		$timeouts[2700] = '45 min';
		$timeouts[3600] = '1 heure';
		$timeouts[10800] = '3 heures';
		$timeouts[21600] = '6 heures';
		$timeouts[32400] = '9 heures';
		$timeouts[43200] = '12 heures';
		$timeouts[86400] = '24 heures (1 jour)';
		$timeouts[259200] = '3 jours';
		$timeouts[604800] = '7 jours';
		$timeouts[2592000] = '30 jours (1 mois)';
		$timeouts[7776000] = '3 mois';
		$timeouts[15552000] = '6 mois';
		$timeouts[23328000] = '9 mois';
		$timeouts[31536000] = '365 jours (1 an)';
		$smarty_contents->assign('cache_lifetime', generate_select( 'cache_lifetime', 'Dur&eacute;e de vie', $timeouts, $config->get('cache_lifetime', 'system'), false, "Indique l'intervalle entre chaque rafra&icirc;chissement de la cache serveur activ&eacute;e au-dessus." ));

		$smarty_contents->assign('debug', generate_input_checkbox( 'debug', 'Debug', $config->get('debug', 'system'), "Active l'affichage des messages de debug PHP &agrave; l'&eacute;cran." ));
		$smarty_contents->assign('debug_log', generate_input_checkbox( 'debug_log', 'Debug Log', $config->get('debug_log', 'system'), "Active le stockage des erreurs PHP dans des fichiers de log pour plus tard." ));

		$smarty_contents->assign('security', generate_input_checkbox( 'security', 'S&eacute;curit&eacute;', $config->get('security', 'system'), "Indique si le syst&egrave;me de s&eacute;curit&eacute; est actif sur le site ou non." ));
		$smarty_contents->assign('security_session', generate_input_text( 'security_session', 'Session', $config->get('security_session', 'system'), "", false, "Nom de la variable de session utilis&eacute;e." ));
		$tables = array();
		foreach (MyActiveRecord::FindAll('tabledefinitions', '', 'title asc') as $table) {
			$tables[$table->name] = $table->title;
		}
		$smarty_contents->assign('security_table', generate_select( 'security_table', 'Table', $tables, $config->get('security_table', 'system'), false, "Table utilis&eacute;e pour la gestion de la s&eacute;curit&eacute;." ) );
		
		$smarty_contents->assign('timeout_history', generate_select( 'timeout_history', 'Historique', $timeouts, $config->get('timeout_history', 'system'), false, "Temps pendant laquelle un historique est conserv&eacute; avant d'&ecirc;tre purg&eacute;." ));
		$smarty_contents->assign('timeout_logs', generate_select( 'timeout_logs', 'Logs', $timeouts, $config->get('timeout_logs', 'system'), false, "Temps pendant laquelle une entr&eacute;e de log est conserv&eacute;." ));
		$smarty_contents->assign('timeout_sessions', generate_select( 'timeout_sessions', 'Sessions', $timeouts, $config->get('timeout_sessions', 'system'), false, "Dur&eacute;e de vie d'une session." ));

		$smarty_contents->assign('menu_name_1', generate_input_text( 'menu_name_1', 'Menu 1', $config->get('menu_name_1'), "", false, "Nom du menu en position 1." ));
		$smarty_contents->assign('menu_name_2', generate_input_text( 'menu_name_2', 'Menu 2', $config->get('menu_name_2'), "", false, "Nom du menu en position 2." ));
		$smarty_contents->assign('menu_name_3', generate_input_text( 'menu_name_3', 'Menu 3', $config->get('menu_name_3'), "", false, "Nom du menu en position 3." ));
		$smarty_contents->assign('menu_name_4', generate_input_text( 'menu_name_4', 'Menu 4', $config->get('menu_name_4'), "", false, "Nom du menu en position 4." ));

		$code = '';
		foreach($languages as $language_code => $language) {
			$pages = array();
			$found = false;
			foreach (MyActiveRecord::FindAll('contents', "language = '$language_code'", 'title asc') as $page) {
				$pages[$page->id] = $page->title . ' - (' . $page->fullpath . ')';
				$found = true;
			}
			if ($found) {
				$code .= generate_select( 'template_page_id_' . $language_code, "Template ($language)", $pages, $config->get('template_page_id_' . $language_code), false, "Page utilisée comme modèle à la création d'une nouvelle page ($language)." );
			}
		}
		$smarty_contents->assign('template_page_id',  $code);


		$smarty_contents->assign('maintenance', generate_input_checkbox( 'maintenance', 'Maintenance', $config->get('maintenance'), "Indique si le site est en maintenance/bloqué ou non." ));
		$smarty_contents->assign('maintenance_text', generate_text_area( 'maintenance_text', 'Texte de maintenance', base64_decode($config->get('maintenance_text')), 8, 100, '', false, "Tapez le message de maintenance &agrave; afficher si n&eacute;cessaire." ));
		
		// Widget Manager
		$files = glob(WS_ADMINISTERED_APPLICATION_FOLDER . "/controllers/W*.class.php");
		$widgets = array();
		foreach ($files as $w) {
			$content = file_get_contents($w);
			$widgets[] = array(
				'id'		=> md5(basename($w)),
				'rname'		=> widget_get_element('RNAME', $content),
				'name'		=> widget_get_element('NAME', $content),
				'path'		=> widget_get_element('PATH', $content),
				'note'		=> widget_get_element('NOTE', $content),
				'version'	=> widget_get_element('VERSION', $content),
				'active'	=> (widget_get_element('ACTIVE', $content) == 'YES')?true:false,
				'checked'	=> (widget_get_element('ACTIVE', $content) == 'YES')?"checked='checked'":'',
				'init'		=> (widget_get_element('INIT', $content) == 'YES')?$w:''
			);
		}
		$smarty_contents->assign('widget_list', $widgets);

		$deployments = array();
		$id = 1;
		foreach($config->get('deployments') as $name) {
			$selected = ($config->get('deployment') == $name)?"checked='checked'":'';
			$deployments[] = array_merge(
				array( 'id' => $id, 'name' => $name, 'selected' => $selected ),
				$config->get($name));
			$id++;
		}
		$smarty_contents->assign('id', ($id+1));
		$smarty_contents->assign('deployments', $deployments);

		return $smarty_contents->fetch( 'parameters-index-' . $this->language . '.tpl' );
	}

	# Saves site parameters
	function save_site() {
		$this->clean_post();
//		d($_POST,true);

		$config = new WSConfig;
		$dest = dirname(__FILE__) . '/../../../application/config/';
		$config->load($dest);

		$config->params['config']['company'] = $_POST['company'];
		$config->params['config']['contactmail'] = $_POST['contactmail'];
		$config->params['config']['default_language'] = $_POST['default_language'];
		
		$config->params['config']['languages'] = $_POST['languages'];
		
		$config->params['config']['version'] = $_POST['version'];
		$config->params['config']['release'] = $_POST['release'];
		$config->params['config']['author'] = $_POST['author'];
		$config->params['config']['uacct'] = $_POST['uacct'];
		$config->params['config']['headers'] = $_POST['headers'];
		$config->params['config']['recaptcha_public'] = $_POST['recaptcha_public'];
		$config->params['config']['recaptcha_private'] = $_POST['recaptcha_private'];
		$config->params['config']['recaptcha_theme'] = $_POST['recaptcha_theme'];
		$config->params['uroutes'] = $_POST['routes'];
		
		$config->params['system']['speedup'] = isset($_POST['speedup']);
		$config->params['system']['caching'] = isset($_POST['caching'])?2:0;
		$config->params['system']['cache_lifetime'] = $_POST['cache_lifetime'];

		$config->params['system']['security'] = isset($_POST['security']);
		$config->params['system']['security_session'] = $_POST['security_session'];
		$config->params['system']['security_table'] = $_POST['security_table'];

		$config->params['system']['timeout_history'] = $_POST['timeout_history'];
		$config->params['system']['timeout_logs'] = $_POST['timeout_logs'];
		$config->params['system']['timeout_sessions'] = $_POST['timeout_sessions'];

		$config->params['system']['debug'] = isset($_POST['debug']);
		$config->params['system']['debug_log'] = isset($_POST['debug_log']);

		$config->params['config']['menu_name_1'] = $_POST['menu_name_1'];
		$config->params['config']['menu_name_2'] = $_POST['menu_name_2'];
		$config->params['config']['menu_name_3'] = $_POST['menu_name_3'];
		$config->params['config']['menu_name_4'] = $_POST['menu_name_4'];

		$config->params['config']['maintenance'] = isset($_POST['maintenance']);
		$config->params['config']['maintenance_text'] = $_POST['maintenance_text'];

		foreach($config->get('reasonable_languages', 'system') as $language) {
			if (isset($_POST['template_page_id_' . $language])) {
				$config->params['config']['template_page_id_' . $language] = $_POST['template_page_id_' . $language];
			}
		}

//		d($config, true);

		// Save Configuration files
		if ($config->save($dest, 'all') ) {
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Saved config.php' );
			echo '{ "type": "info", "message": "Fichier de configuration sauvegard&eacute;."}';
		}
		else {
			WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not save config.php' );
			echo '{ "type": "error", "message": "Fichier de configuration non sauvegard&eacute;; contactez votre administrateur."}';
		}
	}
	
	function save_database() {
		$this->clean_post();

		$config = new WSConfig;
		$dest = dirname(__FILE__) . '/../../../application/config/';
		$config->load($dest);
		$config->params['system']['setup'] = isset($_POST['setup']);

		$tables = array();
		foreach ($_POST as $key => $value) {
			if (strstr($key, 'name_')) {
				$id = substr($key, 5, strlen($key));
				$name = trim($value);
				$sql = trim($_POST['sql_' . $id]);
				
				if ( ($name != '') && ($sql != '') ) {
					$tables[] = array(
						'name' => $name,
						'sql' => $sql
					);
				}
			}
		}

		$config->params['database'] = $tables;

		if ( ( $config->save( $dest, 'database' ) ) && ( $config->save( $dest, 'system' ) ) ) {
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Saved database.php and system.php' );
			echo "<span style='color: green;'>Fichier de configuration sauvegard&eacute;.</span>";
		}
		else {
			WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not save database.php and system.php' );
			echo "<span style='color: red;'>Fichier de configuration non sauvegard&eacute;; contactez votre administrateur.</span>";
		}
	}
	
	function save_deployment() {
		$this->clean_post();
		
		$config = new WSConfig;
		$dest = dirname(__FILE__) . '/../../../application/config/';
		$config->load($dest);
		
		$config->params['config']['deployment'] = $_POST['deployment'][0];


		$config->params['config']['local']['html_root'] 		 = $_POST['html_root_1'];
		$config->params['config']['local']['html_lib'] 		 = $_POST['html_lib_1'];
		$config->params['config']['local']['db_user'] 		 = $_POST['db_user_1'];
		$config->params['config']['local']['db_password'] 	 = $_POST['db_password_1'];
		$config->params['config']['local']['db_name'] 		 = $_POST['db_name_1'];
		$config->params['config']['local']['db_server'] 		 = $_POST['db_server_1'];
		$config->params['config']['beta']['html_root'] 		 = $_POST['html_root_2'];
		$config->params['config']['beta']['html_lib'] 		 = $_POST['html_lib_2'];
		$config->params['config']['beta']['db_user'] 			 = $_POST['db_user_2'];
		$config->params['config']['beta']['db_password'] 		 = $_POST['db_password_2'];
		$config->params['config']['beta']['db_name'] 			 = $_POST['db_name_2'];
		$config->params['config']['beta']['db_server'] 		 = $_POST['db_server_2'];
		$config->params['config']['production']['html_root'] 	 = $_POST['html_root_3'];
		$config->params['config']['production']['html_lib'] 	 = $_POST['html_lib_3'];
		$config->params['config']['production']['db_user'] 	 = $_POST['db_user_3'];
		$config->params['config']['production']['db_password'] = $_POST['db_password_3'];
		$config->params['config']['production']['db_name'] 	 = $_POST['db_name_3'];
		$config->params['config']['production']['db_server'] 	 = $_POST['db_server_3'];
		
		
		// Save Configuration files
		if ($config->save($dest, 'config')) {
			echo "<span style='color: green;'>Fichier de configuration sauvegard&eacute;.</span>";
		}
		else {
			echo "<span style='color: red;'>Fichier de configuration non sauvegard&eacute;; contactez votre administrateur.</span>";
		}
		
	}

	function clean_post() {
		// Clean up _POST
		function process_value(&$value, $key) {
			if (!is_array($value)) {
				switch ($key) {
					case 'uacct':
					case 'headers':
					case 'maintenance_text':
						$value = base64_encode($value);
					break;
					case 'routes':
						$value = trim($value);
						$ar = explode("\n", $value);
						$value = array();
						foreach ($ar as $a) {
							if (trim($a) == '') continue;
							list($key,$v) = explode('=', $a);
							$key = trim($key);
							$v = trim($v);
							if ($key != '') {
								$value[$key] = $v;
							}
						}
					break;
					default:
						$value = trim($value);
						$value = htmlentities($value, ENT_QUOTES, "UTF-8" );
					break;
				}
			}
		}
		array_walk($_POST, 'process_value');
	}
	
	function set_widgets() {
		if (!isset($this->auth->session)) {
			echo 'KO';
			die();
		}
		if (!isset($_POST['id']) || !isset($_POST['value'])) {
			echo 'KO';
			die();
		}
		$widgets = glob(WS_ADMINISTERED_APPLICATION_FOLDER . "/controllers/W*.class.php");
		$filename = '';
		foreach($widgets as $w) {
			if (md5(basename($w)) == $_POST['id'] ) {
				$filename = $w;
			}
		}
		if (!file_exists($filename)) {
			echo 'KO';
			die();
		}
		$content = file_get_contents($filename);
		$new_content = widget_set_element('ACTIVE', ($_POST['value'] == 'true'), $content);
		if ($new_content != $content) {
			file_put_contents($filename, $new_content);
		}
		echo ($content != $new_content);
	}
	
	function init_widget() {
		if (!isset($this->auth->session)) {
			echo 'KO';
			die();
		}
		if (!isset($_POST['url'])) {
			echo 'KO';
			die();
		}
		
		$widget_filename = $_POST['url'];
		$widget_class = file_basename(basename($_POST['url']));
		
		include($widget_filename);
		
		$widget = new $widget_class;

		$initinfo = array();

		if (!method_exists($widget, '_init')) {
			die("The widget cannot be initalized.");
		}

		$initinfo = $widget->_init();
		
		$this->init_remove_tables($initinfo);

		if ($_POST['init'] == 1) {
			die('Nettoyage terminé.');
		}

		$this->init_create_tables($initinfo);

		echo "Opération terminée.";
		
	}
	
	function init_remove_tables($tables) {
		foreach ($tables as $table) {
			$tabledefinition = MyActiveRecord::FindFirst('tabledefinitions', "name = '" . $table->name . "'");
			if ($tabledefinition) {
				$tabledefinition->destroy();
			}
		}
	}
	
	function init_create_tables($tables) {
		foreach ($tables as $table) {
			// New table to create
			$nt = MyActiveRecord::Create('tabledefinitions');
			$nt->language = 'fr';
			$nt->name			= $table->name;
			$nt->title			= $table->title;
			$nt->description	= $table->description;
			$nt->sortparams		= $table->sortparams;
			
			if ($table->inlineadd   != null) { $nt->inlineadd		= $table->inlineadd;   }
			if ($table->filtrable   != null) { $nt->filtrable		= $table->filtrable;   }
			if ($table->childtable  != null) { $nt->childtable		= $table->childtable;  }
			if ($table->system      != null) { $nt->system			= $table->system;      }
			if ($table->rss         != null) { $nt->rss				= $table->rss;         }
			if ($table->rss_sublink != null) { $nt->rss_sublink		= $table->rss_sublink; }
			if ($table->porder      != null) { $nt->porder			= $table->porder;	   }

			$nt->saveadvanced('create', true);

			// Additional fields			
			$counter = 10;
			foreach ($table->fields as $f) {
				$nf = MyActiveRecord::Create('tablefields');
				$nf->language = 'fr';
				$nf->title = $f->title;
				$nf->name = $f->name;
				$nf->type = $f->type;
				$nf->description = $f->description;
				$nf->default = $f->default;
				$nf->showlist = $f->showlist;
				$nf->listeditable = $f->listeditable;
				$nf->showedit = $f->showedit;
				$nf->tabledefinitions_id = $nt->id;
				$nf->porder = $counter;
				$nf->save();
				$nf->saveadvanced('create', true);
				$counter++;
			}

		}
	}
	
	function _check_rights( $level ) {
		$user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
		$user_group = $user->find_parent('groups');
		$modules 	= unserialize($user_group->rights);
		$tables 	= unserialize($user_group->datarights);
		if (!isset($modules[$level])) {
			return false;
		}
		else {
			return true;
		}
	}

}

?>