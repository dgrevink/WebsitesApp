<?php

/**
 *	RNAME: Documents proteges a telecharger.
 *	PATH: 
 *	NOTE: 
 *	VERSION: 2.0
 *	ACTIVE: NO
 *  INIT: YES
 *
 */

class WDocuments extends WSController {
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
		
		if (!$this->_check_init()) {
			return "<div class='ws-debug'>WProduct ( Produit - Fiche ): BAD DATABASE STRUCTURE</div>";
		}

		// Init useful variables
		$this->language =	$params['language'];	// Current page language
		$this->url = 		$this->params;			// URL as an array
		$this->wparams = 	trim($params['param']);	// Widget params as specified in CMS

		$records = MyActiveRecord::FindAll('documents', "language = '" . $this->language . "'", 'ddate desc, title asc');
		
		$code = array();

		$ddate_format = '';		
			if ($this->language == 'fr') {
				$ddate_format = "%e %B %Y";
			}
			if ($this->language == 'en') {
				$ddate_format = "%B %e, %Y";
			}
			


		$user = null;
		$group = null;
		
		if ($params['auth']->checkAuth()) {
			$user = MyActiveRecord::FindFirst('users', "username like '" . $params['auth']->session['username'] . "'");
			$group = $user->find_parent('groups');
		}
		
		$code[] = "<div class='documents'>";
		$code[] = "<table>";
		foreach ($records as $record) {
			$has_access = false;
			if ($record->groups_id == '-1') {
				$has_access = true;
			}
			else {
				if ($params['auth']->checkAuth()) {
					if ($record->groups_id == $group->id) {
						$has_access = true;
					}
				}
			}
			
			if ($has_access) {
				$code[] = "<tr>";
				$code[] = "<td class='ws-date'>" . "<a href='/WDocuments/download/" . $record->id . "'>" . strftime($ddate_format, strtotime($record->ddate)) . "</a></td>";
				$code[] = "<td class='ws-title'>" . "<a href='/WDocuments/download/" . $record->id . "'>" . $record->title . "</a></td>";
				$code[] = "<td class='ws-description'>" . "<a href='/WDocuments/download/" . $record->id . "'>" . $record->description . "</a></td>";
				$code[] = "</tr>";
			}
		}
		$code[] = "</table>";
		$code[] = "</div>";
		
		return implode("\n", $code);
	}
	
	function download() {
		WSLoader::load_helper('file');
		WSLoader::load_dictionary('mimes');

		$record = MyActiveRecord::FindById('documents', $this->params[0]);
		if ($record) {
			$has_access = false;
			if ($record->groups_id == '-1') {
				$has_access = true;
			}
			else {
				if ($this->auth->checkAuth()) {
					$user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
					$group = $user->find_parent('groups');
					if ($record->groups_id == $group->id) {
						$has_access = true;
					}
				}
			}
			if (!$has_access) {
				die();
			}
			else {
				$filename = basename($record->file);
				$fileext = file_extension($filename);
				$filemime = WSDMimes::getMime($fileext);

				header('Content-type: ' . $filemime);

				header('Content-Disposition: attachment; filename="' . $filename . '"');

				readfile(WS_ROOT . $record->file);

			}
		}
	}


	// This function is called by the CMS to initialize the database tables associated with the widget.
	function _init() {
		return array(
			'products' => (object) array (
				'name'			=> 'documents',
				'title'			=> 'T&eacute;l&eacute;chargements',
				'description'	=> "Les t&eacute;l&eacute;chargements mis &agrave; disposition",
				'sortparams'	=> 'title asc',
				'inlineadd'		=> null,
				'filtrable'		=> null,
				'childtable'	=> null,
				'system'		=> 0,
				'rss'			=> null,
				'rss_sublink'	=> null,
				'porder'		=> null,
				'fields' => array(
						'description'
							=> (object) array( 'title' => 'description', 	'name' => 'Description', 'type' => WST_TEXT, 'description' => 'Description du document',	'showlist' => 0, 'listeditable' => 0, 'showedit' => 1, 'default' => ''),
						'file'
							=> (object) array( 'title' => 'file', 		  'name' => 'Fichier',		  'type' => WST_FILE, 'description' => 'Fichier',					'showlist' => 0, 'listeditable' => 0, 'showedit' => 1, 'default' => ''),
						'groups_id'
							=> (object) array( 'title' => 'groups_id', 	  'name' => 'Media',		  'type' => WST_TABLE_LINK, 'description' => 'Groupe ayant acc&egrave;s',	'showlist' => 1, 'listeditable' => 1, 'showedit' => 1, 'default' => ''),
						'ddate'
							=> (object) array( 'title' => 'ddate', 	'name' => 'Date', 'type' => WST_DATE, 'description' => 'Date de mise &agrave; jour du document',	'showlist' => 0, 'listeditable' => 0, 'showedit' => 1, 'default' => ''),
					)
				)
		);
	}
	
	// Check if widget has sane environment
	function _check_init() {
		$ok = true;
		foreach ($this->_init() as $table => $temp) {
			$ok = $ok && (class_exists(ucfirst($table)));
		}
		return $ok;
	}


	
}

?>