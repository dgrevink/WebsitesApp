<?php

/**
 *	RNAME: Nouvelles - Detail
 *	PATH: newsdetail
 *	NOTE: Affiche en detail une nouvelle dont le +titleseo est passe en URL, sinon il prend la plus r&eacute;cente.
 *	VERSION: 2.0
 *	ACTIVE: NO
 *  INIT: YES
 *
 */

WSLoader::load_helper('database');

class WNewsDetail extends WSController {
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
		if (!$params) {
			die('404');
		}
		
		// Get the current language
		$language = $params['language'];
		
		$titleseo = end($this->params);
		
		if ( (!isset($titleseo[0])) || ($titleseo[0] != '+') ) {
			$titleseo = null;
		}
		else {
			$titleseo = substr($titleseo, 1, strlen($titleseo)-1);
		}
		
		$t = new Template();
        $t->template_dir = WS_APPLICATION_FOLDER . "/views/widgets/" . $params['path'];


		if (!$titleseo) return '';

		$condition = ($titleseo?"titleseo like '" . $titleseo . "'  and ( now() between ddateshow and ddatehide ) ":'  ( now() between ddateshow and ddatehide ) ');
		
		$condition .= " and language = '" . $language . "'";

		$news = MyActiveRecord::FindFirst('news', $condition);
		if ($news) {
			if ($language == 'fr') {
				$news->ddate = strftime("%e %B %Y", strtotime($news->ddate));
			}
			if ($language == 'en') {
				$news->ddate = strftime("%B %e, %Y", strtotime($news->ddate));
			}

			if (!$titleseo)	{
				$t->assign('notitleseo', 'true');
			}
			$t->assign('news', $news);
		}
 
		
        return $t->fetch($params['path'] . '-' . $language . '.tpl');
	}


	// This function is called by the CMS to initialize the database tables associated with the widget.
	function _init() {
		return array(
			'news' => (object) array (
				'name'			=> 'news',
				'title'			=> 'Nouvelles',
				'description'	=> "Les nouvelles du site",
				'sortparams'	=> 'title asc',
				'inlineadd'		=> null,
				'filtrable'		=> null,
				'childtable'	=> null,
				'system'		=> 0,
				'rss'			=> null,
				'rss_sublink'	=> null,
				'porder'		=> null,
				'fields' => array(
						'ddate' 			=> (object) array( 'title' => 'ddate',	 	'name' => 'Date', 		'type' => WST_DATE, 	'description' => 'Date de la nouvelle', 						'showlist' => 0, 'listeditable' => 0, 'showedit' => 1, 'default' => ''),
						'ddateshow' 		=> (object) array( 'title' => 'ddateshow',	'name' => 'Afficher',	'type' => WST_DATE, 	'description' => "Date d'affichage de la nouvelle",				'showlist' => 0, 'listeditable' => 0, 'showedit' => 1, 'default' => ''),
						'ddatehide' 		=> (object) array( 'title' => 'ddatehide',	'name' => 'Cacher', 	'type' => WST_DATE, 	'description' => "Date quand la nouvelle sera cach&eacute;e",	'showlist' => 0, 'listeditable' => 0, 'showedit' => 1, 'default' => ''),
						'contents'			=> (object) array( 'title' => 'contents', 	'name' => 'Contenu',	'type' => WST_HTML, 	'description' => 'Texte de la nouvelle',						'showlist' => 0, 'listeditable' => 0, 'showedit' => 1, 'default' => '')
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
