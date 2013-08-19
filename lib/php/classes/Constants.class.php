<?php

// Field Types

define('WST_HIDDEN',			00); // Logical overloaded type (hideedit or hidelist)

define('WST_INTEGER', 			10); // INTEGER
define('WST_BOOLEAN',			11); // INTEGER 1 or 0
define('WST_ORDER',				17); // List of 50 items to order things
define('WST_FIELD_TYPE',		18); // This list
define('WST_TABLE_LINK',		19); // INTEGER 'item_id' links to table item

define('WST_STRING',			20); // VARCHAR(256)
define('WST_PASSWORD',			21); // VARCHAR(256) Password
define('WST_IMAGE',				22); // VARCHAR(256) File selector + Cropper
define('WST_FILE',				23); // VARCHAR(256) File selector
define('WST_EMAIL',				24); // VARCHAR(256) Valid Email
define('WST_TIME',				25); // TIME Time Picker
define('WST_PLACEHOLDER',		26); // VARCHAR(256) Placeholder picker
define('WST_LANGUAGE',			29); // VARCHAR(256) Language Selector
define('WST_REASONABLE_LANGUAGES',			28); // VARCHAR(256) Reasonable Languages Selector

define('WST_TEXT',				30); // TEXT Textarea
define('WST_HTML',				31); // TEXT FCKedit
define('WST_CODE',				32); // TEXT Code Editor
define('WST_TABLE_RIGHTS',  	33); // TEXT php serialize() of a list of tables, presented as a list of tables to check.
define('WST_MODULES_RIGHTS',	34); // TEXT php serialize() of a list of tables, presented as a list of tables to check.
define('WST_KEYWORDS',			35); // TEXT list of comma-separated keywords

define('WST_FLOAT',				40); // FLOAT

define('WST_DATE',				50); // DATE Date Selector
define('WST_COLOR',				51); // Color selector


// Modules Rights

define('WSR_NONE',				00); // No rights

define('WSR_CONTENTS',			10); // Contents ON/OFF
define('WSR_BLOCKS',			20); // Blocks ON/OFF
define('WSR_FILES',				30); // File Manager ON/OFF
define('WSR_FORMS',				40); // Form Manager ON/OFF
define('WSR_NEWSLETTERS',		50); // Newsletter Manager ON/OFF
define('WSR_DATA',				80); // Data Manager ON/OFF
define('WSR_PARAMS',			90); // Parameters ON/OFF
define('WSR_PARAMS_CAN_MAINTENANCE_CONNECT',			91); // Can connect to CMS if in maintenance mode
define('WSR_HELP',				99); // Help ON/OFF

// Modules Rights - Contents
define('WSR_CONTENTS_ORDER',	11); // Order contents ON/OFF
define('WSR_CONTENTS_ADD',		12); // Contents add / edit / save
define('WSR_CONTENTS_EDIT',		13); // Contents add / edit / save
define('WSR_CONTENTS_ACCESS',	14); // Access Panel ON/OFF
define('WSR_CONTENTS_CONTENT',	15); // Content Panel ON/OFF
define('WSR_CONTENTS_LAYOUT',	16); // Layout Tab ON/OFF
define('WSR_CONTENTS_SEO',		17); // SEO Panel ON/OFF
define('WSR_CONTENTS_METADATA',	18); // Metadata Panel ON/OFF
define('WSR_CONTENTS_VERSIONS',	19); // Versions functionality ON/OFF

// Modules Rights - File Manager
define('WSR_FILES_NORMALIZE',	31); // File Normalize button ON/OFF
define('WSR_FILES_CREATE_FILE',	32); // File creation button ON/OFF
define('WSR_FILES_CREATE_DIR',	33); // Directory creation button ON/OFF
define('WSR_FILES_UPLOAD',		34); // File upload button ON/OFF
define('WSR_FILES_COMMENT_DIR',	35); // Comment directory button ON/OFF
define('WSR_FILES_RENAME',		36); // Element rename
define('WSR_FILES_DUPLICATE',	37); // Element duplicate
define('WSR_FILES_DELETE',		38); // Element delete
define('WSR_FILES_DOWNLOAD',	38); // Element download (file)

// Modules Rights - Forms
define('WSR_FORMS_CREATE',		41); // Forms create ON/OFF, also allows forms parametering
define('WSR_FORMS_QUESTIONS',	42); // Forms modify questions ON/OFF, also allows forms saving
define('WSR_FORMS_CONSULT',		43); // Forms consult ON/OFF shows the data tab
define('WSR_FORMS_TEXTS',		44); // Forms texts ON/OFF shows the texts tab

// Modules Rights - Newsletters
define('WSR_NEWSLETTERS_SEND',	51); // Shows or hides the send newsletter button

// System Field Types
define ("WS_FATAL",	E_ERROR);
define ("WS_ERROR",	E_WARNING);
define ("WS_WARNING",	E_NOTICE);
define ("WS_INFO",	1);

// Newsletters statuses
define( "WSN_PAUSED",	0 );
define( "WSN_RUNNING",	1 );
define( "WSN_ERROR",	9 );


define( "DATE_NICE",			"F j, Y");
define( "DATE_NICE_TIME",       "H:i");
define( "DATE_NICE_NAKED",      "d/m");
define( "DATE_POPUP", 			"d/m/Y");
define( "DATE_MYSQL",			"Y-m-d");
define( "DATE_MYSQL_TIME",		"H:i:s");
define( "DATE_MYSQL_DATETIME",	"Y-m-d H:i:s");


