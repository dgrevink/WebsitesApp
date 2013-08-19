Posterous API Library for PHP 5
===============================
Current Version: 0.1
Author: Calvin Freitas

Notes
-----
This is the initial release of my PHP 5 Library for the Posterous API.

Get more information at my website: http://calvinf.com/posterous-api-library-php/







<?php
require('posterous-api.php');
$api = new PosterousAPI();

header('Content-type: text/html');

try {
	$xml = $api->readposts(array('hostname' =>'calvinf'));
}
catch(Exception $e) {
  print $e->getMessage();
  die;
}

foreach($xml->{post} as $post) {
	echo $post->title . "<br />";
}
?>


