<?php



function file_permissions($name) {

$perms = fileperms($name);

if (($perms & 0xC000) == 0xC000) {
    // Socket
    $info = 's';
} elseif (($perms & 0xA000) == 0xA000) {
    // Symbolic Link
    $info = 'l';
} elseif (($perms & 0x8000) == 0x8000) {
    // Regular
    $info = '-';
} elseif (($perms & 0x6000) == 0x6000) {
    // Block special
    $info = 'b';
} elseif (($perms & 0x4000) == 0x4000) {
    // Directory
    $info = 'd';
} elseif (($perms & 0x2000) == 0x2000) {
    // Character special
    $info = 'c';
} elseif (($perms & 0x1000) == 0x1000) {
    // FIFO pipe
    $info = 'p';
} else {
    // Unknown
    $info = 'u';
}

// Owner
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));

// Group
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));

// World
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));

return $info;
}


function file_permissions_octal($file, $octal = true)
{
    if(!file_exists($file)) return false;

    $perms = fileperms($file);

    $cut = $octal ? 2 : 3;

    return substr(decoct($perms), $cut);
}



function allhtmlentities($string) {
   if ( strlen($string) == 0 ) 
       return $string;
   $result = '';
   $string = htmlentities($string, HTML_ENTITIES);
   $string = preg_split("//", $string, -1, PREG_SPLIT_NO_EMPTY);
   $ord = 0;
   for ( $i = 0; $i < count($string); $i++ ) {
       $ord = ord($string[$i]);
       if ( $ord > 127 ) {
           $string[$i] = '&#' . $ord . ';';
       }
   }
   return implode('',$string);
}


function obfuscate($email) {
    $i=0;
    $obfuscated="";
    while ($i<strlen($email)) {
       if (rand(0,2)) {
          $obfuscated.='%'.dechex(ord($email{$i}));
       } else {
          $obfuscated.=$email{$i};
       }
       $i++;
   }
return $obfuscated;
}

//obfuscate_numeric takes a string and replaces the characters with html entity eqivalents. You have to do this if you want to obfuscate the label and have it display normally, or if you just want to obfuscate the "mailto" tag. Note: return string is almost certainly much longer than the plaintext string
function obfuscate_numeric($plaintext) {
    $i=0;
    $obfuscated="";
    while ($i<strlen($plaintext)) {
       if (rand(0,2)) {
          $obfuscated.='&#'.ord($plaintext{$i}) . ';';
       } else {
          $obfuscated.=$plaintext{$i};
       }
       $i++;
   }
return $obfuscated;
}

//this function drives the two above and generates a mailto link. if label isn't set, it just re-obfuscates the email address and uses that as the label
function create_mailto ($email,$label=null) {
if ($label) {
           return sprintf("<a href='%s:%s'>%s</a>",
           obfuscate_numeric('mailto'),
           obfuscate($email),
           obfuscate_numeric($label));
} else {
           return sprintf("<a href='%s:%s'>%s</a>",
           obfuscate_numeric('mailto'),
           obfuscate($email),
           obfuscate_numeric($email));
    }
}


class t_object_sorter
{
   var $object_array;
   var $sort_by;
  
   function _comp($a,$b)
   {
       $key=$this->sort_by;
       if ($this->object_array[$a]->$key == $this->object_array[$b]->$key) return 0;
       return ($this->object_array[$a]->$key < $this->object_array[$b]->$key) ? -1 : 1;
   }
  
   function _comp_desc($a,$b)
   {
       $key=$this->sort_by;
       if ($this->object_array[$a]->$key == $this->object_array[$b]->$key) return 0;
       return ($this->object_array[$a]->$key > $this->object_array[$b]->$key) ? -1 : 1;
   }
  
   function sort(&$object_array, $sort_by, $sort_type = "ASC")
   {
       $this->object_array = $object_array;
       $this->sort_by      = $sort_by;
       if ($sort_type == "DESC")
       {
           uksort($object_array, array($this, "_comp_desc"));
       }
       else
       {
           uksort($object_array, array($this, "_comp"));
       }
   }
}

