<?php

/**
 *	RNAME: Utilisateurs - D&eacute;sinscrire
 *	PATH: 
 *	NOTE: Affiche un bouton de d&eacute;inscription - mettre sur une page de type: 'Page de Login'.
 *	VERSION: 2.0
 *	ACTIVE: NO
 *
 */

class WUserRemove extends WSController {
	function index( $params = null ) {
		if (!$params) {
			die('404');
		}
		
		// Get the current language
		$this->language = $params['language'];

		if (!$params['auth']->getAuth()) {
			return '';
		}
		else {
			$t = new Template();
	        $t->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/";
			if (isset($_POST['remove'])) {
				$this->current_user = MyActiveRecord::FindFirst('users', "username = '" . $params['auth']->getUsername() . "'");
				$this->current_user->groups_id = -2; // Mettre au groupe utilisateurs désinscrits.
				$this->current_user->save();
				$params['auth']->logout();
				$t->assign('removed', true);
		        return $t->fetch(get_class() . '/index-' . $this->language . '.tpl');
			}
			else {
				$t->assign('action', $params['infos']['REQUEST_URI']);
				$this->current_user = MyActiveRecord::FindFirst('users', "username = '" . $params['auth']->getUsername() . "'");
				if ($this->current_user) {
					$t->assign('user_longname', $this->current_user->title);
					$t->assign('username', $this->current_user->username);
				}
		        return $t->fetch(get_class() . '/index-' . $this->language . '.tpl');
			}
		}
	}

}
