<?php

$filename = array_shift(glob(WS_APPLICATION_FOLDER . '/cache/cache/' . md5($_SERVER['REQUEST_URI']) . '*.tpl'));

if (file_exists($filename)) {
	$_contents = file_get_contents($filename);
	$_info_start = strpos($_contents, "\n") + 1;
	$_info_len = (int)substr($_contents, 0, $_info_start - 1);
	$_cache_info = unserialize(substr($_contents, $_info_start, $_info_len));
	if ($_cache_info['expires'] > time()) {
		echo substr($_contents, $_info_start + $_info_len);
		die();
	}
}

