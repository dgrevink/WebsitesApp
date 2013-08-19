<?php

/**

	file_force_contents($dir, $contents)
function file_extension($name) {
function file_basename($name) {


*/

    function file_force_contents($dir, $contents){
        $parts = explode('/', $dir);
        $file = array_pop($parts);
        $dir = '';
        foreach($parts as $part)
            if(!is_dir($dir .= "/$part")) mkdir($dir);
        file_put_contents("$dir/$file", $contents);
    }
    

function file_extension($name) {
	$array = explode(".", $name);
	$nr    = count($array);
	return $array[$nr-1];
}

function file_basename($name) {
	$array = explode(".", $name);
	
	return reset($array);
}



function load_file( $file ) {
	$handle = @fopen( $file, 'r');
	if ($handle == null) {
		return null;
	}
	else {
		$result = fread( $handle, 1024*1024*1024 );
		fclose($handle);
		return $result;
	}
}

function save_file( $file, $data ) {
	$handle = @fopen($file, 'w');
	if ($handle == null) {
		return false;
	}
	else {
		fwrite($handle, $data);
		fclose($handle);
		return true;
	}
}

function truncate_file($file) {
	$fh = fopen($file, 'w');
	fclose($fh);
}


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
            return @rmdir($fileglob);
        } else {
            $matching = glob($fileglob);
            if ($matching === false) {
                //trigger_error(sprintf('No files match supplied glob %s', $fileglob), E_USER_WARNING);
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
        trigger_error('Param #1 must be filename or glob pattern, or array of filenames or glob patterns', E_USER_ERROR);
        return false;
    }

    return true;
}

function append_file($filename, $text) {
	$fh = fopen($filename, 'a');
	if ($fh) {
		$string = $text;
		if (is_array($text)) {
			$string = implode("\n", $text);
		}
		fwrite($fh, $string);
		fclose($fh);
	}
}

function get_dir_size($path)  { 
  $totalsize = 0; 
  $totalcount = 0; 
  $dircount = 0; 
  if ($handle = opendir ($path)) { 
    while (false !== ($file = readdir($handle))) { 
      $nextpath = $path . '/' . $file; 
      if ($file != '.' && $file != '..' && !is_link ($nextpath) && ($file[0] != '.')) { 
        if (is_dir ($nextpath)) { 
          $dircount++; 
          $result = get_dir_size($nextpath); 
          $totalsize += $result['size']; 
          $totalcount += $result['count']; 
          $dircount += $result['dircount']; 
        } 
        elseif (is_file ($nextpath)) { 
          $totalsize += filesize ($nextpath); 
          $totalcount++; 
        } 
      } 
    } 
  } 
  closedir ($handle); 
  $total['size'] = $totalsize; 
  $total['count'] = $totalcount; 
  $total['dircount'] = $dircount; 
  return $total; 
} 

