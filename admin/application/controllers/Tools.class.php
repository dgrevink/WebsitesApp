<?php

WSLoader::load_helper('file');
WSLoader::load_helper('forms-advanced');
WSLoader::load_helper('misc');
WSLoader::load_base('menu');
WSLoader::load_dictionary('languages');
WSLoader::load_helper('database');


$DB = array();
$VERSION = "2.0";

class Tools extends WSController {

	function duplicatecontent(){
		$echo = array();

		$echo[] = " Duplication de contenu existants vers d'autres langues";
		$echo[] = "========================================================";
		$echo[] = "";

		echo "<html><head><title>Table duplication</title></head><body style='background-color: #111; padding: 10px; color: #ccc;'>";
		echo "<pre>";
		echo implode("\n", $echo);
		if (!isset($_POST['post'])) {
			echo '<p>Cette opération ne pourra pas être annulée.</p>';
			echo "<form method='post'>";

			// Table to duplicate

			// Languages
			$app_config = new WSConfig;
			$app_config->load(dirname(__FILE__) . '/../../../application/config/');

			echo "Choisir la langue d'origine <select name='source_language'>";
			foreach ($app_config->get('languages') as $language) {
				echo "<option value='$language'>" . WSDLanguages::getLanguageName($language) . " ($language)</option>";
			}
			echo "</select>";

			echo " &rarr; <select name='destination_language'>";
			foreach ($app_config->get('languages') as $language) {
				echo "<option value='$language'>" . WSDLanguages::getLanguageName($language) . " ($language)</option>";
			}
			echo "</select>";

			echo "<br/><br/><span style='color: red;'>ATTENTION cette opération va réellement détruire TOUTES les données dans la langue de destination.<br/>Assurez-vous d'être BIEN certain de ce que vous faites !!!!</span><br/><br/>";



			echo "<input name='post' type='submit' value='OK'>";
			echo "</form>";
			die();
		} else { // if posted
			$source_lng = $_POST['source_language'];
			$dest_lng = $_POST['destination_language'];

			MyActiveRecord::Query("delete from contents where language='".$dest_lng."';");
			MyActiveRecord::Query("delete from blocks where language='".$dest_lng."';");
			MyActiveRecord::Query("delete from formsdefinitions where language='".$dest_lng."';");
			MyActiveRecord::Query("delete from slider where language='".$dest_lng."';");

			$has_content = false; // if false, do nothing
			echo 'Contenu...';
			$ids = array();
			foreach(MyActiveRecord::FindAll('contents', "language='".$source_lng."'", "contents_id asc, id asc") as $olditem) {
				$has_content = true;
				$newmenu = MyActiveRecord::Create('contents');
				$newmenu->id = NULL;
				$newmenu->language				= $dest_lng ;
				$newmenu->title					= $olditem->title . ' '.strtoupper($dest_lng);
				$newmenu->contents_id			= 0;

				$newmenu->access				= $olditem->access;
				$newmenu->cached				= $olditem->cached;
				$newmenu->porder				= $olditem->porder;
				$newmenu->layout				= str_replace('-'.$source_lng, '-'.$dest_lng, $olditem->layout);
				$newmenu->titleshort			= $olditem->titleshort;
				$newmenu->hidden				= $olditem->hidden;
				$newmenu->path					= $olditem->path;
				$newmenu->fullpath				= str_replace("/$source_lng/", "/$dest_lng/", $olditem->fullpath);
				$newmenu->seodescription		= $olditem->seodescription;
				$newmenu->seokeywords			= $olditem->seokeywords;
				$newmenu->content				= $olditem->content;
				$newmenu->comment				= "Transferred from " . WSDLanguages::getLanguageName($source_lng) .  " <br/>" . $olditem->comment;
				$newmenu->placeholder_1			= $olditem->placeholder_1;
				$newmenu->placeholder_1_param	= $olditem->placeholder_1_param;
				$newmenu->placeholder_2			= $olditem->placeholder_2;
				$newmenu->placeholder_2_param	= $olditem->placeholder_2_param;
				$newmenu->placeholder_3			= $olditem->placeholder_3	;
				$newmenu->placeholder_3_param	= $olditem->placeholder_3_param;
				$newmenu->placeholder_4			= $olditem->placeholder_4;
				$newmenu->placeholder_4_param	= $olditem->placeholder_4_param;
				$newmenu->placeholder_5			= $olditem->placeholder_5;
				$newmenu->placeholder_5_param	= $olditem->placeholder_5_param;
				$newmenu->placeholder_6			= $olditem->placeholder_6;
				$newmenu->placeholder_6_param	= $olditem->placeholder_6_param;
				$newmenu->placeholder_7			= $olditem->placeholder_7;
				$newmenu->placeholder_7_param	= $olditem->placeholder_7_param;
				$newmenu->placeholder_8			= $olditem->placeholder_8;
				$newmenu->placeholder_8_param	= $olditem->placeholder_8_param;
				$newmenu->placeholder_9			= $olditem->placeholder_9;
				$newmenu->placeholder_9_param	= $olditem->placeholder_9_param;

				$user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
				if (!$user) {
					echo 'KO';
					exit();
				}

				$newmenu->creator_id = $olditem->creator_id;
				$newmenu->modifier_id = $user->id;
				$newmenu->create_date = $olditem->create_date;
				$newmenu->modify_date = MyActiveRecord::DbDateTime();
				$newmenu->sitemap = $olditem->sitemap;
				$newmenu->menus = $olditem->menus;
				$newmenu->save();
				$ids[$newmenu->id] = $olditem->id;
			}
			if (!$has_content) {
				die('nothing done, no content in ' . $source_lng);
			}
			echo 'DONE !<br/>';


			// blocks
			echo 'Blocks...';
			$blockids = array();
			foreach(MyActiveRecord::FindAll('blocks', "language='".$source_lng."'", "id asc") as $olditem) {
				$newblock = MyActiveRecord::Create('blocks');
				$newblock->id = NULL;
				$newblock->language				= $dest_lng ;
				$newblock->title					= $olditem->title . ' '.strtoupper($dest_lng);
				$newblock->content				= $olditem->content;
				$newblock->titleseo 			= $olditem->titleseo;
				$newblock->position			= $olditem->position;
				$newblock->save();
				$blockids[$newblock->id] = $olditem->id;
			}
			echo 'DONE !<br/>';

			// Forms
			echo 'Forms...';
			$formids = array();
			foreach(MyActiveRecord::FindAll('formsdefinitions', "language='".$source_lng."'", "id asc") as $oldform) {
				$newform = MyActiveRecord::Create('formsdefinitions');
				$newform->id = NULL;
				$newform->language				= $dest_lng ;
				$newform->type = $oldform->type;
				$newform->max_questions = $oldform->max_questions;
				$newform->title			= $oldform->title . ' '.strtoupper($dest_lng);
				$newform->introduction	= $oldform->introduction;
				$newform->thanks		= $oldform->thanks;
				$newform->errors		= $oldform->errors;
				$newform->mailadmin		= $oldform->mailadmin;
				$newform->mailuser		= $oldform->mailuser;
				$newform->questions 	= $oldform->questions;
				$newform->emailsender	= $oldform->emailsender;
				$newform->emailadmin 	= $oldform->emailadmin;
				$newform->emailextra 	= $oldform->emailextra;
				$newform->instructions 	= $oldform->instructions;
				$newform->locked 		= $oldform->locked;

				$newform->tabledefinitions_id = $oldform->tabledefinitions_id;
				$newform->userquestions 	= $oldform->userquestions;
				$newform->contesterror 	= $oldform->contesterror;
				$newform->usecaptcha	= $oldform->usecaptcha;
				$newform->save();
				$formids[$newform->id] = $oldform->id;
			}
			echo 'DONE !<br/>';

			echo 'Page structures and references...';
			foreach(MyActiveRecord::FindAll('contents', "language='{$dest_lng}'") as $newmenu) {
				$oldmenu = MyActiveRecord::FindById('contents', $ids[$newmenu->id]);

				if ($oldmenu->contents_id != 0) {
					$newmenu->contents_id = array_search($oldmenu->contents_id, $ids);
				}

				// Handle placeholder references for blocs and forms
				for($i=1;$i<=9;$i++) {
					$placeholder_params = explode('-',$oldmenu->{'placeholder_' . $i});
					$placeholder_type = $placeholder_params[0];
					if ($placeholder_type == 'block') {
						$newmenu->{'placeholder_' . $i} = 'block-' . array_search($placeholder_params[1], $blockids);
					}
					if ($placeholder_type == 'form') {
						$newmenu->{'placeholder_' . $i} = 'form-' . array_search($placeholder_params[1], $formids);
					}
				}

				$newmenu->save();
			}
			echo 'DONE !<br/>';

			echo 'Slider ...<br />';
			if (class_exists('slider')) {
				foreach(MyActiveRecord::FindAll('slider', "language='".$source_lng."'", "id asc") as $olditem) {
					$slider = MyActiveRecord::Create('slider');
					$slider->id = NULL;
					$slider->language	= $dest_lng ;
					$slider->title	= $olditem->title . ' '.strtoupper($dest_lng);
					$slider->content	= $olditem->content;
					$slider->titleseo 	= $olditem->titleseo;

					$slider->save();
				}
			}
			echo 'DONE !<br/>';

		}
		echo '</pre>';


		echo '</body></html>';

	}

