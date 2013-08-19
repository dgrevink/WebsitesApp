<?php

/**
 *	RNAME: Utilisateurs - Logout
 *	PATH: 
 *	NOTE: Affiche un bouton de d&eacute;connection si une session s&eacute;curis&eacute;e est en cours (logout).
 *	VERSION: 2.0
 *	ACTIVE: NO
 *
 */

class WUserLogout extends WSController {
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
		$this->language = $params['language'];

		if (!$params['auth']->getAuth()) {
			return '';
		}
		else {
			$t = new Template();
	        $t->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/";
			$t->assign('action', $params['infos']['REQUEST_URI']);
			$this->current_user = MyActiveRecord::FindFirst('users', "username = '" . $params['auth']->getUsername() . "'");
			if ($this->current_user) {
				$t->assign('user_longname', $this->current_user->title);
			}
	        return $t->fetch(get_class() . '/index-' . $this->language . '.tpl');
		}
	}
	
}
