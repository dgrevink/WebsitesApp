<?php

/**
 *	RNAME: Icones en ligne avec selection ( mettre 1 a 4 en parametre )
 *	PATH: 
 *	NOTE: Affiche un plan de site dynamique.
 *	VERSION: 2.0
 *	ACTIVE: YES
 *
 */

class WSimpleContextMenu extends WSController {
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
		
		$items = array(
			array(
				'link' => '/fr/produits-et-services/alarme/',
				'text' => 'Alarme',
				'image' => '/public/images/icones/alarme.png'
			),
			array(
				'link' => '/fr/produits-et-services/telesurveillance/',
				'text' => 'T&eacute;l&eacute;surveillance',
				'image' => '/public/images/icones/telesurveillance.png'
			),
			array(
				'link' => '/fr/produits-et-services/videosurveillance/',
				'text' => 'Vid&eacute;osurveillance',
				'image' => '/public/images/icones/videosurveillance.png'
			),
			array(
				'link' => '/fr/produits-et-services/controle-et-geolocalisation/',
				'text' => 'Contr&ocirc;le d&rsquo;acc&egrave;s et g&eacute;olocalisation',
				'image' => '/public/images/icones/controle.png'
			)
		);
		
		$menu = "<ul class='simplecontextmenu'>";
		$counter = 1;
		foreach($items as $item) {
			$highlight = ($counter == $this->params['param'])?'current':'';
			$menu .= "<li class='{$highlight}'><a href='{$item['link']}' title='{$item['text']}'><img src='{$item['image']}' alt='{$item['text']}'/>{$item['text']}</a></li>";
			$counter ++;
		}
		$menu .= '</ul>';
		
		return $menu;
	}

}
