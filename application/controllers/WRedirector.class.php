<?php

/**
 *	RNAME: Redirector
 *	PATH: 
 *	NOTE: Redirige la page vers n'importe quoi sur Internet lié en paramètre.
 *	VERSION: 1.0
 *	ACTIVE: YES
 *
 */

class WRedirector extends WSController {
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
		
		
		if ( !empty($params['param']) ) {
			header( "HTTP/1.1 301 Moved Permanently" ); 
			header('Location: ' . $this->params['param']);
			die();
		}
	}
}

