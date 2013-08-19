<?php

/**
 *	RNAME: Utilisateurs - Changement de Profil
 *	PATH: 
 *	NOTE: Affiche un formulaire de changement de profil pour l'utilisateur connecté, n'affiche rien sinon.
 *	VERSION: 2.0
 *	ACTIVE: NO
 *
 */

WSLoader::load_helper('forms-advanced');
WSLoader::load_support('mail');
WSLoader::load_base('templates');

class WUserProfile extends WSController {
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
		$this->language = $this->params['language'];

		if (!$params['auth']->getAuth()) return '';
		
		$this->_load_user($params['auth']->getUsername());
		
		$t = new Template();
        $t->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/";
   	    
   	    $form_code = $this->_get_modify_form('');
   	    
		$t->assign('form_code', $form_code);

        return $t->fetch(get_class() . '/index-' . $this->language . '.tpl');
	}
	
	function _load_user($user) {
		$this->current_user = MyActiveRecord::FindFirst('users', "username = '" . $user . "'");
		if (!$this->current_user) return '';
		
		$this->userstable = MyActiveRecord::FindFirst('tabledefinitions', "name = 'users'");
		if (!$this->userstable) return '';

		$this->userstablefields = $this->userstable->find_children('tablefields', null, "porder asc");
	}
	
	function _get_modify_form($message) {
		$code = array();
		$rich_editors = array();

		$t = new Template();
   	    $t->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/";

		$t->assign('username', $this->current_user->username);

		foreach ($this->userstablefields as $field) {
			if ( ($field->title == 'id') || ($field->title == 'language') || ($field->title == 'groups_id') ) continue;
			if ($field->showedit == 0) continue;

			$form_field_title = $field->title;

			if ($field->title == 'username') {
				$code[] = generate_hidden($form_field_title, $this->current_user->{$field->title});
				continue;
			}
			

			switch ($field->type) {
				case WST_PASSWORD:
					$code[] = generate_input_password( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', '', false, nl2br($field->description) );
				break;
				case WST_TEXT:
					$code[] = generate_text_area( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $this->current_user->{$field->title}, 8, 60, '', false, nl2br($field->description) );
				break;
				default:
					if ($form_field_title != 'userstypes_id') {
						$code[] = generate_input_text( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $this->current_user->{$field->title}, '', false, nl2br($field->description) );
					}
				break;
			}
		}

		$code[] = generate_hidden('current_language', $this->language);
		
		$code[] = "</form>";
		$t->assign('questions_code', implode('', $code));
		return $t->fetch(get_class() . '/form-' . $this->language . '.tpl');
	}

	function change() {
		$this->params = array();
		$this->params['path'] = 'user-profile';

		if (!$this->auth->getAuth()) die('BAD_AUTH');
		if ($this->auth->getUsername() != $_POST['username']) die('BAD_USERNAME');
		$record = MyActiveRecord::FindFirst('users', "username = '" . $_POST['username'] . "'");
		if (!$record) die('NO_RECORD');
		
		$this->_load_user($record->username);
		
		$errors = false;
		foreach($_POST as $pkey => $pvalue) {
			switch ($pkey) {
				case 'username':
				case 'id':
				case 'language':
				case 'groups_id':
					continue;
				break;
				case 'current_language':
					$this->language = $_POST['current_language'];
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
				default:
					$record->{$pkey} = $pvalue;
				break;
			}
		}
		if (!$errors) {
			// send email to user to say the account has changed ?
			$record->save();
			echo 'OK';
		}


	}
	
}
