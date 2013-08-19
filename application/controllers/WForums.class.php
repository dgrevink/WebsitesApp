<?php

/**
 *	RNAME: Forums - Forum complet
 *	PATH: 
 *	NOTE: Affiche un forum complet avec tous les threads du site.
 *	VERSION: 1.0
 *	ACTIVE: NO
 *
 */

WSLoader::load_helper('database');
WSLoader::load_helper('forms-advanced');
WSLoader::load_base('settings');

class WForums extends WSController {
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
		
		$settings = new WSSettings();
		$profile_url = $settings->get('profils');
		
		$t = new Template();
        $t->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/" . $this->params['path'];

		$connected = false;
		
		// browse list of all threads
		if (!$this->params['urlparam']) {
			$t->assign('browsing_top', true);
			$threads = activerecord2smartyarray(MyActiveRecord::FindAll('forumthreads'));
			foreach($threads as &$thread) {
				$users = MyActiveRecord::FindAll('users');
				$thread['user_username'] = $users[$thread['users_id']]->username;
				$thread['user_title'] = $users[$thread['users_id']]->title;
				$thread['user_profile_url'] = "$profile_url/+{$users[$thread['users_id']]->titleseo}";
			}
			$t->assign('threads', $threads);
		}
		// else show particular thread
		else {
			$thread = MyActiveRecord::FindFirst('forumthreads', 'titleseo = "' . $this->params['urlparam'][0] . '"');
			if ( $this->params['auth']->getAuth() ) {
				$user = MyActiveRecord::FindFirst('users', "username like '" . $this->params['auth']->getUsername() . "'");
				if (!$user) {
					$t->assign('connected', false);
				}
				else {
					$connected = true;
					$t->assign('username', $user->title);
					// Check if form has been posted
					if (isset($_POST['posted'])) {
						$_POST['comment'] = trim($_POST['comment']);
						if ( $_POST['comment'] ) {
							$page_title = $this->params['template']->_tpl_vars['ws_title'];
							if (!isset($_POST['relatedpostid'])) {
								$this->insertcomment($thread, $user, $page_title, $_POST['comment']);
							}
							else {
								$this->insertcomment($thread, $user, $page_title, $_POST['comment'], $_POST['relatedpostid']);
							}
						}
					}
				}
			}
			
			$thread = MyActiveRecord::FindFirst('forumthreads', 'titleseo = "' . $this->params['urlparam'][0] . '"');
			if ($thread) {
				$user = MyActiveRecord::FindById('users', $thread->users_id);
				$t->assign('user', $user);
				$comments = $thread->find_children('forumcomments', null, 'lineage asc');
				$commentcode = '';
				foreach($comments as &$comment) {
					$comment_user = $comment->find_parent('users');
					$comment->username = $comment_user->title;
					$comment->profile_url = "$profile_url/+{$user->titleseo}";
					$comment->thread_id = $thread->id;
					$comment->thread_title = $thread->title;
					$c = new Template();
					$c->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/" . $this->params['path'];
					$c->assign('comment', $comment);
					$c->assign('user', $user);
					$c->assign('connected', $connected);
					$commentcode .= $c->fetch(get_class() .  '/comment-' . $this->params['language'] . '.tpl');
				}
				
				$t->assign('comments', $commentcode);
			}
		}

		$t->assign('connected', $connected);

