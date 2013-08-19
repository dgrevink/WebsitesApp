<?php
/*
* -------------------------------------------------------------------------
* URI ROUTING
* -------------------------------------------------------------------------
* This file lets you re-map URI requests to specific controller functions.
*
* Typically there is a one-to-one relationship between a URL string
* and its corresponding controller class/method. The segments in a
* URL normally follow this pattern:
*
* 	www.your-site.com/class/method/id/
*
* In some instances, however, you may want to remap this relationship
* so that a different class/function is called than the one
* corresponding to the URL.
*
* Examples:
* $route['journals'] = "blogs";
* $route['blog/joe'] = "blogs/users/34";
* $route['product/:any'] = "catalog/product_lookup";
* ==> URL of product/anything is remapped to catalog/product_lookup
*
* Possible expressions:
* ":any", ":num"
*
* Advanced example:
* $route['products/([a-z]+)/(\d+)'] = "$1/id_$2";
* ==> products/shirts/123 ==> call shirt / and id_123 as function
*
*/

$route['en/:any'] = 'Site/index/';
$route['en'] = 'Site/index/';
$route['fr/:any'] = 'Site/index/';
$route['fr'] = 'Site/index/';
$route['es/:any'] = 'Site/index/';
$route['es'] = 'Site/index/';
$route['nl/:any'] = 'Site/index/';
$route['nl'] = 'Site/index/';
$route['zh/:any'] = 'Site/index/';
$route['zh'] = 'Site/index/';
$route['ja/:any'] = 'Site/index/';
$route['ja'] = 'Site/index/';
$route['ko/:any'] = 'Site/index/';
$route['ko'] = 'Site/index/';


# Reserved routes, feel free to change if you want, just keep the array keys
$route['default_controller'] = 'Site';
$route['default_method'] = 'index';

