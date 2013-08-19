<?php

WSLoader::load_base('log');
WSLoader::load_helper('forms-advanced');
WSLoader::load_base('log');

/**
 * Forms creator
 *
 * @package	admin
 * @subpackage	forms
 * @category	Forms
 * @author	David Grevink
 * @link	http://websitesapp.com/
 */
class Forms extends WSController {

	public $module_name = 'Forms';
	public $module_version = '2.0';

	/**
	 * Class Constructor
	 *
	 * @access	public
	 * @param	$smarty	string
	 * @param	$language string
	 * @param	$current_language
	 * @param	$params array
	 * @param	$parameters array
	 * @param	$auth object
	 * @return	class instance
	 */
 	function Forms($smarty=null, $language=null, $current_language=null, $params=null, $parameters=null, &$auth=null) {
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

	/**
	 * Generates the display for the form editor
	 *
	 * @access	public
	 * @return	generated code
	 */
	function _index() {
		if (!$this->_check_rights(WSR_FORMS)) {
			return false;
		}

		$smarty_contents = new Template;

		# Set current language
		if (isset($_POST['current_language'])) {
			$_SESSION['ws_admin']['editlanguage'] = $_POST['current_language'];
		}
		# $current_language = (isset($_POST['current_language']))?$_POST['current_language']:$this->auth->session['editlanguage'];
		$smarty_contents->assign('current_language', $this->current_language);

		# Set available languages	
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
		$smarty_contents->assign('languages', $languages);

		$smarty_contents->assign('WSR_FORMS_CREATE',		($this->_check_rights(WSR_FORMS_CREATE)) );
    	$smarty_contents->assign('WSR_FORMS_QUESTIONS',	($this->_check_rights(WSR_FORMS_QUESTIONS)) );
    	$smarty_contents->assign('WSR_FORMS_CONSULT',		($this->_check_rights(WSR_FORMS_CONSULT)) );
    	$smarty_contents->assign('WSR_FORMS_TEXTS', 		($this->_check_rights(WSR_FORMS_TEXTS)) );
		
		$smarty_contents->assign('current_page_title', 'Formulaires');
		$smarty_contents->assign('current_short_page_title', 'Formulaires');

		
		if (!isset($this->params[2]) || (!is_numeric($this->params[2]))) {
			$forms_definition = MyActiveRecord::FindAll('formsdefinitions', "language = '" . $this->current_language . "'", 'title asc');
			
			$forms_temp = array();
			
			foreach($forms_definition as $form) {
				$responses = $form->find_children('formsresponses', null, 'responsedate desc');
				$latest = 0;
				foreach($responses as $response) {
					if (strtotime($response->responsedate) > strtotime($latest)) {
						$latest = $response->responsedate;
					}
				}
				$form->last_response = $latest;
				$form->total_responses = count($responses);
				$form->responses = $responses;
				$forms_temp[] = $form;
			}
			
			$forms = activerecord2smartyarray($forms_temp);
			$smarty_contents->assign('forms', $forms);
			$smarty_contents->assign('listing_type', 'list');
			return $smarty_contents->fetch( 'forms-list-' . $this->language . '.tpl' );
		}
		else {
			$smarty_contents->assign('listing_type', 'detail');
			$form = MyActiveRecord::FindById('formsdefinitions', $this->params[2]);

			$smarty_contents->assign('id', $form->id);
			$smarty_contents->assign('language', $form->language);

			$smarty_contents->assign('title', 			generate_input_text( 'form_title', 'Titre', $form->title, "", false, "Le nom de votre formulaire."));

			$smarty_contents->assign('introduction', 	$form->introduction);
			$smarty_contents->assign('thanks', 	$form->thanks);
			$smarty_contents->assign('errors', 	$form->errors);
			$smarty_contents->assign('mailuser', 	$form->mailuser);
			$smarty_contents->assign('mailadmin', 	$form->mailadmin);
			$smarty_contents->assign('contesterror', 	$form->contesterror);

			$smarty_contents->assign('emailsender', 			generate_input_checkbox( 'emailsender', 	'Courriel Utilisateur', $form->emailsender, "Envoie une copie du r&eacute;sultat du formulaire &agrave; la personne qui l'a rempli. Le syst&egrave;me prendra le premier champ de type courriel pour l'envoi.", false ) );
			$smarty_contents->assign('emailadmin', 			generate_input_checkbox( 'emailadmin', 	'Courriel Administrateur', $form->emailadmin, "Envoie une copie du r&eacute;sultat du formulaire &agrave; l'administrateur du site (actuellement: " . $config->get('contactmail') . " - <a href='/admin/fr/parameters/'>Changer</a> ).", false ) );

			$smarty_contents->assign('emailextra', 		generate_input_text( 'emailextra', 'Courriel Extra', $form->emailextra, "", false, "Addresses courriel suppl&eacute;mentaires (s&eacute;parer les courriels par des virgules)."));
			$smarty_contents->assign('userquestions', 	generate_input_checkbox( 'userquestions', 	'R&eacute;ponses client', $form->userquestions, "Si coch&eacute;, une copie des r&eacute;ponses du formulaire sera ajout&eacute;e en bas du courriel utilisateur.", false ) );

			$smarty_contents->assign('usecaptcha', 		generate_input_checkbox( 'usecaptcha', 	'Captcha', $form->usecaptcha, "Si coch&eacute;, un captcha sera affich&eacute; en bas du formulaire.", false ) );

			$types = array(
				'normal' => 'Ordinaire',
				'survey' => 'Sondage',
				'contest-email' => 'Concours - Condition: Courriel Unique'
			);
			$smarty_contents->assign('type', 			generate_select( 'form_type', 'Type', $types, $form->type, false, "Type de formulaire: Ordinaire, sondage ou concours." ) );

			$numbers = array();
			$numbers[1] = 1;
			$numbers[5] = 5;
			$numbers[10] = 10;
			$numbers[15] = 15;
			$numbers[20] = 20;
			$numbers[30] = 30;
			$numbers[40] = 40;
			$numbers[50] = 50;
			$numbers[75] = 75;
			$numbers[100] = 100;

			$smarty_contents->assign('max_questions',	generate_select( 'max_questions', '# de questions MAX', $numbers, $form->max_questions, false, "Nombre maximum de questions autoris&eacute;es." ) );
			
			$tables_temp = MyActiveRecord::FindAll('tabledefinitions', "language = '" . $this->language . "'", 'system asc, title asc');
			$tables = array();
			$tables[0] = '---';
			foreach ($tables_temp as $table) {
				if (!$table->system) {
					$tables[$table->id] = $table->title;
				}
			}

			$smarty_contents->assign('tabledefinitions_id', 			generate_select( 'tabledefinitions_id', 'Table', $tables, $form->tabledefinitions_id, false, "Fonction avanc&eacute;e: stockage compl&eacute;mentaire dans la table choisie. Les champs du formulaire correspondants &agrave; ceux de la table seront stock&eacute;s." ) );

			$smarty_contents->assign('locked', 			generate_input_checkbox( 'locked', 	'Formulaire Fig&eacute;', $form->locked, "Fonction avanc&eacute;e: les questions du formulaire sont consultables mais fig&eacute;es de fa&ccedil;on &agrave; emp&ecirc;cher leur modification.", false ) );

			$form->responses = $form->find_children('formsresponses', null, 'responsedate desc');

			switch ($form->type) {
				case 'survey':
					$question = array_pop(unserialize($form->questions));
					$values = array();
					foreach ($question->values as $value) {
						$values[$value] = 0;
					}
			
					$responses = $form->responses;
					$counter = 0;
					foreach ($responses as $response) {
						$key = array_pop(unserialize($response->values));
						$key = htmlentities($key, ENT_NOQUOTES, "UTF-8" );
						$values[$key]++;			
						$counter++;
					}
					
					$responses = array();		
					foreach($values as $key => $value) {
						if ($counter != 0) {
							$responses[] = array( 'Option' => $key, '%' => round( $value / $counter * 100 ) );
						}
					}
					$smarty_contents->assign('responses', $responses);
				break;
				default:
					$responses = array();
					foreach($form->responses as $response) {
						$responses[] = @array_merge(
							array( 'date' => $response->responsedate ), 
							unserialize($response->values)
						);
					}
					$smarty_contents->assign('responses', $responses);
				break;
			}
			
			return $smarty_contents->fetch( 'forms-edit-' . $this->language . '.tpl' );
		}

	}

	/**
	 * Saves form
	 *
	 * @access	public
	 * @param	$_POST the form details
	 * @return	OK or error message displayed through ajax
	 */
	function save() {
	
		// Clean Encoding
		// Takes a string and re-encodes in entities using
		// websites system default
		function ce($str) {
			return htmlentities($str, ENT_NOQUOTES, 'UTF-8');
		}

		$id = $_POST['id'];
		$title = ce($_POST['title']);
		
		$record = MyActiveRecord::FindById('formsdefinitions', $id);
		
		if ($record) {
		

			if ($this->_check_rights(WSR_FORMS_TEXTS)) {
				$record->introduction = $_POST['introduction'];
				$record->thanks = $_POST['thanks'];
				$record->errors = $_POST['errors'];
				$record->mailuser = $_POST['mailuser'];
				$record->mailadmin = $_POST['mailadmin'];
				$record->contesterror = $_POST['contesterror'];
			}

			if ($this->_check_rights(WSR_FORMS_CREATE)) {
				$record->title = ce($_POST['title']);
				$record->type = $_POST['type'];
				$record->emailsender = ($_POST['emailsender'] == 'true');
				$record->emailadmin = ($_POST['emailadmin'] == 'true');
				$record->emailextra = $_POST['emailextra'];
				$record->max_questions = $_POST['max_questions'];
				$record->locked = ($_POST['locked'] == 'true');
				$record->tabledefinitions_id = $_POST['tabledefinitions_id'];
				$record->userquestions = ($_POST['userquestions'] == 'true');
				$record->usecaptcha = ($_POST['usecaptcha'] == 'true');
			}


			if ($this->_check_rights(WSR_FORMS_QUESTIONS)) {
				$record->questions = array();
				
				for( $i=1 ; $i <= $_POST['total_questions'] ; $i++) {
					$question = null;
					$question->order 		= ce($_POST['questions_' . $i . '_order']);
					$question->name 		= ce($_POST['questions_' . $i . '_name']);
					$question->type			= ce($_POST['questions_' . $i . '_type']);
					$question->label 		= ce($_POST['questions_' . $i . '_label']);
					$question->type 		= ce($_POST['questions_' . $i . '_type']);
					$question->comment 		= ce(@$_POST['questions_' . $i . '_comment']);
					$question->error 		= ce($_POST['questions_' . $i . '_error']);
					$question->mandatory 	= ( $_POST['questions_' . $i . '_mandatory'] == 'true' );
					
					switch ($question->type) {
						case 'text':
						case 'longtext':
						case 'email':
						case 'hidden':
						case 'password':
							$question->values = ce($_POST['questions_' . $i . '_values']);
						break;
						case 'select':
						case 'radio':
						case 'multicheckbox':
							$values = ce($_POST['questions_' . $i . '_values'], ENT_COMPAT, 'UTF-8');
							$values = rtrim($values, '|');
							$question->values = explode('|', $values);
						break;
						case 'tablelink':
						case 'attachment':
							$question->params1 		= ce(@$_POST['questions_' . $i . '_params1']);
							$question->params2 		= ce(@$_POST['questions_' . $i . '_params2']);
						break;
					}
					
					$record->questions[$question->name] = $question;
				}
				
				$record->questions = serialize($record->questions);
			}
			
			if ($record->save()) {
				WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Form #' . $id . ' saved. ( ' . $title . ' ) ' );
				echo '{ "type": "info", "message": "Les donn&eacute;es pour le formulaire ' . $title . ' sont sauvegard&eacute;es !"}';
			}
			else {
				WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Form #' . $id . ' could NOT be saved. ( ' . $title . ' ) ' );
				echo '{ "type": "error", "message": "Les donn&eacute;es pour le formulaire ' . $title . ' ne sont PAS sauvegard&eacute;es !"}';
			}
		}

	}
	
	/**
	 * Delete
	 *
	 * @access	public
	 * @param	id $_POST
	 * @return	OK or error message displayed through ajax
	 */
	function delete() {
		$form_id = $_POST['id'];
		$record = MyActiveRecord::FindById('formsdefinitions', $form_id);
		if (!$record) {
			echo 'KO';
			return false;
		}
		$related = $record->find_children('formsresponses');
		foreach($related as $item) {
			$item->delete();
		}
		$record->delete();
		WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Form #' . $form_id . ' destroyed. ( ' . $record->title . ' ) ' );
		echo 'OK';
		return true;
	}
	
	/**
	 * Create empty form
	 *
	 * @access	public
	 * @param	language $_POST
	 * @param	name $_POST
	 * @return	OK or error message displayed through ajax
	 */
	function create() {
		$language = $_POST['language'];
		$name = $_POST['name'];
		$record = MyActiveRecord::Create('formsdefinitions');
		$record->language = $language;
		$record->title = $name;
		$questions = array();
		$record->questions = serialize($questions);
		$record->save();
		WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Created form #' . $record->id . ' ( ' . $record->title . ' ) ');
		echo 'OK';
		return true;
	}


	function _clean_post() {
		// Clean up _POST
		function process_value(&$value) {
			if (!is_array($value)) {
				$value = trim($value);
				$value = htmlentities($value, ENT_QUOTES, "UTF-8" );
			}
		}
		array_walk($_POST, 'process_value');
	}

	/**
	 * Check if a right is active for the current user.
	 *
	 * @access	public
	 * @param	$level int
	 * @see		Constants.class.php
	 * @return	true or false if the user has the right or not.
	 */
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
	
	/**
	 * Returns an array of questions representing the form as a
	 * JSON object.
	 *
	 * @access	public
	 * @param	id IN_URL ($this->params[0])
	 * @return	the questions as a JSON object
	 */
	function get_questions() {

		WSLoader::load_support('json');

		if (!isset($this->params[0])) {
			die('cannot be called directly.');
		}
		if (!is_numeric($this->params[0])) {
			die('cannot be called directly.');
		}
		
		$json = new Services_JSON;
		
		$form = MyActiveRecord::FindById('formsdefinitions', $this->params[0]);
		if ($form) {
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
			header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
			header("Cache-Control: no-cache, must-revalidate" ); 
			header("Pragma: no-cache" );
			header("Content-type: text/x-json");
			
			// We have to strip hash keys for smarty
			$questions = array();
			$q = unserialize($form->questions);
			foreach($q as $item) {
				$questions[] = $item;
			}

			print_r( $json->encode($questions) );
		}

	}
	
	/**
	 * Erases all the forms responses for the given form
	 *
	 * @access	public
	 * @param	id IN_URL ($this->params[0])
	 * @return	message as AJAX string
	 */
	function erase() {
		$id = $this->params[0];
		$form = MyActiveRecord::FindById('formsdefinitions', $id);
		foreach($form->find_children('formsresponses') as $record) {
			$record->destroy();
		}
		
		
		WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Destroyed all form responses for form #' . $form->id . ' ( ' . $form->title . ' ) ');
		echo "Toutes les r&eacute;ponses sont effac&eacute;es.";
	}
	
	/**
	 * Duplicates the given form
	 *
	 * @param	id IN_URL ($this->params[0])
	 * @return	message as AJAX string
	 */
	function duplicate() {
		$id = $_POST['id'];
		$form = MyActiveRecord::FindById('formsdefinitions', $id);
		
		$newform = MyActiveRecord::Create('formsdefinitions');
		
		$newform->language = 			$_POST['language'];
		$newform->type = 				$form->type;
		$newform->max_questions = 		$form->max_questions;
		$newform->title = 				$form->title . ' COPIE';
		$newform->introduction = 		$form->introduction;
		$newform->thanks = 				$form->thanks;
		$newform->errors = 				$form->errors;
		$newform->mailadmin = 			$form->mailadmin;
		$newform->mailuser = 			$form->mailuser;
		$newform->questions = 			$form->questions;
		$newform->emailsender = 		$form->emailsender;
		$newform->emailadmin = 			$form->emailadmin;
		$newform->emailextra = 			$form->emailextra;
		$newform->instructions =		$form->instructions;
		$newform->locked =				$form->locked;
		$newform->tabledefinitions_id =	$form->tabledefinitions_id;
		$newform->userquestions =		$form->userquestions;
		$newform->usecaptcha =			$form->usecaptcha;
		$newform->contesterror = 		$form->contesterror;
		
		$newform->save();
		
		
		WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Destroyed all form responses for form #' . $form->id . ' ( ' . $form->title . ' ) ');
		echo "Formulaire dupliquŽ.";
	}

}

?>