        return $t->fetch(get_class() . '/index-' . $this->params['language'] . '.tpl');
	}

	// increases lineage according to this example:
	/*
		Each node in the lineage is made up of a six digit number. If the number is not 6 digits it's prepadded with zeros. Each of these nodes is called a "rank". Since the rank is fixed width we have no need for a separator character, and we can also use it to order our result set.
		
		The first post would have a lineage of:		000001
		The second would be:						000002
		And a reply to to the first would be:		000001000001
		And a reply to that would be:				000001000001000001
		
		And pretty soon you end up with:
		000001 000002 000001000001 000001000001000001 000002000001 000001000002 000001000002000001 and so on.
		
		So now I can get the original post and the replies in order if I want simply by saying:
		
		select * from post where lineage like '000001%' order by lineage;
		
		And a single indexed column provides a speedy response.
		
	*/	
	function inclineage($lineage) {
		$value = substr($lineage, strlen($lineage) - 6);
		$value ++;
		$value = sprintf('%06s', $value);
		$lineage = substr($lineage, 0, strlen($lineage) - 6) . $value;
		return $lineage;
	}
	
	// inserts a comment in the forumcomments table, created the thread if necessary
	function insertcomment($thread, $user, $title, $comment, $relatedid = null) {
		$content = nl2br(htmlentities($comment, ENT_NOQUOTES, 'UTF-8'));
		$comment = MyActiveRecord::Create('forumcomments');
		$comment->language = $user->language;
		$comment->forumcomments_id = null;
		$comment->users_id = $user->id;
		$comment->title = $title;
		$comment->content = $content;
		$comment->postdate = date('Y-m-d H:i:s');
		$comment->level = 1;
		$comment->lineage = '000001';
		$last = MyActiveRecord::FindFirst('forumcomments', 'level = 1','lineage desc' );
		if ($last) {
			$comment->lineage = $this->inclineage($last->lineage);
		}
		if ($relatedid) {
			$relatedpost = MyActiveRecord::FindById('forumcomments', $relatedid);
			if ($relatedpost) {
				$comment->forumcomments_id = $relatedpost->id;
				$comment->level = $relatedpost->level + 1;
				// get the last lineage
				$last = MyActiveRecord::FindFirst('forumcomments', "forumcomments_id = {$relatedpost->id}",'lineage desc' );
				if (!$last) {
					$comment->lineage = $relatedpost->lineage . '000001';
				}
				else {
					$comment->lineage = $this->inclineage($last->lineage);
				}
			}
		}
		if (!$thread) {	// We have no thread, so we create one !
			$thread = MyActiveRecord::Create('forumthreads');
			$thread->language = $comment->language;
			$thread->title = $title;
			$thread->users_id = $comment->users_id;
			$thread->contents_id = $this->params['infos']['id'];
			$thread->url = $this->params['infos']['REQUEST_URI'];
			$thread->page_title = $title;
			$thread->createdate = $comment->postdate;
			$thread->saveadvanced();
		}
		$comment->forumthreads_id = $thread->id;
		$comment->saveadvanced();
		return $comment->id;
	}
	
	function dopost() {
		if (!isset($_POST['threadid']) || !isset($_POST['comment']) || !isset($_POST['title']) ) {
			die('badparam');
		}
		else {
			global $a;
			$thread = MyActiveRecord::FindById('forumthreads', $_POST['threadid']);
			if (!$thread) die('badparam');
			$comment = $_POST['comment'];
			$title = $_POST['title'];
			$user = MyActiveRecord::FindFirst('users', "username like '" . $a->getUsername() . "'");
			if (!$user) die('badparam');
			
			$forumcomments_id = 1;
			if (isset($_POST['relatedpostid'])) {
				$forumcomments_id = $_POST['relatedpostid'];
			}
			
			$id = $this->insertcomment($thread, $user, $title, $comment, $forumcomments_id);
			
			WSLoader::load_base('settings');
			$settings = new WSSettings();
			$profile_url = $settings->get('profils');
			
			WSLoader::load_base('templates');
			$c = new Template();
			$c->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/";
			$comment = MyActiveRecord::FindById('forumcomments', $id);
			$comment->thread_title = $title;
			$comment->username = $user->title;
			$comment->profile_url = "$profile_url/+{$user->titleseo}";
			$comment->thread_id = $thread->id;
			$c->assign('comment', $comment);
			$c->assign('user', $user);
			$c->assign('connected', true);
			echo $c->fetch(get_class() .  '/comment-' . $user->language . '.tpl');
			
		}
	}
}