	function relinklanguages() {
		$echo = array();

		$echo[] = " Reconnection des langues";
		$echo[] = "==========================";
		$echo[] = "";

//		$echo[] = "Cache applicative...OK";
//		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/cache/cache/*.*');
//		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/cache/templates_c/*.*');
//		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/../public/.thumbs/*');

//		$echo[] = "Cache syst&egrave;me...OK";
//		rm(WS_APPLICATION_FOLDER . '/cache/cache/*.*');
//		rm(WS_APPLICATION_FOLDER . '/cache/templates_c/*.*');

		$app_config = new WSConfig;
		$app_config->load(dirname(__FILE__) . '/../../../application/config/');

		$source_language = $app_config->get('default_language');
		$echo[] = "Source language: $source_language";

		foreach(MyActiveRecord::FindAll('contents', "language = '$source_language'") as $sitem) {
			foreach ($app_config->get('languages') as $language) {
				$did = $sitem->{'contents_' . $language .'_id'};
				if ($did != -1) {
					$ditem = MyActiveRecord::FindById('contents', $did);
					if ($ditem) {
						$echo[] = "Crosslinking {$sitem->fullpath} to {$ditem->fullpath} {$sitem->id} <=> {$ditem->id}";
						foreach ($app_config->get('languages') as $lang) {
							if ($lang != $source_language) {
								$ditem->{'contents_' . $lang . '_id'} = $sitem->{'contents_' . $lang . '_id'};
							}
							else {
								$ditem->{'contents_' . $source_language . '_id'} = $sitem->id;
							}
						}
						$ditem->save();
					}
				}
			}
		}

		echo "<html><head><title>Reconnection des langues</title></head><style>.ws-debug{background-color:orange;padding:0.5em;font-size:10px;}</style><body style='background-color: #111; padding: 10px;'>";
		echo "<pre style='color: #ccc'>";
		echo implode("\n", $echo);
		echo '</pre>';
		echo '</body></html>';
	}



