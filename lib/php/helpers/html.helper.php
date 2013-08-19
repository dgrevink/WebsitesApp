<?php

function encrypt_mailto($buffer) {
	
	$buffer = preg_replace("/[\"\']mailto:([A-Za-z0-9._%-]+)\@([A-Za-z0-9._%-]+)\.([A-Za-z.]{2,4})[\"\'\?]/e", "'\"contact/'.str_rot13('\\1').'+'.str_rot13('\\2').'+'.str_rot13('\\3').'\" rel=\"nofollow\" '", $buffer);
	
	return $buffer;

//  return preg_replace("/\"mailto:([A-Za-z0-9._%-]+)\@([A-Za-z0-9._%-]+)\.([A-Za-z.]{2,4})\"/", "\"contact/\\1+\\2+\\3\" rel=\"nofollow\"", $buffer);
}

function encrypt_mail($buffer) {
	//$buffer = preg_replace("/([A-Za-z0-9._%-]+)\@([A-Za-z0-9._%-]+)\.([A-Za-z.]{2,4})/e", 'contact/'.str_rot13('\\1').'+'.str_rot13('\\2').'+'.str_rot13('\\3'), $buffer);

	$buffer = preg_replace("/([A-Za-z0-9._%-]+)\@([A-Za-z0-9._%-]+)\.([A-Za-z.]{2,4})/e", "''.str_rot13('\\1').'+'.str_rot13('\\2').'+'.str_rot13('\\3').''", $buffer);
	
	return $buffer;
//	return preg_replace("/([A-Za-z0-9._%-]+)\@([A-Za-z0-9._%-]+)\.([A-Za-z.]{2,4})/", "contact/\\1+\\2+\\3", $buffer);
}

