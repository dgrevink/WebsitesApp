<?php

	function resize_image($uploadedfile, $dest, $setwidth, $setheight) {
		$src = imagecreatefromjpeg($uploadedfile);
		list($width,$height) = getimagesize($uploadedfile);
		if ($width > $height) {
			$newwidth=$setwidth;
			$newheight=($height/$width)*$setwidth;
		}
		else {
			$newheight=$setheight;
			$newwidth=($width/$height)*$setheight;
		}
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		imagejpeg($tmp,$dest,80);
		imagedestroy($src);
		imagedestroy($tmp);
	}
	
	function recrop_image($uploadedfile, $dest, $setwidth, $setheight) {
		$src = imagecreatefromjpeg($uploadedfile);
		list($width,$height) = getimagesize($uploadedfile);
		$newwidth=$setwidth;
		$newheight=($height/$width)*$setwidth;
		$tmp=imagecreatetruecolor($setwidth,$setheight);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		imagejpeg($tmp,$dest,80);
		imagedestroy($src);
		imagedestroy($tmp);
	}

