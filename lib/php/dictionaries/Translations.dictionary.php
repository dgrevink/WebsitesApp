<?php

// Translations for admin module

class WSDTranslations {

	static $meanings = array(
		'fr' => array(
			'SYSTEM_EXPIRED_SESSION' => "Votre session a expir&eacute;. ",
			'SYSTEM_WRONG_CREDENTIALS' => "Courriel / mot de passe erronn&eacute;. Veuillez r&eacute;-essayer s'il vous plait. ",
			'CONTENT_TITLE' => 'Titre',
			'CONTENT_TITLE_DESCRIPTION' => 'Titre de la page. Il appara&icirc;tra dans le titre de la fen&ecirc;tre et dans les menus principaux.',
			'CONTENT_TITLESHORT' => 'Sous-titre',
			'CONTENT_TITLESHORT_DESCRIPTION' => "Un titre alternatif plus court. Ce titre sera utilis&eacute; dans les endroits ou la taille est trop restreinte.",


			''=>''
		),
		'en' => array(
			'SYSTEM_EXPIRED_SESSION' => "Votre session a expir&eacute;. ",
			'SYSTEM_WRONG_CREDENTIALS' => "Courriel / mot de passe erronn&eacute;. Veuillez r&eacute;-essayer s'il vous plait. ",
			'CONTENT_TITLE' => 'Titre',
			'CONTENT_TITLE_DESCRIPTION' => 'Titre de la page. Il appara&icirc;tra dans le titre de la fen&ecirc;tre et dans les menus principaux.',
			'CONTENT_TITLESHORT' => 'Sous-titre',
			'CONTENT_TITLESHORTDESCRIPTION' => "Un titre alternatif plus court. Ce titre sera utilis&eacute; dans les endroits ou la taille est trop restreinte.",


			''=>''
		)
	);


	function getLabel($label, $language_code = 'fr') {
		global $userlanguage;
		if (isset($userlanguage)) { $language_code = $userlanguage; }
		return WSDTranslations::$meanings[$language_code][$label];
	}


}

?>