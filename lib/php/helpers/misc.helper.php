<?php
function number_pad($number,$n) {
return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
}

function array_neighbor($arr, $key)
{
   $keys = array_keys($arr);
   $keyIndexes = array_flip($keys);
  
   $return = array();
   if (isset($keys[$keyIndexes[$key]-1])) {
       $return[] = $keys[$keyIndexes[$key]-1];
   }
   else {
       $return[] = $keys[sizeof($keys)-1];
   }
   
   if (isset($keys[$keyIndexes[$key]+1])) {
       $return[] = $keys[$keyIndexes[$key]+1];
   }
   else {
       $return[] = $keys[0];
   }
   
   return $return;
}

function endsWith( $str, $sub ) {
	return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );
}

function humanize( $string ) {
	$string = str_replace('_', ' ', $string);
	if (substr($string, strlen($string)-2, 2) == 'id') {
		$string = substr($string, 0, strlen($string)-2);
	}
	if ($string == 'language') {
		$string = 'langue';
	}
	return trim(ucwords( $string ));
}

function shorten_text($text, $chars) { 
		if (strlen($text) >= $chars) {
        	$text = $text." "; 
   		    $text = substr($text,0,$chars); 
   	    	$text = substr($text,0,strrpos($text,' ')); 
	        $text = $text."..."; 
		}

        return $text; 
}

function valid_email($email) {
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

function html2txt($document){
	$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
	               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
	   	            '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
	               '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
	);
	$text = preg_replace($search, '', $document);
	return $text;
}


function array_merge_recursive_unique($array1, $array2) {
    // STRATEGY
    /*
    Merge array1 and array2, overwriting 1st array values with 2nd array 
    values where they overlap. Use array1 as the base array and then add 
    in values from array2 as they exist.
    
    Walk through each value in array2 and see if a value corresponds
    in array1. If it does, overwrite with second array value. If it's an 
    array, recursively execute this function and return the value. If it's 
    a string, overwrite the value from array1 with the value from array2. 
    
    If a value exists in array2 that is not found in array1, add it to array1.
    */

    // LOOP THROUGH $array2
    foreach($array2 AS $k => $v) {

		// CHECK IF VALUE EXISTS IN $array1
        if(!empty($array1[$k])) {
            // IF VALUE EXISTS CHECK IF IT'S AN ARRAY OR A STRING
            if(!is_array($array2[$k])) {
                // OVERWRITE IF IT'S A STRING
                $array1[$k]=$array2[$k];
            } else {
                // RECURSE IF IT'S AN ARRAY
                $array1[$k] = array_merge_recursive_unique($array1[$k], $array2[$k]);
            }
        } else {
            // IF VALUE DOESN'T EXIST IN $array1 USE $array2 VALUE
            $array1[$k]=$v;
        }
    }
	unset($k, $v);

	return $array1;
}

function array_trim_recursive(&$array, $splice_empty = true) {
    foreach($array as $k => &$v) {
        if(is_array($v)) {
            array_trim_recursive($v, $splice_empty);
        } else {
            $array[$k] = trim($v);
            if(!$v && $splice_empty) {
                array_splice($array, $k, 1);
            }
        }
    }
}

function normalize_string($old) {
		//$new = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $old);
		$new = utf8_accents_to_ascii($old);
		$new = utf8_strip_non_ascii($new);
		$new = strtolower($new);
		$new = strtr($new,
			"{}[]\"*%'_()!$'?: ,&+-/.",
			"------------------------"
		);
		$new = preg_replace('/-+/', '-',$new);
		$new = str_ireplace('#039;', "", $new);
		$new = trim($new, '-');
		return $new;
}


function utf8_strip_non_ascii($str) {
    ob_start();
    while ( preg_match(
        '/^([\x00-\x7F]+)|([^\x00-\x7F]+)/S',
            $str, $matches) ) {
        if ( !isset($matches[2]) ) {
            echo $matches[0];
        }
        $str = substr($str, strlen($matches[0]));
    }
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}


