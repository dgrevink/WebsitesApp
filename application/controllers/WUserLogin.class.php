<?php

/**
 *	RNAME: Utilisateurs - Login et Logout
 *	PATH: 
 *	NOTE: Affiche un formulaire de connection (login) - mettre sur une page de type: 'Page de Login'.
 *	VERSION: 2.0
 *	ACTIVE: NO
 *
 */

class WUserLogin extends WSController {

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

		$t = new Template();
        $t->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/";

		$allowed = false;

		if (!$params['auth']->getAuth()) {
			$t->assign('action', $params['infos']['REQUEST_URI']);
			$t->assign('error_message', $params['auth']->getAuthData('error_message'));
		}
		else {
			// Check if group is allowed on this page, else output error
			$user = MyActiveRecord::FindFirst('users', "username like '" . $params['auth']->getUsername() . "'");
			if ($user) {
				$t->assign('user_longname', $user->title);
				$group = $user->find_parent('groups');
				if ($group) {
					// Get the real ID for the real page, not the current login page
					$menu = new WSMenu($this->params['language']);
					$url = chop(substr($params['infos']['REQUEST_URI'], 3),'/');
					if ($url == '') $url = '/';												// check for root
					$urlparam_pos = strpos($url, '+');										// check for + params
					if ($urlparam_pos > 0) { $url = substr($url, 0, $urlparam_pos-1); }		// check for + params
					$real_page_id = $menu->get_id($url, $this->params['language']);
					$infos = ($menu->get($real_page_id));

					if (in_array('-1', $infos['access'])) {
						$allowed = true;
					}
					else {
						if (in_array($group->id, $infos['access'])) {
							$allowed = true;
						}
					}
				}
			}
			if (!$allowed) {
				$params['auth']->logout();
				$params['auth']->setAuthData('error_message', 'NO-PAGE-ACCESS');
				header('Location: ' . $params['infos']['REQUEST_URI']);
				die();
			}
		}

		if ($params['auth']->getStatus() == AUTH_WRONG_LOGIN) {
			$t->assign('error_message', 'BAD-LOGIN-OR-PASSWORD');
		}
		if ($params['auth']->getStatus() == AUTH_IDLED) {
			$t->assign('error_message', 'IDLED');
		}

		if ($allowed) {
		    return $t->fetch(get_class() . '/logout-' . $this->params['language'] . '.tpl');
		}
		else {
		    return $t->fetch(get_class() . '/index-' . $this->params['language'] . '.tpl');
		}

	}

}
