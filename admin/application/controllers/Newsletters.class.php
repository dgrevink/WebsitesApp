<?php

# Newsletter Module
# Handles newsletter

WSLoader::load_helper('database');
WSLoader::load_helper('misc');
WSLoader::load_base('log');
//WSLoader::load_base('templates');

class Newsletters extends WSController {

	public $module_name = 'Newsletters';
	public $module_version = '2.0';

	function Newsletters($smarty=null, $language=null, $current_language=null, $params=null, $parameters=null, &$auth=null) {
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
		if (!$this->_check_rights(WSR_NEWSLETTERS)) {
			return false;
		}

		$smarty_contents = new Template;
		
		$blocks = MyActiveRecord::FindAll('nblocks', "", 'language asc, title asc');
		$smarty_contents->assign('blocks', activerecord2smartyarray($blocks));

		$filters = MyActiveRecord::FindAll('filters', null, 'title asc');
		$smarty_contents->assign('filters', activerecord2smartyarray($filters));
		
		$newsletters_temp = MyActiveRecord::FindAll('newsletter', '', 'ddate asc, title asc');
		$newsletters = array();
		foreach($newsletters_temp as $news) {
			$news->block = @$blocks[$news->nblocks_id]->title;
			$news->filter = $filters[$news->filters_id]->title;
			$news->id = $news->id;
			$news->filter_total = count($news->find_children('newsletterusers', "readdate != '0000-00-00 00:00:00'")) . '/' . count($news->find_children('newsletterusers'));
			$news->status_text = WSConstants::$newsletter_statuses[$news->status];
			$newsletters[] = $news;
		}
		$news = activerecord2smartyarray($newsletters);

		$smarty_contents->assign('news', $news);

		$smarty_contents->assign('WSR_NEWSLETTERS_SEND',($this->_check_rights(WSR_NEWSLETTERS_SEND)) );
		
		return $smarty_contents->fetch('newsletters-index-' . $this->language . '.tpl' );
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
	
	function ddate() {
		$id = $this->params[0];
		$id = trim($id);
		$news = MyActiveRecord::FindById('newsletter', $id);
		if ($news) {
			echo $news->ddate;
		}
	}

	function status() {
		$id = $this->params[0];
		$id = trim($id);
		$news = MyActiveRecord::FindById('newsletter', $id);
		if ($news) {
			echo WSConstants::$newsletter_statuses[$news->status];
		}
	}

	function completion() {
		$id = $this->params[0];
		$id = trim($id);
		$news = MyActiveRecord::FindById('newsletter', $id);
		if ($news) {
			echo $news->completion;
		}
	}

	function toggle_status() {
		$id = $this->params[0];
		$news = MyActiveRecord::FindById('newsletter', $id);
		if ($news) {
			switch($news->status) {
				case WSN_PAUSED:
					$news->status = WSN_RUNNING;
					WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Newsletter #' . $news->id . ' is now running. (Title: ' . $news->title . ')' );
					$news->save();
					break;
				case WSN_RUNNING:
					$news->status = WSN_PAUSED;
					WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Newsletter #' . $news->id . ' is now stopped. (Title: ' . $news->title . ')' );
					$news->save();
					break;
				case WSN_ERROR:
				default:
			}
		}
	}

	function delete() {
		$id = $this->params[0];
		$news = MyActiveRecord::FindById('newsletter', $id);
		if ($news) {
			if ($news->delete()) {
				echo $news->id;
			}
		}
	}
	
	function create() {
		if (trim($_POST['title']) != '') {
			$news = MyActiveRecord::Create('newsletter');
			$news->title = $_POST['title'];
			$news->nblocks_id = $_POST['block'];
			$news->filters_id = $_POST['filters'];
			$block = MyActiveRecord::FindById('nblocks', $news->nblocks_id);
			$news->language = $block->language;
			if ($news->save()) {
				$filter = MyActiveRecord::FindById('filters', $news->filters_id);
				$results = $filter->getResults();
				$results_table = $filter->getTable();
				foreach($results as $result) {
					$contact = MyActiveRecord::Create('newsletterusers');
					$contact->title = method_exists($result, 'get_complete_title')?$result->get_complete_title():$result->title;
					$contact->email = $result->email;
					$contact->sourcetable = $results_table;
					$contact->sourcekey = $result->id;
					$contact->sent = 0;
					$contact->newsletter_id = $news->id;
					$contact->save();
					$contact->checkcode = md5($results_table . $contact->id);
					$contact->save();
				}
				WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Newsletter #' . $news->id . ' saved. (Title: ' . $news->title . ')' );
				echo '{ "type": "info", "message": "La newsletter ' . $news->title . ' a &eacute;t&eacute; cr&eacute;&eacute;e et est pr&ecirc;te &agrave; l\'envoi !"}';
			}
			else {
				WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Newsletter #' . $news->id . ' could NOT be saved. (Title: ' . $news->title . ')' );
				echo '{ "type": "error", "message": "Erreur &agrave; la cr&eacute;ation de la newsletter."}';
			}
		}
	}
	
	function getcontacts() {
		if ($this->auth->checkAuth()) {
			if ( (isset($this->params[0]) && (is_numeric($this->params[0])) ) ) {
				$code = array();
				$code[] = 'name,email,open,checkcode';
				$news = MyActiveRecord::FindById('newsletter', $this->params[0]);
				$users = $news->find_children('newsletterusers', null, 'email asc');
				foreach ($users as $user) {
					$code[] = utf8_accents_to_ascii(html_entity_decode( $user->title ,ENT_COMPAT,'utf-8')) . "," . $user->email . ',' . (($user->readdate!='0000-00-00 00:00:00')?$user->readdate:'') . ',' . $user->checkcode;
				}

				WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Contacts for newsletter #' . $news->id . ' downloaded.' );
				
				header("Content-type: application/octet-stream");
				header("Content-Disposition: attachment; filename=\"" . $news->title . " Contacts.csv\"");
				echo implode("\n", $code);
			}
		}
	}

	function getfailedcontacts() {
		if ($this->auth->checkAuth()) {
			if ( (isset($this->params[0]) && (is_numeric($this->params[0])) ) ) {
				$code = array();
				$code[] = 'name,email';
				$news = MyActiveRecord::FindById('newsletter', $this->params[0]);
				$users = $news->find_children('newsletterusers', 'sent > 1', 'email asc');
				foreach ($users as $user) {
					$code[] = utf8_accents_to_ascii(html_entity_decode( $user->title ,ENT_COMPAT,'utf-8')) . "," . $user->email;
				}

				WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Failed contacts for newsletter #' . $news->id . ' downloaded.' );

				header("Content-type: application/octet-stream");
				header("Content-Disposition: attachment; filename=\"" . $news->title . " Failed Contacts.csv\"");
				echo implode("\n", $code);
			}
		}
	}

	function process() {
		define('WSN_MAX_SENT', 20);
		define('WSN_INTERVAL', 1000000);
		
		echo date("d/m/y H:i:s") . " - Newsletters - ( " . WSN_MAX_SENT . " / " . WSN_INTERVAL . " ) Processing:\n";
		
		$done = false;
		
		$app_config = new WSConfig;
		$app_config->load(dirname(__FILE__) . '/../../../application/config/');

		
		$newsletters = MyActiveRecord::FindAll('newsletter', 'status = ' . WSN_RUNNING);
		if ($newsletters) {
			$maxcount = round(WSN_MAX_SENT / count($newsletters));
			
			foreach($newsletters as $newsletter) {

				WSLog::admin( WS_INFO, 'websites-system', 0, 'BEGIN Processing Newsletter #' . $newsletter->id . ' ( ' . $newsletter->title . ' ) ' );

				// init
				$block = $newsletter->find_parent('nblocks');
				$domain = "http://" . $_SERVER['HTTP_HOST'];
				$block->contents = str_ireplace("'/public/", "'" . $domain . "/public/", $block->contents);
				$block->contents = str_ireplace("\"/public/", "\"" . $domain . "/public/", $block->contents);
				
				// check if work is done
				$total = count($newsletter->find_children('newsletterusers'));
				$count = count($newsletter->find_children('newsletterusers', 'sent > 0'));
				if ($count == $total) {
					WSLog::admin( WS_INFO, 'websites-system', 0, 'PAUSED Newsletter #' . $newsletter->id . ' ( ' . $newsletter->title . ' ) ' );
					$newsletter->status = WSN_PAUSED;
					$newsletter->completion = 100;
				}
				else {
					// set start date if not set
					if ($newsletter->ddate == '0000-00-00') {
						$newsletter->ddate = date('Y-m-d');
					}
					
					// get maxcount first contacts
					$contacts = $newsletter->find_children('newsletterusers', 'sent = 0', null, $maxcount);
					
					// Build HTML
					$smarty = new Template;
					$smarty->template_dir = WS_APPLICATION_FOLDER . '/../../application/views/mails/';
					$smarty->assign('title', htmlentities($newsletter->title, ENT_NOQUOTES, 'UTF-8'));
					$track = base64_decode($app_config->get('uacct'));
					if ($track) {
						$track = str_ireplace('_trackPageview(', "_trackPageview(\"/SENT_NEWSLETTERS/" . htmlentities($newsletter->title, ENT_NOQUOTES, 'UTF-8') . "\"", $track);
					}
					
					$smarty->assign('message', $block->contents . $track);
					$smarty->assign('id', $newsletter->id);
					$smarty->assign('domain', $_SERVER['HTTP_HOST']);
				
					// loop
					foreach($contacts as $contact) {
						$smarty->assign('checkcode', $contact->checkcode);
						$html = $smarty->fetch( 'newsletter-' . $block->language . '.tpl');
						
						if (!sendHTMLemail($html,$app_config->get('contactmail'),utf8_accents_to_ascii(html_entity_decode( $contact->title ,ENT_COMPAT,'utf-8')) . " <" . $contact->email . ">",utf8_accents_to_ascii($newsletter->title))) {
							WSLog::admin( WS_ERROR, 'websites-system', 0, 'KO ' . $contact->email );
							echo(date("d/m/y H:i:s") . ' - KO ' . $contact->email . "\n");
							$contact->sent = 2;
							$contact->save();
						}
						else {
							$contact->sent = 1;
							$contact->save();
							WSLog::admin( WS_INFO, 'websites-system', 0, 'OK ' . $contact->email );
							echo(date("d/m/y H:i:s") . ' - OK ' . $contact->email . "\n");
						}
						$done = true;
						usleep(WSN_INTERVAL);
					}

					$count = count($newsletter->find_children('newsletterusers', 'sent > 0'));
					$newsletter->completion = round($count / $total * 100 );

					WSLog::admin( WS_INFO, 'websites-system', 0, 'Sent ' . $count . ' mails, ' . $newsletter->completion . ' % done.' );

					WSLog::admin( WS_INFO, 'websites-system', 0, 'END Processing Newsletter #' . $newsletter->id . ' ( ' . $newsletter->title . ' ) ' );

				}

				// clean up
				$newsletter->save();
				
			} // foreach newsletters
		} // if $newsletters
		
		if (!$done) {
			echo date("d/m/y H:i:s") . " - Nothing to do !\n";
		}
		else {
			echo date("d/m/y H:i:s") . " - Processing has been done !\n";
		}

	}

}










function sendHTMLemail($HTML,$from,$to,$subject)
{
// First we have to build our email headers
// Set out "from" address

    $headers = "From: $from\r\n"; 

// Now we specify our MIME version

    $headers .= "MIME-Version: 1.0\r\n"; 

// Create a boundary so we know where to look for
// the start of the data

    $boundary = uniqid("HTMLEMAIL"); 
    
// First we be nice and send a non-html version of our email
    
    $headers .= "Content-Type: multipart/alternative;".
                "boundary = $boundary\r\n\r\n"; 

    $headers .= "This is a MIME encoded message.\r\n\r\n"; 

    $headers .= "--$boundary\r\n".
                "Content-Type: text/plain; charset=ISO-8859-1\r\n".
                "Content-Transfer-Encoding: base64\r\n\r\n"; 
                
    $headers .= chunk_split(base64_encode(strip_tags($HTML))); 

// Now we attach the HTML version

    $headers .= "--$boundary\r\n".
                "Content-Type: text/html; charset=ISO-8859-1\r\n".
                "Content-Transfer-Encoding: base64\r\n\r\n"; 
                
    $headers .= chunk_split(base64_encode($HTML)); 

// And then send the email ....

    return mail($to,$subject,"",$headers);
    
}