define( "DATE_POINT_FR",			"%d.%m.%Y");
define( "DATE_POINT_EN",			"%m.%d.%Y");



/**
 * WSConstants
 *
 * @package system
 * @subpackage	constants
 * @category	constants
 * @author	David Grevink
 * @link	http://websitesapp.com
 */
class WSConstants {
	public static  $field_types = array(
		WST_HIDDEN => 		'SYSTEM - Cach&eacute;',
		WST_INTEGER => 		'INT - Nombre entier',
		WST_BOOLEAN =>		'INT - Vrai / Faux',
		WST_ORDER => 		"INT - Num&eacute;ro d'ordre",
		WST_FIELD_TYPE => 	'SYSTEM - Liste de types de champs',
		WST_TABLE_LINK => 	'SYSTEM - Lien vers une autre table',
		WST_PLACEHOLDER => 	'SYSTEM - Sélecteur de placeholder dans un template',

		WST_STRING => 		'VARCHAR - Ligne de texte',
		WST_PASSWORD => 	'VARCHAR - Mot de passe',
		WST_IMAGE => 		'VARCHAR - Image (avec cropper)',
		WST_FILE => 		'VARCHAR - Fichier',
		WST_EMAIL => 		'VARCHAR - Courriel',
		WST_TIME => 		'VARCHAR - Temps (Heures / Minutes)',
		WST_LANGUAGE => 	'VARCHAR - Langue',
		WST_REASONABLE_LANGUAGES => 'VARCHAR - Langues Raisonnables',

		WST_TEXT => 		'TEXT - Texte brut',
		WST_HTML => 		'TEXT - Editeur de texte avanc&eacute;',
		WST_CODE => 		'TEXT - Editeur PHP/JS/HTML/CSS',
		WST_TABLE_RIGHTS => 'SYSTEM - Liste de tables permises',
		WST_MODULES_RIGHTS => 'SYSTEM - Liste des modules permis',
		WST_KEYWORDS =>		'SYSTEM - Mots cl&eacute;s',

		WST_FLOAT => 		'FLOAT - Nombre d&eacute;cimal (Monnaie, statistiques, ...)',

		WST_DATE => 		'DATE - Date',
		
		WST_COLOR => 		'VARCHAR - Color picker'
	);
	
	public static $modules_rights = array(
		WSR_CONTENTS 			=>	"Contenu",
		WSR_CONTENTS_ORDER		=>	"Contenu - Ordonner le menu",
		WSR_CONTENTS_ADD		=>	"Contenu - Ajouter / Enlever des pages",
		WSR_CONTENTS_EDIT		=>	"Contenu - Modification de pages",
		WSR_CONTENTS_ACCESS 	=>	"Contenu - Page > Acc&egrave;s",
		WSR_CONTENTS_CONTENT	=>	"Contenu - Page > Contenu",
		WSR_CONTENTS_LAYOUT		=>	"Contenu - Layout",
		WSR_CONTENTS_METADATA	=>	"Contenu - Metadata",
		WSR_CONTENTS_SEO		=>	"Contenu - Metadata > SEO",
		WSR_CONTENTS_VERSIONS	=>	"Contenu - Gestion des Versions",

		WSR_BLOCKS  			=>	"Blocs",

		WSR_FILES  				=>	"Fichiers",
		WSR_FILES_NORMALIZE		=>  "Fichiers - Normaliser",
		WSR_FILES_CREATE_FILE	=>  "Fichiers - Cr&eacute;er un fichier",
		WSR_FILES_CREATE_DIR	=>  "Fichiers - Cr&eacute;er un r&eacute;pertoire",
		WSR_FILES_UPLOAD		=>  "Fichiers - Upload",
		WSR_FILES_COMMENT_DIR	=>  "Fichiers - Commenter un r&eacute;pertoire",
		WSR_FILES_RENAME		=>  "Fichiers - Renommer",
		WSR_FILES_DUPLICATE		=>	"Fichiers - Dupliquer",
		WSR_FILES_DELETE		=>	"Fichiers - Effacer",
		WSR_FILES_DOWNLOAD		=>	"Fichiers - T&eacute;l&eacute;charger",

		WSR_FORMS  				=>	"Formulaires",
		WSR_FORMS_CREATE  		=>	"Formulaires - Cr&eacute;ation et param&egrave;trage",
		WSR_FORMS_QUESTIONS		=>	"Formulaires - Gestion des Questions",
		WSR_FORMS_CONSULT		=>	"Formulaires - Consultation et t&eacute;l&eacute;chargement des r&eacute;ponses",
		WSR_FORMS_TEXTS			=>  "Formulaires - Modification des textes",

		WSR_NEWSLETTERS  		=>	"Newsletters",
		WSR_NEWSLETTERS_SEND	=>  "Newsletters - Envoi",

		WSR_DATA		  		=>	"Donn&eacute;es",

		WSR_PARAMS  			=>	"Param&egrave;trage",
		WSR_PARAMS_CAN_MAINTENANCE_CONNECT => "Peut se connecter en mode maintenance/offline",

		WSR_HELP  				=>	"Aide"
	);


	
	public static $newsletter_statuses = array(
		WSN_PAUSED		=>	'Arr&ecirc;t&eacute;',
		WSN_RUNNING		=>	'Lanc&eacute;',
		WSN_ERROR		=>	'Erreurs'
	);


}

/* End of file Constants.class.php */
/* Location: ./lib/php/classes/Constants.class.php */