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

$config['author'] = "{$config.author}";
$config['contactmail'] = "{$config.contactmail}";
$config['company'] = "{$config.company}";
$config['version'] = "{$config.version}";
$config['release'] = "{$config.release}";
$config['maintenance'] = {if $config.maintenance}true{else}false{/if};
$config['maintenance_text'] = "{$config.maintenance_text}";

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
$config['default_language'] = '{$config.default_language}';
{section name=languages loop=$languages}
$config['languages'][] = '{$languages[languages]}';
{/section}

/*
*--------------------------------------------------------------------------
* 3rd Party Options
*--------------------------------------------------------------------------
*
* uacct:   google account number
* headers: HTML code to insert in the <header> section.
*
*/
$config['uacct'] = "{$config.uacct}";
$config['headers'] = "{$config.headers}";
$config['recaptcha_public'] = "{$config.recaptcha_public}";
$config['recaptcha_private'] = "{$config.recaptcha_private}";
$config['recaptcha_theme'] = "{$config.recaptcha_theme}";

/*
*--------------------------------------------------------------------------
* Contents
*--------------------------------------------------------------------------
*
* menu_name_1-4: Menu location names for contents tab
*
*/
$config['menu_name_1'] = "{$config.menu_name_1}";
$config['menu_name_2'] = "{$config.menu_name_2}";
$config['menu_name_3'] = "{$config.menu_name_3}";
$config['menu_name_4'] = "{$config.menu_name_4}";

{$template_page_ids}

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
$config['deployment'] = "{$config.deployment}";
$config['deployments'] = array( 'local', 'beta', 'production' );

$config['local'] = array(
    'html_root' => "{$local.html_root}",
    'html_lib' => "{$local.html_lib}",
    'db_type' => "{$local.db_type}",
    'db_server' => "{$local.db_server}",
    'db_user' => "{$local.db_user}",
    'db_password' => "{$local.db_password}",
    'db_name' => "{$local.db_name}"
);

$config['beta'] = array(
    'html_root' => "{$beta.html_root}",
    'html_lib' => "{$beta.html_lib}",
    'db_type' => "{$beta.db_type}",
    'db_server' => "{$beta.db_server}",
    'db_user' => "{$beta.db_user}",
    'db_password' => "{$beta.db_password}",
    'db_name' => "{$beta.db_name}"
);

$config['production'] = array(
    'html_root' => "{$production.html_root}",
    'html_lib' => "{$production.html_lib}",
    'db_type' => "{$production.db_type}",
    'db_server' => "{$production.db_server}",
    'db_user' => "{$production.db_user}",
    'db_password' => "{$production.db_password}",
    'db_name' => "{$production.db_name}"
);