function utf8_accents_to_ascii( $str, $case=0 ){
    
    static $UTF8_LOWER_ACCENTS = NULL;
    static $UTF8_UPPER_ACCENTS = NULL;
    
    if($case <= 0){
        
        if ( is_null($UTF8_LOWER_ACCENTS) ) {
            $UTF8_LOWER_ACCENTS = array(
  'à' => 'a', 'ô' => 'o', 'ď' => 'd', 'ḟ' => 'f', 'ë' => 'e', 'š' => 's', 'ơ' => 'o',
  'ß' => 'ss', 'ă' => 'a', 'ř' => 'r', 'ț' => 't', 'ň' => 'n', 'ā' => 'a', 'ķ' => 'k',
  'ŝ' => 's', 'ỳ' => 'y', 'ņ' => 'n', 'ĺ' => 'l', 'ħ' => 'h', 'ṗ' => 'p', 'ó' => 'o',
  'ú' => 'u', 'ě' => 'e', 'é' => 'e', 'ç' => 'c', 'ẁ' => 'w', 'ċ' => 'c', 'õ' => 'o',
  'ṡ' => 's', 'ø' => 'o', 'ģ' => 'g', 'ŧ' => 't', 'ș' => 's', 'ė' => 'e', 'ĉ' => 'c',
  'ś' => 's', 'î' => 'i', 'ű' => 'u', 'ć' => 'c', 'ę' => 'e', 'ŵ' => 'w', 'ṫ' => 't',
  'ū' => 'u', 'č' => 'c', 'ö' => 'oe', 'è' => 'e', 'ŷ' => 'y', 'ą' => 'a', 'ł' => 'l',
  'ų' => 'u', 'ů' => 'u', 'ş' => 's', 'ğ' => 'g', 'ļ' => 'l', 'ƒ' => 'f', 'ž' => 'z',
  'ẃ' => 'w', 'ḃ' => 'b', 'å' => 'a', 'ì' => 'i', 'ï' => 'i', 'ḋ' => 'd', 'ť' => 't',
  'ŗ' => 'r', 'ä' => 'ae', 'í' => 'i', 'ŕ' => 'r', 'ê' => 'e', 'ü' => 'ue', 'ò' => 'o',
  'ē' => 'e', 'ñ' => 'n', 'ń' => 'n', 'ĥ' => 'h', 'ĝ' => 'g', 'đ' => 'd', 'ĵ' => 'j',
  'ÿ' => 'y', 'ũ' => 'u', 'ŭ' => 'u', 'ư' => 'u', 'ţ' => 't', 'ý' => 'y', 'ő' => 'o',
  'â' => 'a', 'ľ' => 'l', 'ẅ' => 'w', 'ż' => 'z', 'ī' => 'i', 'ã' => 'a', 'ġ' => 'g',
  'ṁ' => 'm', 'ō' => 'o', 'ĩ' => 'i', 'ù' => 'u', 'į' => 'i', 'ź' => 'z', 'á' => 'a',
  'û' => 'u', 'þ' => 'th', 'ð' => 'dh', 'æ' => 'ae', 'µ' => 'u', 'ĕ' => 'e', 
            );
        }
        
        $str = str_replace(
                array_keys($UTF8_LOWER_ACCENTS),
                array_values($UTF8_LOWER_ACCENTS),
                $str
            );
    }
    
    if($case >= 0){
        if ( is_null($UTF8_UPPER_ACCENTS) ) {
            $UTF8_UPPER_ACCENTS = array(
  'À' => 'A', 'Ô' => 'O', 'Ď' => 'D', 'Ḟ' => 'F', 'Ë' => 'E', 'Š' => 'S', 'Ơ' => 'O',
  'Ă' => 'A', 'Ř' => 'R', 'Ț' => 'T', 'Ň' => 'N', 'Ā' => 'A', 'Ķ' => 'K',
  'Ŝ' => 'S', 'Ỳ' => 'Y', 'Ņ' => 'N', 'Ĺ' => 'L', 'Ħ' => 'H', 'Ṗ' => 'P', 'Ó' => 'O',
  'Ú' => 'U', 'Ě' => 'E', 'É' => 'E', 'Ç' => 'C', 'Ẁ' => 'W', 'Ċ' => 'C', 'Õ' => 'O',
  'Ṡ' => 'S', 'Ø' => 'O', 'Ģ' => 'G', 'Ŧ' => 'T', 'Ș' => 'S', 'Ė' => 'E', 'Ĉ' => 'C',
  'Ś' => 'S', 'Î' => 'I', 'Ű' => 'U', 'Ć' => 'C', 'Ę' => 'E', 'Ŵ' => 'W', 'Ṫ' => 'T',
  'Ū' => 'U', 'Č' => 'C', 'Ö' => 'Oe', 'È' => 'E', 'Ŷ' => 'Y', 'Ą' => 'A', 'Ł' => 'L',
  'Ų' => 'U', 'Ů' => 'U', 'Ş' => 'S', 'Ğ' => 'G', 'Ļ' => 'L', 'Ƒ' => 'F', 'Ž' => 'Z',
  'Ẃ' => 'W', 'Ḃ' => 'B', 'Å' => 'A', 'Ì' => 'I', 'Ï' => 'I', 'Ḋ' => 'D', 'Ť' => 'T',
  'Ŗ' => 'R', 'Ä' => 'Ae', 'Í' => 'I', 'Ŕ' => 'R', 'Ê' => 'E', 'Ü' => 'Ue', 'Ò' => 'O',
  'Ē' => 'E', 'Ñ' => 'N', 'Ń' => 'N', 'Ĥ' => 'H', 'Ĝ' => 'G', 'Đ' => 'D', 'Ĵ' => 'J',
  'Ÿ' => 'Y', 'Ũ' => 'U', 'Ŭ' => 'U', 'Ư' => 'U', 'Ţ' => 'T', 'Ý' => 'Y', 'Ő' => 'O',
  'Â' => 'A', 'Ľ' => 'L', 'Ẅ' => 'W', 'Ż' => 'Z', 'Ī' => 'I', 'Ã' => 'A', 'Ġ' => 'G',
  'Ṁ' => 'M', 'Ō' => 'O', 'Ĩ' => 'I', 'Ù' => 'U', 'Į' => 'I', 'Ź' => 'Z', 'Á' => 'A',
  'Û' => 'U', 'Þ' => 'Th', 'Ð' => 'Dh', 'Æ' => 'Ae', 'Ĕ' => 'E',
            );
        }
        $str = str_replace(
                array_keys($UTF8_UPPER_ACCENTS),
                array_values($UTF8_UPPER_ACCENTS),
                $str
            );
    }
    
    return $str;
    
}