#-----------------------------------------------#
# Purpose  : Returns a timestamp from a string based on the given format and default timezone if it's ambiguous.
#
# Supported formats
#
# %Y - year as a decimal number including the century
# %m - month as a decimal number (range 01 to 12)
# %d - day of the month as a decimal number (range 01 to 31)
#
# %H - hour as a decimal number using a 24-hour clock (range 00 to 23)
# %M - minute as a decimal number
#
function parse_date( $date, $format ) {
   // Builds up date pattern from the given $format, keeping delimiters in place.
   if( !preg_match_all( "/%([YmdHMp])([^%])*/", $format, $formatTokens, PREG_SET_ORDER ) ) {
       return false;
   }

   foreach( $formatTokens as $formatToken ) {
		if (isset($formatToken[2])) {
	       $delimiter = preg_quote( $formatToken[2], "/" );
		}
		else {
			$delimiter = '';
		}
		if (!isset($datePattern)) {
			$datePattern = "";
		}
       $datePattern .= "(.*)".$delimiter;
   }
  
   // Splits up the given $date
   if( !preg_match( "/".$datePattern."/", $date, $dateTokens) ) {
       return false;
   }
   $dateSegments = array();
   for($i = 0; $i < count($formatTokens); $i++) {
       $dateSegments[$formatTokens[$i][1]] = $dateTokens[$i+1];
   }

   // Reformats the given $date into US English date format, suitable for strtotime()
   if( $dateSegments["Y"] && $dateSegments["m"] && $dateSegments["d"] ) {
       $dateReformated = $dateSegments["Y"]."-".$dateSegments["m"]."-".$dateSegments["d"];
   }
   else {
       return false;
   }
   if( isset($dateSegments["H"]) && isset($dateSegments["M"]) ) {
       $dateReformated .= " ".$dateSegments["H"].":".$dateSegments["M"];
   }

   return strtotime( $dateReformated );
}


function object_sort($object_array, $field, $direction = "ASC") {
	$sorter = new t_object_sorter;
	$sorter->sort($object_array, $field, $direction);
	return $object_array;
}

function error_panel( $error ) {
	global $ep_errors;
	
	$panel = new HTML_Template_IT("templates");
	if (!$panel->loadTemplatefile('errors.tpl')) {
			$returned_panel = null;
	}
	else {
		$panel->touchBlock( $ep_errors[$error] );
		$returned_panel = $panel->get();
	}
	return $returned_panel;
}



function get_filetype( $filename ) {
	$file_extension = strtolower(substr(strrchr($filename,"."),1));

	switch( $file_extension ) {
		case "pdf": $type="acrobat"; break;
		case "zip": $type="zip"; break;
		case "doc": $type="word"; break;
		case "xls": $type="excel"; break;
		case "ppt": $type="powerpoint"; break;
		case "tif": $type="image_tif"; break;
		case "gif": $type="image_gif"; break;
		case "png": $type="image_png"; break;
		case "jpeg":
		case "jpg": $type="image_jpg"; break;
		case "htm":
		case "html": $type="html"; break;
		default: $type="unknown";
	}
	return $type;
}


function formatbytes($val, $digits = 3, $mode = "SI", $bB = "B"){ //$mode == "SI"|"IEC", $bB == "b"|"B"
	$si = array("", "k", "M", "G", "T", "P", "E", "Z", "Y");
	$iec = array("", "Ki", "Mi", "Gi", "Ti", "Pi", "Ei", "Zi", "Yi");
	switch(strtoupper($mode)) {
		case "SI" : $factor = 1000; $symbols = $si; break;
		case "IEC" : $factor = 1024; $symbols = $iec; break;
		default : $factor = 1000; $symbols = $si; break;
	}
	switch($bB) {
		case "b" : $val *= 8; break;
		default : $bB = "B"; break;
	}
	for($i=0;$i<count($symbols)-1 && $val>=$factor;$i++)
		$val /= $factor;
	$p = strpos($val, ".");
	if($p !== false && $p > $digits) $val = round($val);
	elseif($p !== false) $val = round($val, $digits-$p);
	return round($val, $digits) . " " . $symbols[$i] . $bB;
}