	function erasecache() {
		$echo = array();

		$echo[] = " Effacement de la cache";
		$echo[] = "========================";
		$echo[] = "";

		$echo[] = "Cache applicative...OK";
		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/cache/cache/*.*');
		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/cache/templates_c/*.*');
		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/../public/.thumbs/*');

		$echo[] = "Cache syst&egrave;me...OK";
		rm(WS_APPLICATION_FOLDER . '/cache/cache/*.*');
		rm(WS_APPLICATION_FOLDER . '/cache/templates_c/*.*');

		echo "<html><head><title>Effacement de cache</title></head><body style='background-color: #111; padding: 10px;'>";
		echo "<pre style='color: #ccc'>";
		echo implode("\n", $echo);
		echo '</pre>';
		echo '</body></html>';
	}

	function erasestats() {
		$echo = array();

		$echo[] = " Effacement des statistiques";
		$echo[] = "=============================";
		$echo[] = "";

		$echo[] = "Statistiques...OK";
		MyActiveRecord::Query('update contents set hits=0;');

		echo "<html><head><title>Effacement des stats</title></head><body style='background-color: #111; padding: 10px;'>";
		echo "<pre style='color: #ccc'>";
		echo implode("\n", $echo);
		echo '</pre>';
		echo '</body></html>';
	}

	function erasehistory() {
		$echo = array();

		$echo[] = " Effacement de l'historique de contenu";
		$echo[] = "=======================================";
		$echo[] = "";

		$echo[] = "Historique...OK";
		MyActiveRecord::Query('truncate table wsthistory;');

		echo "<html><head><title>Effacement historique</title></head><body style='background-color: #111; padding: 10px;'>";
		echo "<pre style='color: #ccc'>";
		echo implode("\n", $echo);
		echo '</pre>';
		echo '</body></html>';
	}

	function erasesafeties() {
		$echo = array();

		$echo[] = " Effacement des fichiers de sauvegarde de s&eacute;curit&eacute;";
		$echo[] = "===================================================";
		$echo[] = "";

		$echo[] = "Backups menus et configuration...OK";
		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/../backups/configs/*.*');

		echo "<html><head><title>Reset de site</title></head><body style='background-color: #111; padding: 10px;'>";
		echo "<pre style='color: #ccc'>";
		echo implode("\n", $echo);
		echo '</pre>';
		echo '</body></html>';
	}

	function resetsite() {
		$echo = array();

		$echo[] = " Destruction absolue du site";
		$echo[] = "==============================";
		$echo[] = "";

		if (!isset($_POST['post'])) {
			echo "<html><head><title>Reset de site</title><style>span.control, span.label { padding-left: 10px; }</style></head><body style='background-color: #111; padding: 10px;'>";
			echo "<pre style='color: #ccc'>";
			$echo[] = "<form method='post'>";

			$echo[] = "1. Informations utilisateur principal:<br/>";
			$echo[] = generate_input_text( 'name', 'Nom et pr&eacute;nom', '', '');
			$echo[] = generate_input_text( 'username', 'Login', '', '');
			$echo[] = generate_input_password( 'password', 'Mot de passe', '', '');
			$echo[] = generate_input_text( 'email', 'Courriel', '', '');

			$echo[] = '';
			$echo[] = "2. Informations sur la compagnie WEB:<br/>";
			$echo[] = generate_input_text( 'companyname', 'Entreprise', '', '');
			$echo[] = generate_input_text( 'companymotto', 'Motto', '', '');
			$echo[] = generate_input_text( 'companyurl', 'URL', '', '');

			$echo[] = '';
			$echo[] = "3. Informations sur le site:<br/>";
			$echo[] = generate_input_text( 'sitename', 'Entreprise', '', '');
			$echo[] = generate_input_text( 'siteauthor', 'Auteur', '', '');
			$echo[] = generate_input_text( 'siteemail', 'Courriel de contact', '', '');

			$echo[] = '';
			$echo[] = "<input name='post' type='submit' value='Mise &agrave; z&eacute;ro'>";
			$echo[] = "</form>";

			echo implode("\n", $echo);

			echo '</pre>';
			echo '</body></html>';
			die();
		}

		$echo[] = "Reparam&egrave;trage du site...OK";
		$config = new WSConfig;
		$config->load(WS_ADMINISTERED_APPLICATION_FOLDER . '/config/');

		$config->params['config']['company'] = $_POST['sitename'];
		$config->params['config']['author'] = $_POST['siteauthor'];
		$config->params['config']['contactmail'] = $_POST['siteemail'];

		$config->params['config']['default_language'] = 'fr';

		$config->params['config']['languages'] = array( 'fr', 'en');

		$config->params['config']['version'] = '0.1';
		$config->params['config']['release'] = 'alpha';
		$config->params['config']['uacct'] = '';

		$config->params['config']['menu_name_1'] = 'Menu Principal';
		$config->params['config']['menu_name_2'] = 'Menu 2';
		$config->params['config']['menu_name_3'] = 'Menu 3';
		$config->params['config']['menu_name_4'] = 'Menu 4';

		$config->params['config']['maintenance'] = false;
		$config->params['config']['maintenance_text'] = 'Ce site est désactivé.';


		$config->save(WS_ADMINISTERED_APPLICATION_FOLDER . '/config/', 'config');
		$config->save(WS_ADMINISTERED_APPLICATION_FOLDER . '/config/', 'system');

		$cms_config = file(WS_APPLICATION_FOLDER . '/config/config.php');
		$cms_config[23] = '$config[\'brand\'] = \'' . $_POST['companyname'] . "';\n";
		$cms_config[24] = '$config[\'brand-website\'] = \'' . $_POST['companyurl'] . "';\n";
		$cms_config[25] = '$config[\'brand-website-text\'] = \'' . $_POST['companymotto'] . "';\n";
		file_put_contents(WS_APPLICATION_FOLDER . '/config/config.php', implode("",$cms_config));


		$echo[] = "Nettoyage des tables...";
		$echo[] = sqlexec("truncate table contents");
		$echo[] = sqlexec("truncate table formsresponses");
		$echo[] = sqlexec("truncate table formsdefinitions");
		$echo[] = sqlexec("truncate table blocks");
		$echo[] = sqlexec("truncate table newsletterusers");
		$echo[] = sqlexec("truncate table newsletter");
		$echo[] = sqlexec("truncate table filterparameters");
		$echo[] = sqlexec("truncate table filters");
		$echo[] = sqlexec("truncate table banners");
		//$echo[] = sqlexec("truncate table news");
		$echo[] = sqlexec("truncate table nusers");
		$echo[] = sqlexec("truncate table users");
		$echo[] = sqlexec("truncate table groups");
		$echo[] = "";
		$echo[] = "R&eacute;insertion de donn&eacute;es cruciales...";
		$echo[] = sqlexec("insert into users  ( id, username, password, groups_id, title, email, language) values (1, '" . $_POST['username'] . "', '" . md5($_POST['password']) . "', 1, '" . $_POST['name'] . "', '" . $_POST['email'] . "', 'fr')");
		$echo[] = sqlexec("INSERT INTO `groups` VALUES ('1','God','a:30:{i:10;s:2:\"10\";i:11;s:2:\"11\";i:12;s:2:\"12\";i:13;s:2:\"13\";i:14;s:2:\"14\";i:15;s:2:\"15\";i:16;s:2:\"16\";i:18;s:2:\"18\";i:17;s:2:\"17\";i:19;s:2:\"19\";i:20;s:2:\"20\";i:30;s:2:\"30\";i:31;s:2:\"31\";i:32;s:2:\"32\";i:33;s:2:\"33\";i:34;s:2:\"34\";i:35;s:2:\"35\";i:36;s:2:\"36\";i:37;s:2:\"37\";i:38;s:2:\"38\";i:40;s:2:\"40\";i:41;s:2:\"41\";i:42;s:2:\"42\";i:43;s:2:\"43\";i:44;s:2:\"44\";i:50;s:2:\"50\";i:51;s:2:\"51\";i:80;s:2:\"80\";i:90;s:2:\"90\";i:99;s:2:\"99\";}','a:24:{s:7:\"banners\";s:7:\"banners\";s:14:\"banners_modify\";s:14:\"banners_modify\";s:6:\"blocks\";s:6:\"blocks\";s:13:\"blocks_modify\";s:13:\"blocks_modify\";s:7:\"filters\";s:7:\"filters\";s:14:\"filters_modify\";s:14:\"filters_modify\";s:18:\"filtercomparatives\";s:18:\"filtercomparatives\";s:25:\"filtercomparatives_modify\";s:25:\"filtercomparatives_modify\";s:16:\"filterparameters\";s:16:\"filterparameters\";s:23:\"filterparameters_modify\";s:23:\"filterparameters_modify\";s:6:\"groups\";s:6:\"groups\";s:13:\"groups_modify\";s:13:\"groups_modify\";s:5:\"notes\";s:5:\"notes\";s:12:\"notes_modify\";s:12:\"notes_modify\";s:4:\"news\";s:4:\"news\";s:11:\"news_modify\";s:11:\"news_modify\";s:16:\"tabledefinitions\";s:16:\"tabledefinitions\";s:23:\"tabledefinitions_modify\";s:23:\"tabledefinitions_modify\";s:11:\"tablefields\";s:11:\"tablefields\";s:18:\"tablefields_modify\";s:18:\"tablefields_modify\";s:5:\"users\";s:5:\"users\";s:12:\"users_modify\";s:12:\"users_modify\";s:6:\"nusers\";s:6:\"nusers\";s:13:\"nusers_modify\";s:13:\"nusers_modify\";}', 'fr'),('2','Gestionnaires du site','a:6:{i:10;s:2:\"10\";i:13;s:2:\"13\";i:15;s:2:\"15\";i:20;s:2:\"20\";i:30;s:2:\"30\";i:80;s:2:\"80\";}','a:8:{s:6:\"blocks\";s:6:\"blocks\";s:13:\"blocks_modify\";s:13:\"blocks_modify\";s:4:\"news\";s:4:\"news\";s:11:\"news_modify\";s:11:\"news_modify\";s:5:\"users\";s:5:\"users\";s:12:\"users_modify\";s:12:\"users_modify\";s:6:\"nusers\";s:6:\"nusers\";s:13:\"nusers_modify\";s:13:\"nusers_modify\";}', 'fr'),('-1','Tous','a:0:{}','a:0:{}', 'fr')");

		$echo[] = "";
		$echo[] = "Nettoyage des fichiers...";
		$echo[] = "/public/.thumbs/...OK";
		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/../public/.thumbs/*');
		$echo[] = "/public/files/...OK";
		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/../public/files/*');
		$echo[] = "/public/flash/...OK";
		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/../public/flash/*');
		$echo[] = "/public/images/...OK";
		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/../public/images/*');
		$echo[] = "/public/media/...OK";
		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/../public/media/*');
		$echo[] = "/application/lib/images/...OK";
		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/lib/images/*');

		$echo[] = "/application/lib/css/all.css...OK";
		truncate_file(WS_ADMINISTERED_APPLICATION_FOLDER . '/lib/css/all.css');

		$echo[] = "/application/lib/css/all-en.css...OK";
		truncate_file(WS_ADMINISTERED_APPLICATION_FOLDER . '/lib/css/all-en.css');

		$echo[] = "/application/lib/css/ie.css...OK";
		truncate_file(WS_ADMINISTERED_APPLICATION_FOLDER . '/lib/css/ie.css');

		$echo[] = "/application/lib/css/ie6.css...OK";
		truncate_file(WS_ADMINISTERED_APPLICATION_FOLDER . '/lib/css/ie6.css');

		$echo[] = "/application/lib/css/editor.css...OK";
		truncate_file(WS_ADMINISTERED_APPLICATION_FOLDER . '/lib/css/editor.css');

		$echo[] = "/application/lib/css/main.css...OK";
		truncate_file(WS_ADMINISTERED_APPLICATION_FOLDER . '/lib/css/main.css');

		$echo[] = "/application/lib/js/site.js...OK";
		truncate_file(WS_ADMINISTERED_APPLICATION_FOLDER . '/lib/js/site.js');

		$echo[] = "admin log...OK";
		truncate_file(WS_ADMINISTERED_APPLICATION_FOLDER . '/logs/admin.log');

		$echo[] = "site log...OK";
		truncate_file(WS_ADMINISTERED_APPLICATION_FOLDER . '/logs/site.log');

		$echo[] = "sql log...OK";
		truncate_file(WS_ADMINISTERED_APPLICATION_FOLDER . '/logs/sql.log');

		$echo[] = "";
		$echo[] = "Cache applicative...OK";
		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/cache/cache/*.*');
		rm(WS_ADMINISTERED_APPLICATION_FOLDER . '/cache/templates_c/*.*');

		$echo[] = "";
		$echo[] = "Cache syst&egrave;me...OK";
		rm(WS_APPLICATION_FOLDER . '/cache/cache/*.*');
		rm(WS_APPLICATION_FOLDER . '/cache/templates_c/*.*');

		$echo[] = "";
		$echo[] = "Op&eacute;ration termin&eacute;e.";




		echo "<html><head><title>Effacement de cache</title></head><body style='background-color: #111; padding: 10px;'>";
		echo "<pre style='color: #ccc'>";
		echo implode("\n", $echo);
		echo '</pre>';
		echo '</body></html>';
		$this->auth->logout();
	}

	function statify() {
		$echo = array();

		echo "<html><head><title>Statification du site</title></head><body style='background-color: #111; padding: 10px; color: #ccc;'>";
		echo "<pre>";

		echo " Statification du site\n";
		echo "========================\n";
		echo "\n";

		$app_config = new WSConfig;
		$app_config->load(dirname(__FILE__) . '/../../../application/config/');
		foreach ($app_config->get('languages') as $language) {
			$pages = new WSMenu($language);
			$first = true;
			foreach ($pages->menu as $p) {
				if ($p['cached'] == 1) {
					echo "Processing /" . $language . $p['path'] . "/...";
					$content = file_get_contents( 'http://' . $_SERVER["SERVER_NAME"] . '/' . $language . $p['path'] . '/');
					file_force_contents(dirname(__FILE__) . '/../../../' . $language . $p['path'] . '/index.html',$content);
					if ($first) {
						echo "OK !\n";
						echo "Processing /" . $language . "/...";
						file_force_contents(dirname(__FILE__) . '/../../../' . $language . '/index.html',$content);
						echo "OK !\n";
						if ($p['language'] == $app_config->get('default_language')) {
							echo "Processing /index.html";
							file_force_contents(dirname(__FILE__) . '/../../../index.html',$content);
						}
						$first = false;
					}
					flush();
					echo "OK !\n";
				}
			}
		}

		echo '</pre>';
		echo '</body></html>';
	}

	function destatify() {
		$echo = array();

		echo "<html><head><title>Statification du site</title></head><body style='background-color: #111; padding: 10px; color: #ccc;'>";
		echo "<pre>";

		echo " Déstatification du site\n";
		echo "========================\n";
		echo "\n";

		$app_config = new WSConfig;
		$app_config->load(dirname(__FILE__) . '/../../../application/config/');

		foreach ($app_config->get('languages') as $language) {
			echo "Processing " . $language . '...';
			rm(dirname(__FILE__) . '/../../../' . $language . '');
			flush();
			echo "OK !\n";
		}
		rm(dirname(__FILE__) . '/../../../index.html');

		echo '</pre>';
		echo '</body></html>';
	}

	function dumpdatabase() {
		$app_config = new WSConfig;
		$app_config->load(dirname(__FILE__) . '/../../../application/config/');
		$database = $app_config->get($app_config->get('deployment'));

		global $DB;
		$dbname = 		$database['db_name'];
		$dbhost = 		$database['db_server'];
		$dbuser = 	$database['db_user'];
		$dbpass = 	$database['db_password'];
		global $VERSION;

		$return = '';
		$message = '';

		$creationstart=strtok(microtime()," ")+strtok(" ");

		$mailto="david@starplace.org";

		$subject="Backup DB";
		$from_name="Your trustworthy website";
		$from_mail="noreply@yourwebsite.com";
		$replyto = $from_mail;

		mysql_connect($dbhost, $dbuser, $dbpass);
		mysql_select_db($dbname);

		$tables = array();
		$result = mysql_query("SHOW TABLES");

		while($row = mysql_fetch_row($result))
			$tables[] = $row[0];

		foreach($tables as $table) {
			$result = mysql_query("SELECT * FROM $table");
			$return.= "DROP TABLE IF EXISTS $table;";
			$row2 = mysql_fetch_row(mysql_query("SHOW CREATE TABLE $table"));
			$return.= "\n\n".$row2[1].";\n\n";
			while($row = mysql_fetch_row($result)) {
				$return.= "INSERT INTO $table VALUES(";
				$fields=array();
				foreach ($row as $field)
					$fields[]="'".mysql_real_escape_string($field)."'";
				$return.= implode(",",$fields).");\n";
			}
			$return.="\n\n\n";
		}
		$filename='db-backup-'.date("Y-m-d-H.m.s").'.sql.bz2';
		$content=bzcompress($return,9);
		file_put_contents(WS_ADMINISTERED_APPLICATION_FOLDER . '/../backups/' . $filename, $content);
	}

function touchtable() {
		$echo = array();

		$echo[] = " Retouche de table ";
		$echo[] = "==================";
		$echo[] = "";
		$echo[] = "";


		echo "<html><head><title>Touch table</title></head><body style='background-color: #111; padding: 10px; color: #ccc;'>";
		echo "<pre>";
		echo implode("\n", $echo);
		echo '</pre>';
		if (!isset($_POST['post'])) {
			echo "<form method='post'>";
			$defined = MyActiveRecord::FindAll('tabledefinitions', null, 'name asc');
			echo "<select name='selected_table'>";

			foreach ($defined as $item) {
				echo "<option value='{$item->name}'>{$item->name}</option>";
			}
			echo "</select>";

			echo "<input name='post' type='submit' value='OK'>";
			echo "</form>";
			die();
		}


		// Lets import the table into tabledefintions
		$stable = $_POST['selected_table'];

		d($stable);
		require_once(WS_ADMINISTERED_APPLICATION_FOLDER . '/models/' . ucwords($stable) . '.class.php');
		foreach(MyActiveRecord::FindAll($stable) as $record) {
			$record->saveadvanced(true);
		}
		die();

}

function touchcontent() {
		require_once(WS_ADMINISTERED_APPLICATION_FOLDER . '../admin/application/models/Contents.class.php');
		foreach(MyActiveRecord::FindAll('Contents') as $record) {
			d($record->title);
			$record->content_1 = str_replace('../../..', '', $record->content_1);
			$record->content_2 = str_replace('../../..', '', $record->content_2);
			$record->content_3 = str_replace('../../..', '', $record->content_3);
			$record->content_4 = str_replace('../../..', '', $record->content_4);
			$record->content_5 = str_replace('../../..', '', $record->content_5);
//			d($record->content_1);
			$record->save();
		}
		die();
}

function retouch() {
	if (isset($this->params[0])) {
		require_once(WS_ADMINISTERED_APPLICATION_FOLDER . '../admin/application/models/Contents.class.php');
		$id = $this->params[0];
		d($id);
		$record = MyActiveRecord::FindById('Contents', $id);
		if ($record) {
			d($record->content_1);
			d(base64_encode($record->content_1));
		}
	}
}

	function import_table_definition() {
		$echo = array();

		$echo[] = " Importation de Table existante dans les definitions";
		$echo[] = "=====================================================";
		$echo[] = "";
		$echo[] = "";


		echo "<html><head><title>Importation DB2DEF</title></head><body style='background-color: #111; padding: 10px; color: #ccc;'>";
		echo "<pre>";
		echo implode("\n", $echo);
		echo '</pre>';
		if (!isset($_POST['post'])) {
			echo "<form method='post'>";
			echo "<select name='selected_table'>";
			$existing = MyActiveRecord::Tables();
			$defined = MyActiveRecord::FindAll('tabledefinitions', null, 'name asc');
			$to_import = array();

			foreach ($existing as $e) {
				$found = false;
				foreach ($defined as $d) {
					if ($e == $d->name) {
						$found = true;
					}
				}
				if (!$found) {
					$to_import[] = $e;
				}
			}

			foreach ($to_import as $item) {
				echo "<option value='$item'>$item</option>";
			}
			echo "</select>";

			echo "<input name='post' type='submit' value='OK'>";
			echo "</form>";
			die();
		}


		// Lets import the table into tabledefintions
		$stable = $_POST['selected_table'];
		$tdef = MyActiveRecord::Create('tabledefinitions');
		$tdef->title = ucwords($stable);
		$tdef->name = $stable;
		$tdef->sortparams = 'title asc';
		$tdef->filtrable = 0;
		$tdef->childtable = '';
		$tdef->description = 'Imported @ ' . date(DATE_RFC822) . ' by Parameters::AdvancedTools::TableImporter';
		$tdef->saveadvanced('create', true);

		// Load up the model we just created and get the structure of the table
		require_once(WS_ADMINISTERED_APPLICATION_FOLDER . '/models/' . ucwords($stable) . '.class.php');
		$rd = MyActiveRecord::Columns($stable);

		// Determine the default sort field
		$first = true;
		$found = false;
		foreach ($rd as $def) {
			if ($first && substr($def['Type'], 0, 7) == 'varchar') {
				$first = false;
				$tdef->sortparams = $def['Field'] . ' asc';
				$found = true;
			}
		}
		if ($found) {
			$tdef->save();
		}
		else {
			$first = true;
			$found = false;
			foreach ($rd as $def) {
				if ($first && substr($def['Type'], 0, 3) == 'int') {
					$first = false;
					$tdef->sortparams = $def['Field'] . ' asc';
					$found = true;
				}
			}
			if ($found) {
				$tdef->save();
			}
		}

		d($rd);
		// Import individual fields
		$found_language = false;
		$found_title = false;
		$porder = 0;
		foreach ($rd as $def) {
			$porder++;
			$tr = MyActiveRecord::Create('tablefields');
			$tr->language = 'fr';
			$tr->tabledefinitions_id = $tdef->id;
			$tr->title = $def['Field'];
			$tr->name = ucwords($def['Field']);
			$tr->porder = $porder;
			switch ($tr->title) {
				case 'id':
				case 'language':
					$tr->showlist = 0;
					$tr->showedit = 0;
					$tr->listeditable = 0;
					break;
				default:
					$tr->showlist = 1;
					$tr->showedit = 1;
					$tr->listeditable = 0;
			}
			$tr->listeditable = 0;
			$tr->description = '';
			$tr->default = $def['Default'];
			$tr->filtre = 0;
			if (substr($def['Type'], 0, 7) == 'varchar') {
				$tr->type = WST_STRING;
			}
			else if (substr($def['Type'], 0, 3) == 'int') {
				$tr->type = WST_INTEGER;
			}
			else if (substr($def['Type'], 0, 4) == 'date') {
				$tr->type = WST_DATE;
			}
			else if (substr($def['Type'], 0, 4) == 'text') {
				$tr->type = WST_TEXT;
				$tr->showlist = 0;
			}
			if (endsWith($def['Field'], '_id')) {
				$tr->type = WST_TABLE_LINK;
				$tr->listeditable = 1;
			}

			$tr->save();

			if ($def['Field'] == 'language') {
				$found_language = true;
			}
			if ($def['Field'] == 'title') {
				$found_title = true;
			}
		}

		if (!$found_language) {
	    		$language = MyActiveRecord::Create('tablefields');
	    		$language->title = "language";
	    		$language->name = 'Langue';
	    		$language->tabledefinitions_id = $tdef->id;
	    		$language->porder = 2;
	    		$language->showlist = 0;
	    		$language->listeditable = 0;
	    		$language->showedit = 1;
	    		$language->description = "Langue.";
	    		$language->type = WST_STRING;
	    		$language->default = 'fr';
	    		$language->saveadvanced();
		}

		if (!$found_title) {
	    		$language = MyActiveRecord::Create('tablefields');
	    		$language->title = "title";
	    		$language->name = 'Titre';
	    		$language->tabledefinitions_id = $tdef->id;
	    		$language->porder = 3;
	    		$language->showlist = 0;
	    		$language->listeditable = 0;
	    		$language->showedit = 1;
	    		$language->description = "Titre.";
	    		$language->type = WST_STRING;
	    		$language->default = 'fr';
	    		$language->saveadvanced();
		}


		echo '</body></html>';
	}





	function duplicatetabledata() {
		$echo = array();

		$echo[] = " Copie du contenu d'une table d'une langue à l'autre";
		$echo[] = "=====================================================";
		$echo[] = "";
		$echo[] = "";


		echo "<html><head><title>Table duplication</title></head><body style='background-color: #111; padding: 10px; color: #ccc;'>";
		echo "<pre>";
		echo implode("\n", $echo);
		if (!isset($_POST['post'])) {
			echo '<p>Cette opération ne pourra pas être annulée.</p>';
			echo "<form method='post'>";

			// Table to duplicate
			$tables = MyActiveRecord::FindAll('tabledefinitions', null, 'title asc');

			// Language
			echo "<select name='selected_table'>";
			foreach ($tables as $table) {
				echo "<option value='" . $table->name . "'>" . $table->title . " (" . $table->name . ")</option>";
			}
			echo "</select>";


			// Languages
			$app_config = new WSConfig;
			$app_config->load(dirname(__FILE__) . '/../../../application/config/');

			echo ": <select name='source_language'>";
			foreach ($app_config->get('languages') as $language) {
				echo "<option value='$language'>$language</option>";
			}
			echo "</select>";

			echo " &rarr; <select name='destination_language'>";
			foreach ($app_config->get('languages') as $language) {
				echo "<option value='$language'>$language</option>";
			}
			echo "</select>";

			echo "<br/><label><input type='checkbox' name='destination_delete'>Détruire données dans la langue de destination</label><br/><br/>";



			echo "<input name='post' type='submit' value='OK'>";
			echo "</form>";
			die();
		}
		echo '</pre>';

		$table = $_POST['selected_table'];
		$slng  = $_POST['source_language'];
		$dlng  = $_POST['destination_language'];
		if (isset($_POST['destination_delete'])) {
			MyActiveRecord::Query("delete from $table where language = '$dlng'");
		}
		foreach (MyActiveRecord::FindAll($table, "language = '$slng'") as $record) {
			$nrecord = MyActiveRecord::Create($table);
			foreach (get_object_vars($record) as $key => $value) {
				if ($key != 'id') {
					$nrecord->$key = $value;
				}
				$nrecord->language = $dlng;
				$nrecord->save();
			}
		}
		echo "Duplication terminée.";

		echo '</body></html>';
	}


	function exportschema() {
		$echo = array();

		if (!isset($_POST['post'])) {
			$echo[] = " Exportation de tables dans un fichier de définition";
			$echo[] = "=====================================================";
			$echo[] = "";
			$echo[] = "";


			echo "<html><head><title>Exportation Tables</title><style>.ws-debug{padding:1em;font-size:9px;background:orange;color:black;}</style></head><body style='background-color: #111; padding: 10px; color: #ccc;'>";
			echo "<pre>";
			echo implode("\n", $echo);
			echo "<form method='post'>";
			echo "<select name='selected_table'>";
			$existing = MyActiveRecord::Tables();
			$defined = MyActiveRecord::FindAll('tabledefinitions', null, 'name asc');

			foreach ($defined as $item) {
				echo "<option value='{$item->name}'>{$item->name} ({$item->title})</option>";
			}
			echo "</select>";

			echo "<input name='post' type='submit' value='OK'>";
			echo "</form>";
			echo '</pre>';
			die();
		}
		$selected_table = $_POST['selected_table'];
		$table = MyActiveRecord::FindFirst('tabledefinitions', "name = '$selected_table'");
		$table->fields = $table->find_children('tablefields');

		$filename = date('Ymd@His') . '-' . $table->name . '.websites-schema.json';
		header("Content-disposition: attachment; filename=$filename");
		header('Content-type: application/json');
		echo json_encode($table);
	}

	function importschema() {
		$echo = array();
		$echo[] = " Importation de tables à partir d'un fichier de définition";
		$echo[] = "===========================================================";
		$echo[] = "";
		$echo[] = "";

		if (!isset($_POST['post'])) {


			echo "<html><head><title>Importation Tables</title><style>.ws-debug{padding:1em;font-size:9px;background:orange;color:black;}</style></head><body style='background-color: #111; padding: 10px; color: #ccc;'>";
			echo "<pre>";
			echo implode("\n", $echo);
			echo "<form method='post' enctype='multipart/form-data'>";
			echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000' />";

			echo "Fichier JSON de définition: <input name='file' type='file' />";

			echo "<input name='post' type='submit' value='OK'>";
			echo "</form>";
			echo '</pre>';
			die();
		}
		echo "<html><head><title>Importation Tables</title><style>.ws-debug{padding:1em;font-size:9px;background:orange;color:black;}</style></head><body style='background-color: #111; padding: 10px; color: #ccc;'>";
		echo "<pre>";

		$definition = file_get_contents($_FILES['file']['tmp_name']);
		$definition = json_decode($definition);

		//d($definition);

		$echo[] = "";
		$echo[] = "Table: {$definition->name} ({$definition->title})";

		$table = MyActiveRecord::Create('tabledefinitions');
		$table->language 			= $definition->language;
		$table->title 				= $definition->title;
		$table->name 				= $definition->name;
		$table->description 		= $definition->description;
		$table->sortparams 			= $definition->sortparams;
		$table->filtrable 			= $definition->filtrable;
		$table->childtable 			= $definition->childtable;
		$table->system 				= $definition->system;
		$table->rss 				= $definition->rss;
		$table->rss_sublink 		= $definition->rss_sublink;
		$table->porder 				= $definition->porder;
		$table->inlineadd 			= $definition->inlineadd;
		$table->monolanguage 		= $definition->monolanguage;
		$table->default_language	= $definition->default_language;
		$table->menu_fr 			= $definition->menu_fr;
		$table->saveadvanced('create');
//		d('tableid: '. $table->id);
		$echo[] = "Table created.";
		// Loading class definition for newly created table
		require(WS_ROOT . "/application/models/{$table->name}.class.php");
		$echo[] = "Creating fields...";
		foreach($definition->fields as $fdefinition) {
//			d($fdefinition);
			if ($fdefinition->title == 'id') continue;
			if ($fdefinition->title == 'language') continue;
			if ($fdefinition->title == 'title') continue;
			if ($fdefinition->title == 'titleseo') continue;
			$field = MyActiveRecord::Create('tablefields');
			$field->language 				= $fdefinition->language;
			$field->title 					= $fdefinition->title;
			$field->name 					= $fdefinition->name;
			$field->tabledefinitions_id 	= $table->id;
			$field->porder 					= $fdefinition->porder;
			$field->showlist 				= $fdefinition->showlist;
			$field->listeditable 			= $fdefinition->listeditable;
			$field->showedit 				= $fdefinition->showedit;
			$field->description 			= $fdefinition->description;
			$field->type 					= $fdefinition->type;
			$field->format_en 				= $fdefinition->format_en;
			$field->format_fr 				= $fdefinition->format_fr;
			$field->format_es 				= $fdefinition->format_es;
			$field->filtre 					= $fdefinition->filtre;
			$field->default 				= $fdefinition->default;
			$field->width 					= $fdefinition->width;
//			d($field);
//			$field->save();
//			$id = $field->id;
//			$field = MyActiveRecord::FindById('tablefields', $id);
			$field->saveadvanced('create', true);
			$echo[] = "Added field {$field->title} ($field->name)";
		}

		echo implode("\n", $echo);
		echo '</pre>';
	}

	function phpinfo() {
		phpinfo();
	}

	function showresources() {

		$files_all = glob_recursive(WS_ADMINISTERED_APPLICATION_FOLDER . "../public/*.*");
		$realbasedir = WS_ADMINISTERED_APPLICATION_FOLDER . '..';
		foreach($files_all as &$file) {
			$file = substr($file, strlen($realbasedir));
		}
		$files_used = array();
		$files_unused = array();

		// Process contents
		foreach(MyActiveRecord::FindAll('contents') as $content) {
			foreach($files_all as $file) {
				if (strpos($content->content_1, $file) !== false){
					$files_used[] = $file;
					// d($file);
					// d($content->content_1);
					// die();
				}
				if (strpos($content->content_2, $file) !== false){
					$files_used[] = $file;
				}
				if (strpos($content->content_3, $file) !== false){
					$files_used[] = $file;
				}
				if (strpos($content->content_4, $file) !== false){
					$files_used[] = $file;
				}
				if (strpos($content->content_5, $file) !== false){
					$files_used[] = $file;
				}
			}
		}

		// Process all tables, look for any field of type text, longtext or varchar
		foreach(MyActiveRecord::FindAll('tabledefinitions') as $tdef) {
			if ($tdef->name == 'tabledefinitions') continue;
			if ($tdef->name == 'tablefields') continue;
			if ($tdef->name == 'groups') continue;
			if ($tdef->name == 'filters') continue;
			$sfields = array();
			foreach($tdef->find_children('tablefields') as $tfield) {
				switch($tfield->type) {
					case WST_STRING:
					case WST_IMAGE:
					case WST_FILE:
					case WST_TEXT:
					case WST_HTML:
					case WST_CODE:
						$sfields[]= $tfield->title;
					break;
				}
			}
			foreach(MyActiveRecord::FindAll($tdef->name) as $record) {
				foreach($sfields as $field) {
					foreach($files_all as $file)
					if (strpos($record->{$field}, $file) !== false) {
						$files_used[] = $file;
					}
				}
			}

		}
		sort($files_used);
		$files_used = array_unique($files_used);
		sort($files_unused);
		$files_unused = array_diff($files_all, $files_used);

		$echo = array();
		$echo[] = " Utilisation du repertoire /public/ ";
		$echo[] = "====================================";
		$echo[] = "";
		$echo[] = "";

		echo "<html><head><title>Importation Tables</title><style>.ws-debug{padding:1em;font-size:9px;background:orange;color:black;}</style></head><body style='background-color: #111; padding: 10px; color: #ccc;'>";
		echo "<pre>";
		echo implode("\n", $echo);
		echo "Fichiers utilisés:\n";
		echo "------------------\n";
		echo implode("\n", $files_used);
		echo "\n\n";
		echo "Fichiers non utilisés:\n";
		echo "----------------------\n";
		echo implode("\n", $files_unused);
		echo '</pre>';



	}

}


function glob_recursive($pattern, $flags = 0) {
	$files = glob($pattern, $flags);
	foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
		$files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
	}
	return $files;
}
