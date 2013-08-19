<?php

function getRealIpAddr() {
  if (!empty($_SERVER['HTTP_CLIENT_IP']))
  //check ip from share internet
  {
    $ip=$_SERVER['HTTP_CLIENT_IP'];
  }
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
  //to check ip is pass from proxy
  {
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  }
  else
  {
    $ip=$_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}



function locateIp($ip){
	$d = file_get_contents("http://www.ipinfodb.com/ip_query.php?ip=$ip&output=xml");
 
	//Use backup server if cannot make a connection
	if (!$d){
		$backup = file_get_contents("http://backup.ipinfodb.com/ip_query.php?ip=$ip&output=xml");
		$answer = new SimpleXMLElement($backup);
		if (!$backup) return false; // Failed to open connection
	}else{
		$answer = new SimpleXMLElement($d);
	}
 
	$country_code = $answer->CountryCode;
	$country_name = $answer->CountryName;
	$region_name = $answer->RegionName;
	$city = $answer->City;
	$zippostalcode = $answer->ZipPostalCode;
	$latitude = $answer->Latitude;
	$longitude = $answer->Longitude;
	$timezone = $answer->Timezone;
	$gmtoffset = $answer->Gmtoffset;
	$dstoffset = $answer->Dstoffset;
 
	//Return the data as an array
	return array('ip' => $ip, 'country_code' => $country_code, 'country_name' => $country_name, 'region_name' => $region_name, 'city' => $city, 'zippostalcode' => $zippostalcode, 'latitude' => $latitude, 'longitude' => $longitude, 'timezone' => $timezone, 'gmtoffset' => $gmtoffset, 'dstoffset' => $dstoffset);
}


function clean_tweet($tweet)
{
	$regexps = array
	(
		"link"  => '/[a-z]+:\/\/[a-z0-9-_]+\.[a-z0-9-_:~%&\?\+#\/.=]+[^:\.,\)\s*$]/i',
		"at"    => '/(^|[^\w]+)\@([a-zA-Z0-9_]{1,15}(\/[a-zA-Z0-9-_]+)*)/',
		"hash"  => "/(^|[^&\w'\"]+)\#([a-zA-Z0-9_]+)/"
	);

	foreach ($regexps as $name => $re)
	{
		$tweet = preg_replace_callback($re, 'parse_tweet_'.$name, $tweet);
	}

	return $tweet;
}

/*
 * Wrap a link element around URLs matched via preg_replace()
 */
function parse_tweet_link($m)
{
	return '<a href="'.$m[0].'">'.((strlen($m[0]) > 25) ? substr($m[0], 0, 24).'...' : $m[0]).'</a>';
}

/*
 * Wrap a link element around usernames matched via preg_replace()
 */
function parse_tweet_at($m)
{
	return $m[1].'@<a href="http://twitter.com/'.$m[2].'">'.$m[2].'</a>';
}

/*
 * Wrap a link element around hashtags matched via preg_replace()
 */
function parse_tweet_hash($m)
{
	return $m[1].'#<a href="http://search.twitter.com/search?q=%23'.$m[2].'">'.$m[2].'</a>';
}
