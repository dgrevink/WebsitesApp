<?php

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

function widget_get_element($element, $code) {
	$lines = explode("\n", $code);
	foreach ($lines as $line) {
		switch ($element) {
			case 'NAME':
				if (substr($line, 0, 5) == 'class') {
					$parts = preg_split("/[\s,]+/", $line);
					if (isset($parts[1])) return $parts[1];
				}
			break;
			case 'NOTE':
			case 'RNAME':
			case 'PATH':
			case 'VERSION':
			case 'INIT':
			case 'ACTIVE':
				if (preg_match('/\b' . $element . '\b/', $line) != 0 ) {
					return trim(substr($line, strpos($line, ':')+1, strlen($line)));
				}
			break;
		}
	}
}

function widget_set_element($element, $value, $code) {
	$new = array();
	$lines = explode("\n", $code);
	foreach ($lines as $line) {
		switch ($element) {
			case 'ACTIVE':
				if (preg_match('/\b' . $element . '\b/', $line) != 0 ) {
					if ($value) {
						$line = " *	ACTIVE: YES";
					}
					else {
						$line = " *	ACTIVE: NO";
					}
				}
			break;
		}
		$new[] = $line;
	}
	return implode("\n", $new);
}