function store_file_upload($var, $location, $filename=NULL) {
	$ok = false;

	$mimes = new Mimes();
	
	// Check file
	$mime = $_FILES[$var]["type"];

	$name  = $_FILES[$var]["name"];
	$ext = file_extension($name);


	$ok = ( $mimes->supported($mime) && $mimes->supported($ext) );
	
	$ok = ($_FILES[$var]["size"] <= MAX_FILE_SIZE);
	
	if($ok==true) {
		$tempname = $_FILES[$var]['tmp_name'];
		if(isset($filename)) {
			$uploadpath = $location.$filename;
		} else {
			$uploadpath = $location.$_FILES[$var]['name'];
		}
		
		if(is_uploaded_file($_FILES[$var]['tmp_name'])) {
			while(move_uploaded_file($tempname, $uploadpath)) {
				// Wait for the script to finish its upload   
			}
		}
		return $uploadpath;
	}
	else {
		return false;
	}
}



function store_file_upload($var, $location, $filename=NULL, $maxfilesize=NULL) {
	$ok = false;

	// Check file
	$mime = $_FILES[$var]["type"];

	if (	$mime=="application/msword"
 	|| 	$mime=="application/pdf"
 	|| 	$mime=="application/vnd.ms-powerpoint"
 	|| 	$mime=="application/vnd.ms-excel"
 	|| 	$mime=="text/html"
 	|| 	$mime=="application/zip"
 	|| 	$mime=="application/gzip"
 	|| 	$mime=="application/x-zip"
 	|| 	$mime=="application/force-download"
	||	$mime=="image/jpeg"
 	|| 	$mime=="image/pjpeg"
		) {
			echo $mime;
		// Mime type is correct

		// Check extension
		$name  = $_FILES[$var]["name"];
		$array = explode(".", $name);
		$nr    = count($array);
		$ext  = $array[$nr-1];
		if(	$ext=="doc"
		 || $ext=="pdf"
		 || $ext=="ppt"
		 || $ext=="xls"
		 || $ext=="htm"
		 || $ext=="html"
		 || $ext=="zip"
		 || $ext=="gz"
		 || $ext=="rar"
		 || $ext=="jpg"
		 || $ext=="jpeg"
		) {
			$ok = true;
		}
	}


	
	if(isset($maxfilesize)) {
		if($_FILES[$var]["size"] > $maxfilesize) {
			$ok = false;
		}
	}
	
	if($ok==true) {
		$tempname = $_FILES[$var]['tmp_name'];
		if(isset($filename)) {
			$uploadpath = $location.$filename;
		} else {
			$uploadpath = $location.$_FILES[$var]['name'];
		}
		if(is_uploaded_file($_FILES[$var]['tmp_name'])) {
			while(move_uploaded_file($tempname, $uploadpath)) {
				// Wait for the script to finish its upload   
			}
		}
		return true;
	}
	else {
		return false;
	}
}





function mailto( $to, $subject, $body=null, $cc = null, $bcc = null) {
	$mail = 'mailto:' . $to . '?subject=' . htmlentities($subject, ENT_QUOTES);
	if ($cc) {
		$mail .= '&cc=' . $cc;
	}
	if ($bcc) {
		$mail .= '&bcc=' . $bcc;
	}
	if ($body) {
		$mail .= '&body=' . htmlentities($body, ENT_QUOTES);
	}

	return $mail;
}

