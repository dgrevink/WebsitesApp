<?php

/**
 *	RNAME: Utilisateurs - Mot de passe oubli&eacute;
 *	PATH: 
 *	NOTE: Affiche un formulaire de saisie de courriel d'utilisateur auquel on enverra un courriel pour changer son mot de passe.
 *	VERSION: 2.0
 *	ACTIVE: NO
 *
 */

WSLoader::load_support('mail');

class WUserForgotPassword extends WSController {
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

		$t = new Template();
        $t->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/";
		if (isset($_POST['email'])) {
			$generated_password = generate_password();
			$this->current_user = MyActiveRecord::FindFirst('users', "email = '" . $_POST['email'] . "'");
			if ($this->current_user) {
				$this->current_user->password = md5($generated_password);
				$this->current_user->save();
			}
			$t->assign('showretrieved', true);
			
			$tmail = new Template();
	        $tmail->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/" . $params['path'];
	        $tmail->assign('username', $this->current_user->username);
	        $tmail->assign('password', $generated_password);
	        $tmail->assign('sitename', $this->config->get('company'));
	        $tmail->assign('siteurl', 'http://'.$_SERVER['HTTP_HOST']);

			$mail = new htmlMimeMail();
			
			$subject = utf8_accents_to_ascii(html_entity_decode($this->config->get('company')));

			$mail->setSubject($subject);
			$mail->setFrom($this->config->get('contactmail'));
			$mail->html = $tmail->fetch(get_class() . '/email-' . $this->language . '.tpl');

			$result = $mail->send(array($_POST['email']));

	        return $t->fetch(get_class() . '/index-' . $this->language . '.tpl');
		}
		else {
			$t->assign('action', $params['infos']['REQUEST_URI']);
			$t->assign('showform', true);
	        return $t->fetch(get_class() . '/index-' . $this->language . '.tpl');
		}
	}

}