function date_transform($date, $format) {
	$d = strtotime($date);
	return strftime($format, $d);
}






// Get distance between to sets of latitudes and longitudes. $unit is either m, k or n
function distance($lat1, $lon1, $lat2, $lon2, $unit) {  

  $theta = $lon1 - $lon2;  
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));  
  $dist = acos($dist);  
  $dist = rad2deg($dist);  
  $miles = $dist * 60 * 1.1515; 
  $unit = strtoupper($unit); 

  if ($unit == "K") { 
    return ($miles * 1.609344);  
  } else if ($unit == "N") { 
      return ($miles * 0.8684); 
    } else { 
        return $miles; 
      } 
} 

#echo distance(32.9697, -96.80322, 29.46786, -98.53506, "m") . " miles<br>"; 
#echo distance(32.9697, -96.80322, 29.46786, -98.53506, "k") . " kilometers<br>"; 
#echo distance(32.9697, -96.80322, 29.46786, -98.53506, "n") . " nautical miles<br>"; 




/** 
 * GETCOORD 
 * Uses Google Maps to resolve the coordinates of a postal code 
 *  
 * @param   String   $postal   Postal code to lookup 
 * @return  Array    Returns array with latitude and longitude 
 * @return  Boolean  False if an error occurred 
 */ 

// ---- | REQUIRED GOOGLE MAPS KEY | -------- 

function getCoord($postal) 
{ 
    $d = @file_get_contents('http://maps.google.com/maps/geo?q=' . $postal . '&output=xml&key=' . GMAPS_KEY); 
    if (!$d) 
        return false; // Failed to open connection 

    $coord = new SimpleXMLElement($d); 
    
    if ((string) $coord->Response->Status->code != '200') 
        return false; // Invalid status code 

    list($lng, $lat) = explode(',', (string) $coord->Response->Placemark->Point->coordinates); 

    return array('Lat' => (float) $lat, 'Lng' => (float) $lng, 'place' => $coord->Response->Placemark->address );  
} 


function dayofyear2date( $tDay, $tFormat = 'd-m-Y' ) {
	if (!is_numeric($tDay)) return false;
    $day = intval( $tDay );
    $day = ( $day == 0 ) ? $day : $day - 1;
    $offset = intval( intval( $tDay ) * 86400 );
    $str = date( $tFormat, strtotime( 'Jan 1, ' . date( 'Y' ) ) + $offset );
    return( $str );
}



  function generate_password($length = 8)
  {

    // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
  
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    
    // add random characters to $password until $length is reached
    while ($i < $length) { 

      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
        
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }

    }

    // done!
    return $password;

  }
  