function image_resize($filename, $dest, $width, $height, $pictype = "")
{
  $format = strtolower(substr(strrchr($filename,"."),1));
  switch($format)
  {
   case 'gif' :
   $type ="gif";
   $img = imagecreatefromgif($filename);
   break;
   case 'png' :
   $type ="png";
   $img = imagecreatefrompng($filename);
   break;
   case 'jpg' :
   $type ="jpg";
   $img = imagecreatefromjpeg($filename);
   break;
   case 'jpeg' :
   $type ="jpg";
   $img = imagecreatefromjpeg($filename);
   break;
   default :
   die ("ERROR; UNSUPPORTED IMAGE TYPE");
   break;
  }

  list($org_width, $org_height) = getimagesize($filename);
  $xoffset = 0;
  $yoffset = 0;
  if ($pictype == "thumb") // To minimize destortion
  {
   if ($org_width / $width > $org_height/ $height)
   {
     $xtmp = $org_width;
     $xratio = 1-((($org_width/$org_height)-($width/$height))/2);
     $org_width = $org_width * $xratio;
     $xoffset = ($xtmp - $org_width)/2;
   }
   elseif ($org_height/ $height > $org_width / $width)
   {
     $ytmp = $org_height;
     $yratio = 1-((($width/$height)-($org_width/$org_height))/2);
     $org_height = $org_height * $yratio;
     $yoffset = ($ytmp - $org_height)/2;
   }
  //Added this else part -------------
  } else {    
     $xtmp = $org_width/$width;
     $new_width = $width;
     $new_height = $org_height/$xtmp;
     if ($new_height > $height){
       $ytmp = $org_height/$height;
       $new_height = $height;
       $new_width = $org_width/$ytmp;
     }
     $width = round($new_width);
     $height = round($new_height);
  }
  

  $img_n=imagecreatetruecolor ($width, $height);
  imagecopyresampled($img_n, $img, 0, 0, $xoffset, $yoffset, $width, $height, $org_width, $org_height);

  if($type=="gif")
  {
   imagegif($img_n, $dest);
  }
  elseif($type=="jpg")
  {
   imagejpeg($img_n, $dest);
  }
  elseif($type=="png")
  {
   imagepng($img_n, $dest);
  }
  elseif($type=="bmp")
  {
   imagewbmp($img_n, $dest);
  }
  Return true;
}


function asciify($string) {
   if ( strlen($string) == 0 ) 
       return $string;
   $result = '';
   $string = preg_split("//", $string, -1, PREG_SPLIT_NO_EMPTY);
   $ord = 0;
   for ( $i = 0; $i < count($string); $i++ ) {
       $ord = ord($string[$i]);
       if ( $ord > 127 ) {
           $string[$i] = '_';
       }
   }
   return implode('',$string);
}

function strip_ext($f)
{
    return substr($f, 0, strrpos($f, '.'));
}

function get_ext($f) {
    $tmp = strrpos($f, '.'); /* finds the last occurence of . */
    if ($tmp=='0') {
        return '';
    } else {
        return substr($f, $tmp+1);
    }
}

function remove_dups($obj, $item) {
    $out = array(); 
    $list = array(); 
    foreach ($obj as $key=>$so) { 
        if (!in_array($so->$item, $list)) { 
            $list[] = $so->$item;
            $out[$key] = $so; 
        } 
    } 
    return $out;
}

/**
 * rm() -- Vigorously erase files and directories.
 * 
 * @param $fileglob mixed If string, must be a file name (foo.txt), glob pattern (*.txt), or directory name.
 *                        If array, must be an array of file names, glob patterns, or directories.
 */
function rm($fileglob)
{
   if (is_string($fileglob)) {
       if (is_file($fileglob)) {
           return unlink($fileglob);
       } else if (is_dir($fileglob)) {
           $ok = rm("$fileglob/*");
           if (! $ok) {
               return false;
           }
           return rmdir($fileglob);
       } else {
           $matching = glob($fileglob);
           if ($matching === false) {
/*               trigger_error(sprintf('No files match supplied glob %s', $fileglob), E_USER_WARNING);*/
               return false;
           }      
           $rcs = array_map('rm', $matching);
           if (in_array(false, $rcs)) {
               return false;
           }
       }      
   } else if (is_array($fileglob)) {
       $rcs = array_map('rm', $fileglob);
       if (in_array(false, $rcs)) {
           return false;
       }
   } else {
/*       trigger_error('Param #1 must be filename or glob pattern, or array of filenames or glob patterns', E_USER_ERROR);*/
       return false;
   }

   return true;
}


function filesize_r($path){
	if(!file_exists($path)) return 0;
	if(is_file($path)) return filesize($path);
	$self = __FUNCTION__;
	$ret = 0;
	foreach(glob($path."/*") as $fn)
	$ret += $self($fn);
	return $ret;
}

function activerecord2smartyarray($table) {
	$new_table = array();
	foreach ($table as $table_entry) {
		$current_table_record = array();
		$current_table_record_id = 0;
		foreach($table_entry as $key => $value) {
			if ($key == 'date') {
				$value = strtotime($value);
			}
			$current_table_record[$key] = $value;
		}
		$new_table[] = $current_table_record;
	}
	
	return $new_table;
}


?>