<?php

require_once('lib/php/support/pclzip/pclzip.lib.php');
$archive = new PclZip('archive.zip');
$v_list = $archive->add(pathinfo(__FILE__, PATHINFO_DIRNAME),  PCLZIP_OPT_REMOVE_PATH, 'dev');

?>
