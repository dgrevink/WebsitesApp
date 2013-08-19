<?php

/**
 *	RNAME: Utilisateurs - Inscription
 *	PATH: 
 *	NOTE: Affiche un formulaire d'inscription d'utilisateur, avec validation de compte par courriel. Passer le ID du groupe de l'utilisateur qui sera créé.
 *	VERSION: 2.0
 *	ACTIVE: NO
 *
 */

WSLoader::load_helper('forms-advanced');
WSLoader::load_support('mail');
WSLoader::load_base('templates');

class WUserCreate extends WSController {
	function index( $params = null ) {
		// $params has infos on the current page:
		// $this->params['url'] is the current page url as a vector for example.
		// other informations:
		// path			= path that can be used uniquely for this widget to store or access data
		// language		= current page language
		// name			= current widget name
		// param		= current widget parameters given by CMS
		// auth			= auth object
		// infos		= current page infos
		// template		= current page template object ( can be used to affect directly the page )
		// url			= current page as a vector
		if (!$params) { die('404'); }
		$this->params = &$params;
		
		// Get the current language
		$this->language = $this->pparams['language'];
		
		if ( !isset($this->pparams['param']) || !is_numeric($this->pparams['param']) ) {
			return "<div class='ws-debug'>WUserCreate ( Utilisateurs - Inscription ): PLEASE PROVIDE VALID GROUP ID</div>";
		}

		// If activation code is in URL, dont show profile activator
		$activation_string = end($this->params);
		if ( (isset($activation_string[0])) && ($activation_string[0] == '+') ) {
			$activation_string = substr($activation_string, 1, strlen($activation_string)-1);
			foreach (MyActiveRecord::FindAll('users', "groups_id = -2") as $unactivated_user) {
				if ( md5($unactivated_user->password) == $activation_string ) {
					$unactivated_user->groups_id = (int) $this->pparams['param'];
					$unactivated_user->save();
					$t = new Template();
			   	    $t->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/";
					return $t->fetch(get_class() . '/activated-' . $this->language . '.tpl');
				}
			}
			return 'ERROR ACTIVATE';
		}

		$this->_load_users_table();
		
		$t = new Template();
   	    $t->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/";
   	    
   	    $form_code = $this->_get_create_form('');
   	    
		$t->assign('form_code', $form_code);

        return $t->fetch(get_class() . '/index-' . $this->language . '.tpl');
	}
	
	function _load_users_table() {
		$this->userstable = MyActiveRecord::FindFirst('tabledefinitions', "name = 'users'");
		if (!$this->userstable) return '';

		$this->userstablefields = $this->userstable->find_children('tablefields', null, "porder asc");
	}
	
	function _get_create_form($message) {
		$code = array();
		$rich_editors = array();

		$t = new Template();
   	    $t->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/";

		$t->assign('return_page', $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);

		foreach ($this->userstablefields as $field) {
		
		
			if ($field->showedit == 0) continue;

			if ( ($field->title == 'id') || ($field->title == 'language') || ($field->title == 'groups_id') ) continue;

			switch ($field->type) {
				case WST_PASSWORD:
					$code[] = generate_input_password( $field->title, $field->name, '', false, nl2br($field->description) );
				break;
				case WST_TEXT:
					$code[] = generate_text_area( $field->title, $field->name, '', 8, 60, '', false, nl2br($field->description) );
				break;
				case WST_TABLE_LINK:
					$table_related = substr($field->title, 0, strlen($field->title) - 3);
//					$table_related_definition = MyActiveRecord::FindFirst('tabledefinitions', "name = '$table_related'");
					$additional_condition = '';
					if ($table_related == 'users') {
						$additional_condition = ' AND groups_id = 7'; // ACEF: show only associations
					}
					$related_records = MyActiveRecord::FindAll($table_related, "language = '" . $this->language . "'" . $additional_condition, "id asc");
					$related_select = array();
					foreach($related_records as $related_record) {
						if (method_exists($related_record, 'get_link_title')) {
							$link_title = $related_record->get_link_title('');
							if (!$link_title) {
								continue;
							}
							else {
								$related_select[$related_record->id] = $link_title;
							}
						}
						else {
							$related_select[$related_record->id] = $related_record->title;
						}
					}
					$code[] = generate_select( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $related_select, '', false, nl2br($field->description) . "\n");
				break;
				default:
					$code[] = generate_input_text( $field->title, $field->name, '', '', false, nl2br($field->description) );
				break;
			}
		}

		$code[] = generate_hidden('current_language', $this->language);
		$code[] = generate_hidden('default_g', $this->pparams['param']);

		$code[] = "</form>";
		$t->assign('questions_code', implode('', $code));
		return $t->fetch(get_class() . '/form-' . $this->language . '.tpl');
	}

	function create() {
		WSLoader::load_base('templates');
		$this->pparams = array();
		$this->pparams['path'] = 'user-create';
		$this->language = $_POST['current_language'];
		$this->return_page = $_POST['return_page'];

		$record = MyActiveRecord::Create('users');
		$record->groups_id = -2;
		
		$errors = false;
		foreach($_POST as $pkey => $pvalue) {
			switch ($pkey) {
				case 'username':
					if ( MyActiveRecord::Count('users', "username = '" . end($this->pparams) . "'") != 0 ) {
						$errors = true;
						echo 'USER_TAKEN';
					}
					else {
						$record->{$pkey} = $pvalue;
					}
					break;
				case 'current_language':
					$record->language = $_POST['current_language'];
					continue;
					break;
				case 'password':
					if (trim($pvalue) == '' ) continue;
					$record->password = md5($pvalue);
					continue;
					break;
				case 'email':
					if (!valid_email($pvalue)) {
						$errors = true;
						echo 'NO_OR_BAD_EMAIL';
					}
					$record->{$pkey} = $pvalue;
					break;
				default:
					$val = htmlentities($pvalue, ENT_NOQUOTES, 'UTF-8');
					if ($val == '') {
						$val = htmlentities($pvalue);
					}
					$record->{$pkey} = $val;
				break;
			}
		}
		if (!$errors) {
			$record->save();

			$tmail = new Template();
	        $tmail->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/";
	        $tmail->assign('sitename', $this->config->get('company'));
	        $tmail->assign('siteurl', 'http://'.$_SERVER['HTTP_HOST']);
	        $tmail->assign('activationurl', $this->return_page . '/+' . md5($record->password));

			$mail = new htmlMimeMail();
			
			$subject = utf8_accents_to_ascii(html_entity_decode($this->config->get('company')));

			$mail->setSubject($subject);
			$mail->setFrom($this->config->get('contactmail'));
			$mail->html = $tmail->fetch(get_class() . '/email-' . $this->language . '.tpl');

			$result = $mail->send(array($_POST['email']));

			echo 'OK';
		}
	}
	
	function userexists() {
		die( MyActiveRecord::Count('users', "username = '" . end($this->params) . "'") != 0 );
	}
	
}
