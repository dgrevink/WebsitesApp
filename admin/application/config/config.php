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
* brand: Company name shown as CMS customizer
* brand-website: Company URL shown as CMS customizer
* brand-website: Company text (motto) shown as CMS customizer
*
*/

$config['author'] = "David Grevink";
$config['contactmail'] = "david@starplace.org";
$config['company'] = "WebsitesApp.com";
$config['version'] = "4.0.0";
$config['release'] = "";
$config['brand'] = 'starplace.org';
$config['brand-website'] = 'http://starplace.org/';
$config['brand-website-text'] = 'Votre développeur Web';

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
$config['languages'] = array( 'fr', 'en', 'nl' );

/*
*--------------------------------------------------------------------------
* 3rd Party Options
*--------------------------------------------------------------------------
*
* uacct: google account number
*
*/
$config['uacct'] = "";

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
    'html_root' => "/admin",
    'html_lib' => "/lib",
    'db_type' => "mysql",
    'db_server' => "",
    'db_user' => "",
    'db_password' => "",
    'db_name' => ""
);
$config['beta'] = array(
    'html_root' => "/admin",
    'html_lib' => "/lib",
    'db_type' => "mysql",
    'db_server' => "",
    'db_user' => "",
    'db_password' => "",
    'db_name' => ""
);

$config['production'] = array(
    'html_root' => "/admin",
    'html_lib' => "/lib",
    'db_type' => "mysql",
    'db_server' => "",
    'db_user' => "",
    'db_password' => "",
    'db_name' => ""
);

