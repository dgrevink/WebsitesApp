<?php

/**
 *	RNAME: Utilisateurs - Profil public
 *	PATH: 
 *	NOTE: Affiche un profil public de l'utilisateur, y compris son activité sur le site.
 *	VERSION: 2.0
 *	ACTIVE: NO
 *
 */

WSLoader::load_helper('forms-advanced');
WSLoader::load_support('mail');
WSLoader::load_helper('database');
WSLoader::load_base('templates');

class WUserPublicProfile extends WSController {
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
		// urlparam		= false or current page parameters in url ( + delimited ) as an array
		if (!$params) { die('404'); }
		$this->params = &$params;

		$t = new Template();
        $t->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/";

        $userid = end($this->params['url']);
        if ($userid[0] != '+') die();
		$userid = substr($userid, 1);

		$user = MyActiveRecord::FindFirst('users', "titleseo = '$userid'");
		if (!$user) die();

		$t->assign('user', $user);
		$t->assign('sitename', $this->config->get('company'));

		$comments = $user->find_children('forumcomments', null, 'postdate desc');
		foreach($comments as &$comment) {
//			$comment->profile_url = "$profile_url/+{$user->titleseo}";
			$thread = $comment->find_parent('forumthreads');
			if ($thread) {
				$comment->thread_title = $thread->title;
				$comment->thread_url = $thread->url;
				$comment->thread_page_title = $thread->page_title;
			}
		}
		$t->assign('comments', activerecord2smartyarray($comments));

        return $t->fetch(get_class() . '/index-' . $this->params['language'] . '.tpl');
	}

}
