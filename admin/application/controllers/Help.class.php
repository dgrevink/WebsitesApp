<?php

# Help Module
# Load and displays files starting with help/[page_name]-[language].html from the views directory.
#

class Help extends WSController {

	public $module_name = 'Help';
	public $module_version = '0.1';

	function Help($smarty=null, $language=null, $current_language=null, $params=null, $parameters=null, &$auth=null) {
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
		if (!$this->_check_rights(WSR_HELP)) {
			return false;
		}

		$page = (isset($this->params[2]))?$this->params[2]:'index';
		return $this->load_page( $this->application_folder . 'views/help/' . $page . '-' . $this->language . '.html' );
	}
	
	function show() {
		$text = false;
		$text_id = 0;
		$apath = $_POST['path'];

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
			if ($this->_check_data_rights('notes', 'modify')) {
				echo "<a class='doc-edit-button' href='/admin/fr/tables/notes/edit/" . $text_id . "' target='_blank'>Modifier cette documentation</a>";
			}
			echo $text;
		}

		if (!$text) echo 'KO';
	}

	function load_page( $page ) {
		if (file_exists( $page )) {
			$page = file_get_contents($page);
			$page = str_ireplace('images/', '/admin/application/views/help/images/', $page);
			return $page;
		}
		else {
			return null;
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


	function _check_data_rights( $table, $role='view' ) {
		$user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
		$user_group = $user->find_parent('groups');
		$tables 	= unserialize($user_group->datarights);
		if ($role != 'view') {
			$table = $table . '_' . $role;
		}
		if (!isset($tables[$table])) {
			return false;
		}
		else {
			return true;
		}
	}

}

?>