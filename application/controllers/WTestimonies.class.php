<?php

/**
 *	RNAME: Temoignages
 *	PATH: 
 *	NOTE: Affiche trois temoignages pris au hasard dans la table des temoignages.
 *	VERSION: 2.0
 *	ACTIVE: YES
 *
 */

WSLoader::load_helper('database');

class WTestimonies extends WSController {
	function index( $params = null ) {
		if (!$params) {
			die('404');
		}
		
		// Get the current language
		$language = $params['language'];
		
		$testimonies = MyActiveRecord::FindAll('testimonies', "language = '" . $language . "' and active = 1");
		if (!$testimonies) return '';
		if (count($testimonies) < 2) return '';

		$candidates = array_rand($testimonies, 2);
		shuffle($candidates);
		
		$code = array();
		
		$code[] = "<div class='testimonies'>";
		while($id = array_pop($candidates)) {
			$code[] = "<div class='testimony'><span>";
			$code[] = $testimonies[$id]->contents;
			$code[] = "</span></div>";
		}
		$code[] = "</div>";
				
		return implode('', $code);
	}
	
}
