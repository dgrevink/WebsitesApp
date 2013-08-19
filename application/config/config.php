<?php

/*
*--------------------------------------------------------------------------
* General Options
*--------------------------------------------------------------------------
*
* author: Site author (meta tag)
* contactmail: Used for contact forms
* company: Company name, used in page titles and everywhere needed
* version: Version number
* release: Release code
* maintenance: Indique si le site est en maintenance ou non
* maintenance_text: Texte de maintenance qui est affiché
*
*/

$config['author'] = "Websitesapp Demo";
$config['contactmail'] = "david@starplace.org";
$config['company'] = "Websitesapp";
$config['version'] = "1.0";
$config['release'] = "release";
$config['maintenance'] = false;
$config['maintenance_text'] = "Q2Ugc2l0ZSBhIMOpdMOpIG1pcyBob3JzIGxpZ25lLg==";

/*
*--------------------------------------------------------------------------
* Languages
*--------------------------------------------------------------------------
*
* default_language: Default site language
* languages: array of supported languages for the site.
*            this is used to populate the language_bar
*
*/
$config['default_language'] = 'fr';
$config['languages'][] = 'fr';
$config['languages'][] = 'en';

/*
*--------------------------------------------------------------------------
* 3rd Party Options
*--------------------------------------------------------------------------
*
* uacct:   google account number
* headers: HTML code to insert in the <header> section.
*
*/
$config['uacct'] = "";
$config['headers'] = "";
$config['recaptcha_public'] = "6LcZ6cYSAAAAACM485TXqI9FCD6v37ft-7Rf3pHS";
$config['recaptcha_private'] = "6LcZ6cYSAAAAAOrm3LwJ2lvW-AfpvGo_47fKgWY8";
$config['recaptcha_theme'] = "blackglass";

/*
*--------------------------------------------------------------------------
* Contents
*--------------------------------------------------------------------------
*
* menu_name_1-4: Menu location names for contents tab
*
*/
$config['menu_name_1'] = "Menu principal";
$config['menu_name_2'] = "Menu haut droit";
$config['menu_name_3'] = "Inactif";
$config['menu_name_4'] = "Inactif";

$config['template_page_id_fr'] = '1';$config['template_page_id_en'] = '1';

/*
*--------------------------------------------------------------------------
* Deployment
*--------------------------------------------------------------------------
*
* deployment: deployment id
* deployments: list of possible deployments
*
* For each deployment:
*
*/
$config['deployment'] = "local";
$config['deployments'] = array( 'local', 'beta', 'production' );

$config['local'] = array(
    'html_root' => "",
    'html_lib' => "/lib",
    'db_type' => "mysql",
    'db_server' => "localhost",
    'db_user' => "root",
    'db_password' => "beta",
    'db_name' => "websitesapp"
);

$config['beta'] = array(
    'html_root' => "",
    'html_lib' => "/lib",
    'db_type' => "mysql",
    'db_server' => "localhost",
    'db_user' => "",
    'db_password' => "",
    'db_name' => ""
);

$config['production'] = array(
    'html_root' => "",
    'html_lib' => "/lib",
    'db_type' => "mysql",
    'db_server' => "",
    'db_user' => "",
    'db_password' => "",
    'db_name' => ""
);
