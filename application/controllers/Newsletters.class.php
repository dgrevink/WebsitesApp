<?php

WSLoader::load_base('templates');
WSLoader::load_base('history');
include( 'admin/application/models/Wsthistory.class.php' );

class Newsletters extends WSController {
	function display() {
		if ( (isset($this->params[0])) && (is_numeric($this->params[0])) ) {
			$news = MyActiveRecord::FindById('newsletter', $this->params[0]);
			$block = $news->find_parent('nblocks');
			$smarty = new Template;
			$smarty->template_dir = WS_APPLICATION_FOLDER . '/views/mails/';
			$smarty->assign('title', $news->title);
				$domain = "http://" . $_SERVER['HTTP_HOST'];
				$block->contents = str_ireplace("'/public/", "'" . $domain . "/public/", $block->contents);
				$block->contents = str_ireplace("\"/public/", "\"" . $domain . "/public/", $block->contents);
			$smarty->assign('message', $block->contents);
			$smarty->assign('id', $news->id);
			$smarty->assign('domain', $_SERVER['HTTP_HOST']);
			$smarty->display( 'newsletter-' . $news->language . '.tpl');
		}
	}
	
	// Function to tag the read flag on newsletter user
	function blank() {
		header('Content-type: image/gif');
		$im = imagecreatetruecolor(1,1);
		$black = imagecolorallocate($im, 0, 0, 0);
		imagecolortransparent($im, $black);
		if (isset($this->params[0])) {
			$newsletteruser = MyActiveRecord::FindFirst('newsletterusers', "checkcode = '" . $this->params[0] . "'");
			if ($newsletteruser->readdate == '0000-00-00 00:00:00') {
				$newsletteruser->readdate = date('Y-m-d H:i:s');
				$newsletteruser->save();
			}
		}
		imagegif($im);
		imagedestroy($im);
	}
}