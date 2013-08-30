<?php

WSLoader::load_helper('file');
WSLoader::load_helper('misc');
WSLoader::load_base('log');
WSLoader::load_support('phpthumb');
WSLoader::load_support('phpthumb-filters');

class Files extends WSController {
	
	public $module_name = 'Media';
	public $module_version = '2.0';
	
	public $username = '';

	function Files($smarty=null, $language=null, $current_language=null, $params=null, $parameters=null, &$auth=null) {
		parent::WSController();
		
		$this->smarty 			= (isset($this->smarty)				? $this->smarty				: null);
		$this->language 		= (isset($this->language)			? $this->language			: null);
		$this->current_language = (isset($this->current_language)	? $this->current_language	: null);
		$this->params 			= (isset($this->params)				? $this->params				: null);
		$this->parameters 		= (isset($this->parameters)			? $this->parameters			: null);
		$this->auth 			= (isset($this->auth)				? $this->auth				: null);
		
		$this->smarty 			= ($smarty==null			? $this->smarty				: $smarty);
		$this->language 		= ($language==null			? $this->language			: $language);
		$this->current_language = ($current_language==null	? $this->current_language	: $current_language);
		$this->params 			= ($params==null			? $this->params				: $params);
		$this->parameters 		= ($parameters==null		? $this->parameters			: $parameters);
		$this->auth				= ($auth==null				? $this->auth				: $auth);
	}

	function _index() {
		if (!$this->_check_rights(WSR_FILES)) {
			return false;
		}
		
		$dir = 'public/';
		if (count($this->params) > 2) {
			$dir .= implode('/', array_slice($this->params, 2, count($this->params) -1 )) . '/';
		}
		$smarty_contents = new Template;
		$smarty_contents->assign('current_page_title', $this->_dir_to_url($dir));
		$smarty_contents->assign('current_short_page_title', $dir);
		
		$glob = glob(WS_ROOT . $dir . '{,.}*', GLOB_BRACE);
		natcasesort($glob);
		$files = array();
		$wdir = '';
		$counter = 0;

		foreach ($glob as $file) {
			$bs = basename($file);
			if (
					($bs != '_thumbs')
				&&	($bs[0] != '.')
				) {
				$ft = filetype($file);
				$size = null;
				$fsize = null;
				if ($ft == 'file') {
					$ft = file_extension($file);
					$size = formatbytes(filesize($file));
					$fsize = '';
					switch ($ft) {
						case 'jpg':
						case 'gif':
						case 'png':
						case 'bmp':
								$fsize = @getimagesize($file);
							break;
						case 'pdf':
						case 'flv':
						case 'fla':
						default:
							break;
					}
				}
				else {
					$s = get_dir_size($file);
					$size = formatbytes($s['size']);
				}
				if ($fsize) {
					$size = $size . ' (' . $fsize[0] . 'x' . $fsize[1] . ')';
					if (isset($fsize['channels'])) {
						$size .= (@$fsize['channels'] == 4?"<span title='Ce fichier est en JPG-CMYK. Il pourrait ne pas &ecirc;tre bien affich&eacute; sur certains navigateurs.' style='color: red; font-weight: bold;'>!</span>":'');
					}
				}
				$nodelete = false;
				if ($dir == 'public/') $nodelete = true;
				if ($ft != 'wscomment') {
					$counter++;
					$files[] = array(
					'id' => $counter,
					'name' => ($ft=='dir')?basename($file).'/':basename($file),
					'wdir' => $dir,
					'size' => $size,
					'type' => $ft,
					'img' => $fsize,
					'nodelete' => $nodelete,
	//				'permissions' => file_permissions($file),
	//				'permissions_octal' => file_permissions_octal($file),
					'date' => date('Y-m-d', filemtime($file)),
					'fullpath' => '/' . $dir . basename($file)
					);
				}
			}
		}
		
		$count = 0;
		foreach($files as $file) {
			
			$comment = @file_get_contents(WS_ROOT . $files[$count]['fullpath'] . '.wscomment');
			if ($comment) {
				$files[$count]['comment'] = $comment;
			}
			else {
				$files[$count]['comment'] = '';
			}
//			$files[$count]['comment'] = WS_ROOT . $files[$count]['fullpath'] . '.wscomment';
			$count ++;
		}
		
		$name = WS_ROOT . 'public/' . implode('/', array_slice($this->params, 2, count($this->params) -1 ));
		
		$current_template = 'index';
		if (file_exists($name)) {
			if (filetype($name) == 'file') {
				$ext = file_extension(WS_ROOT . 'public/' . implode('/', array_slice($this->params, 2, count($this->params) -1 )));
				switch ($ext) {
					case 'jpg':
					case 'gif':
					case 'png':
					case 'bmp':
						$current_template = 'image';
						list($width, $height, $type, $attr) = getimagesize($name);
						$smarty_contents->assign('fileinfo', $width . 'x' . $height . ' ');
						break;
					case 'pdf':
					case 'flv':
					case 'fla':
						$current_template = 'media';
						break;
					default:
						$current_template = 'file';
						switch($ext) {
							case 'php':
								$smarty_contents->assign('mode', 'php');
							break;
							case 'css':
								$smarty_contents->assign('mode', 'css');
							break;
							case 'js':
								$smarty_contents->assign('mode', 'javascript');
							break;
							case 'html':
							case 'htm':
								$smarty_contents->assign('mode', 'html');
							break;
							case 'php':
								$smarty_contents->assign('mode', 'php');
							break;
							default:
								$smarty_contents->assign('mode', 'plain_text');
							break;
						}
						$smarty_contents->assign('fileinfo', $this->_dir_to_url('public/' . implode('/', array_slice($this->params, 2, count($this->params) -1 ))) . '/' . basename($name));
						$smarty_contents->assign('wfilecontent', file_get_contents($name));
						break;
				}
				$smarty_contents->assign('wfile', 'public/' . implode('/', array_slice($this->params, 2, count($this->params) -1 )));
			}
		}
		
		$comment_file = WS_ROOT . 'public/' . implode('/', array_slice($this->params, 2, count($this->params) -1 )) . '/.wscomment';
		if (file_exists($comment_file)) {
			$smarty_contents->assign('comment' , nl2br(file_get_contents($comment_file)));
		}

		$smarty_contents->assign('files', $files);
		$smarty_contents->assign('wdir', $dir);
		$smarty_contents->assign('key', md5($this->auth->session['username']));
		$smarty_contents->assign('WSR_FILES_NORMALIZE', $this->_check_rights(WSR_FILES_NORMALIZE));
		$smarty_contents->assign('WSR_FILES_CREATE_FILE', $this->_check_rights(WSR_FILES_CREATE_FILE));
		$smarty_contents->assign('WSR_FILES_CREATE_DIR', $this->_check_rights(WSR_FILES_CREATE_DIR));
		$smarty_contents->assign('WSR_FILES_UPLOAD', $this->_check_rights(WSR_FILES_UPLOAD));
		$smarty_contents->assign('WSR_FILES_RENAME', $this->_check_rights(WSR_FILES_RENAME));
		$smarty_contents->assign('WSR_FILES_DELETE', $this->_check_rights(WSR_FILES_DELETE));
		$smarty_contents->assign('WSR_FILES_DUPLICATE', $this->_check_rights(WSR_FILES_DUPLICATE));
		$smarty_contents->assign('WSR_FILES_DOWNLOAD', $this->_check_rights(WSR_FILES_DOWNLOAD));
		$smarty_contents->assign('WSR_FILES_COMMENT_DIR', $this->_check_rights(WSR_FILES_COMMENT_DIR));
		$smarty_contents->assign('upload_max_filesize', ini_get('upload_max_filesize'));
		$smarty_contents->assign('ZIP_SUPPORTED', class_exists('ZipArchive'));

		$dir_size = 0;
		$dcount = 0;
		$fcount = 0;
		if (filetype($name) != 'file') {
			$size = get_dir_size($name);
			$dir_size = formatbytes($size['size']);
			$fcount = 0;
			$dcount = 0;
			foreach ($glob as $file) {
				$bname = basename($file);
				if ( ($bname[0] != '.') && ($bname[0] != '_') ) {
					if ( is_dir($file) ) {
						$dcount++;
					}
					else {
						$fcount++;
					}
				}
			}
		}
		$smarty_contents->assign('dir_size', $dir_size);
		$smarty_contents->assign('file_count', $fcount);
		$smarty_contents->assign('dir_count', $dcount);

		$app_config = new WSConfig;
		$app_config->load(WS_ADMINISTERED_APPLICATION_FOLDER . '/config/');

		if ( ($app_config->get('deployment') != 'local') && sprintf("%o",fileperms($name)) != '40777') {
			$smarty_contents->assign('RIGHTS_WARNING', true);
		}

		return $smarty_contents->fetch( "files-{$current_template}-{$this->language}.tpl" );
	}
	
	function _dir_to_url($dir) {
		$vector = explode('/', $dir);
		array_pop($vector);
		$dir2 = '';
		$stack = array();
		foreach($vector as $el) {
			if (count($stack == 0) && ($el == 'public')) {
				$dir2 .= "<a href='/admin/fr/files/'>/" . $el . '</a>';
			}
			else {
				array_push($stack, $el);
				$dir2 .= "<a href='/admin/fr/files/" . implode('/', $stack) . "/'>/" . $el . '</a>';
			}
		}
		return $dir2;
	}

	function rename() {
		$dir = WS_ROOT . $_POST['dir'] . '/';
		$old = rtrim($_POST['old'], '/');
		$new = rtrim($_POST['new'], '/');
		
		$this->username = $this->auth->session['username'];
		
		if (@rename($dir . $old, $dir . $new)) {
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Renamed file ' . $_POST['dir'] . $old . ' to ' . $_POST['dir'] . $new);
			$this->_normalize($dir, $new);
			echo 'OK';
		}
		else {
			WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not rename file ' . $_POST['dir'] . $old . ' to ' . $_POST['dir'] . $new);
			echo 'KO';
		}
	}

	function duplicate() {
		$dir = WS_ROOT . $_POST['dir'] . '/';
		$old = $_POST['old'];
		
		if ($this->_duplicate($dir, $old)) {
			echo 'OK';
		}
		else {
			echo 'KO';
		}
	}

	function normalize() {
		$dir = WS_ROOT . $_POST['dir'] . '/';
		$old = $_POST['old'];
		if (!$this->_get_username_from_key($_POST['key'])) {
			echo 'KO';
		}
		
		if ($this->_normalize($dir, $old)) {
			echo 'OK';
		}
		else {
			echo 'KO';
		}
	}
	
	function normalize_dir() {
		$dir = WS_ROOT . $_POST['dir'];
		$files = glob($dir . '*');
		$ok = true;
		foreach($files as $file) {
			$ok = $ok && $this->_normalize($dir, basename($file));
		}
		if ($ok) {
			echo 'OK';
		}
		else {
			echo 'KO';
		}
	}
	
	function create_file() {
		$dir = WS_ROOT . @$_POST['dir'];
		$file = @$_POST['filename'];
		$fh = fopen($dir . $file, 'w');
		if ($fh) {
			fclose($fh);
			$this->_normalize($dir, $file);
			echo 'OK';
		}
		else {
			echo 'KO';
		}
	}
	
	function createdir() {
		$dir = WS_ROOT . @$_POST['dir'];
		$dirname = @$_POST['dirname'];
		if (file_exists($dir . $dirname)) {
			echo 'KO';
		}
		else {
			$mask = umask(0);
			mkdir($dir . $dirname, 0777);
			umask($mask);
			$this->_normalize($dir, $dirname);
			echo 'OK';
		}
	}

	function removecommentdir() {
		$dir = WS_ROOT . @$_POST['dir'] . '.wscomment';
		if (unlink($dir)) {
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Removed comment on directory ' . $_POST['dir']);
			echo 'OK';
		}
		else {
			WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not remove comment on directory ' . $_POST['dir']);
			echo 'KO';
		}
	}

	function getcommentfile() {
		$file = WS_ROOT . @$_POST['dir'] . '.wscomment';
		if (file_exists($file)) echo file_get_contents($file);
	}


	function commentfile() {
		$file = WS_ROOT . @$_POST['dir'] . '.wscomment';
		$comment = isset($_POST['comment'])?$_POST['comment']:false;
		if ($comment == '') $comment = false;
		if (!$comment) {
			@unlink($file);
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Removed comment on file ' . $_POST['dir']);
			echo 'OK';
		}
		else {
			if (!file_put_contents($file, @$_POST['comment'])) {
				WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not comment on file ' . $_POST['dir']);
				echo 'KO';
			}
			else {
				WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Commented on file ' . $_POST['dir']);
				echo 'OK';
			}
		}
	}


	function getcommentdir() {
		$dir = WS_ROOT . @$_POST['dir'] . '.wscomment';
		if (file_exists($dir)) echo file_get_contents($dir);
	}

	function commentdir() {
		$dir = WS_ROOT . @$_POST['dir'] . '.wscomment';
		if (!file_put_contents($dir, @$_POST['comment'])) {
			WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not comment on directory ' . $_POST['dir']);
			echo 'KO';
		}
		else {
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Commented on directory ' . $_POST['dir']);
			echo 'OK';
		}
	}
	
	function delete() {
		$dir = $_POST['dir'] . '/';
		$old = rtrim($_POST['old'], '/');
		
		$path = str_ireplace('//','/', '/'.$dir.$old);
		
		if (rm(WS_ROOT . $dir . $old)) {
			rm(WS_ROOT . $dir . $old . '.wscomment');
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Deleted ' . $path);
			echo 'OK';
		}
		else {
			WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not delete ' . $path);
			echo 'KO';
		}
	}
	
	function deleteall() {
		$files = rtrim($_POST['files'], ',');
		
		$files = explode(',', $files);
		foreach($files as $file) {
			if (rm(WS_ROOT . $file)) {
				rm(WS_ROOT . $file . '.wscomment');
				WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Deleted ' . $file);
			}
			else {
				WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not delete ' . $path);
			}
		}
		echo 'OK';
	}
	
	function getdirs() {
		$scannedfiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(WS_ROOT . 'public/'), RecursiveIteratorIterator::SELF_FIRST); 

		$files = array();

		$beginoffset = strlen(WS_ROOT);
		foreach($scannedfiles as $file) {
			if (is_dir($file)) {
				$file = '/' . substr($file, $beginoffset). '/';
				$files[] = $file;
			}
		}
		echo implode(',', $files);
	}

	function move() {
		$dest = $_POST['dest'] . '/';
		$files = explode(',', rtrim($_POST['files'], ','));
		
//		if (rm(WS_ROOT . $dir . $old)) {
//			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Deleted ' . $path);
//			echo 'OK';
//		}
//		else {
//			WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not delete ' . $path);
//			echo 'KO';
//		}
		foreach($files as $file) {
			rename(WS_ROOT . $file, WS_ROOT . $dest . basename($file));
			rename(WS_ROOT . $file . '.wscomment', WS_ROOT . $dest . basename($file) . '.wscomment');
		}
		echo 'OK';
	}
	

	function send() {
		$dest_dir = WS_ROOT . $_REQUEST['folder'];
		
		if (!$this->_get_username_from_key($_REQUEST['key'])) {
			die();
		}
		
		$accepted_extensions = array(
			'txt',
			'jpg',
			'jpeg',
			'gif',
			'png',
			'pdf',
			'doc',
			'xls',
			'docx',
			'xlsx'
		);

		$accepted = array(
			'text/plain',
			'application/zip',
			'application/x-shockwave-flash',
			'image/jpeg',
			'image/pjpeg',
            'image/gif',
            'image/bmp',
            'image/png',
            'application/pdf',
            'application/vnd.ms-excel',
            'application/msword',
            'application/octet-stream',
            'text/xml',
            ''
		);
		
		$errors = array();
		$ok = array();

			if (in_array($_FILES['Filedata']['type'], $accepted)) {
				if (file_extension(strtolower($_FILES['Filedata']['name'])) == 'zip') {
					 $zip = new ZipArchive;
				     $res = $zip->open($_FILES['Filedata']['tmp_name']);
				     if ($res === TRUE) {
						WSLog::admin( WS_INFO, $username, 0, 'Uploaded ZIP ARCHIVE ' . $_FILES['Filedata']['name'] . ' - Size: ' . $_FILES['Filedata']['size'] . ' bytes.');
					    for($i = 0; $i < $zip->numFiles; $i++) {
					    	$filename = $zip->getNameIndex($i);
//					    	if ( strstr($filename, '/') ) continue; // Skip directories (unix)
					    	if ( strstr($filename, '\\') ) continue; // Skip directories (windows)
					    	if ( substr( $filename, 0, 1 ) == '.' ) continue; // Skip hidden files
					    	if ( $filename == '__MACOSX' ) continue; // Skip Mac OS dirty files
				    		if (in_array(file_extension($filename), $accepted_extensions ) ) {
						        $fileinfo = pathinfo($filename);
						        copy("zip://".$_FILES['Filedata']['tmp_name']."#".$filename, $dest_dir.$fileinfo['basename']);
								WSLog::admin( WS_INFO, $username, 0, 'Extracted from ZIP ARCHIVE ' . $_FILES['Filedata']['name'] . ' - File: ' . $filename);
						        $this->_normalize($dest_dir, $filename);
				    		}
					    }
				         $zip->close();
				     }
				}
				else {
					@move_uploaded_file($_FILES['Filedata']['tmp_name'], $dest_dir . $_FILES['Filedata']['name']);
					WSLog::admin( WS_INFO, $username, 0, 'Uploaded ' . $_FILES['Filedata']['name'] . ' to ' . $_REQUEST['folder'] . ' - Type: ' . $_FILES['Filedata']['type'] . ' - Size: ' . $_FILES['Filedata']['size'] . ' bytes.');
					$this->_normalize($dest_dir, $_FILES['Filedata']['name']);
				}
				$ok[] = $_FILES['Filedata']['name'];
			}
			else {
					WSLog::admin( WS_ERROR, $username, 0, 'Could not upload ' . $_FILES['Filedata']['name'] . ' to ' . $_REQUEST['folder'] . ' - Type: ' . $_FILES['Filedata']['type'] . ' - Size: ' . $_FILES['Filedata']['size'] . ' bytes.');
					$errors[] = $_FILES['Filedata']['name'];
			}
		
		if (count($ok) > 0) {
			echo 1;
		}
		
		if (count($errors) > 0) {
			echo 0;
		}
		
	}
	
	function _normalize($dir, $file) {
		$old = rtrim($file, '/');
		$new = normalize_string($old);
		$new = str_replace('\\', '', $new);

		if (substr($old, strlen($old)-4,1) == '.') {
			$new = substr($new, 0, strlen($new)-4) . '.' . substr($new, strlen($new)-3,3);
		}
		
		if (substr($old, 0, 1) == '.') {
			$new = '.' . substr($new, 1, strlen($new)-1);
		}
		
		$mask = umask(0);
		$result = @rename($dir . $old, $dir . $new);
		umask($mask);

		if ($result) {
			@rm($dir . $new . '.wscomment');
			@rename($dir . $old  . '.wscomment', $dir . $new . '.wscomment');
			WSLog::admin( WS_INFO, $this->username, 0, 'Normalized (renamed) file ' . $file . ' to ' . $new);
		}
		else {
			WSLog::admin( WS_ERROR, $this->username, 0, 'Could not normalize (rename) file ' . $file);
		}

		return $result;
	}

	function _duplicate($dir, $file) {
		$path_parts = pathinfo($file);
		$src = $dir . $file;
		$dest = $dir . $path_parts['filename'] . '-copy.' . $path_parts['extension'];
		if (file_exists($dest)) {
			WSLog::admin( WS_ERROR, $this->username, 0, 'Could not duplicate file ' . $file . ', it already exists !');
			return false;
		}
		$result = @copy($src, $dest);
		if ($result) {
			@copy($src . '.wscomment', $dest . '.wscomment');
			WSLog::admin( WS_INFO, $this->username, 0, 'Duplicated file ' . $file . ' to ' . $path_parts['filename'] . '-copy.' . $path_parts['extension']);
		}
		else {
			WSLog::admin( WS_ERROR, $this->username, 0, 'Could not duplicate file ' . $file);
		}
		return $result;
	}

	function _check_rights( $level ) {
		$this->username = $this->auth->session['username'];
		$user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
		$user_group = $user->find_parent('groups');
		$modules 	= unserialize($user_group->rights);
		$tables 	= unserialize($user_group->datarights);
		if (!isset($modules[$level])) {
			return false;
		}
		else {
			return true;
		}
	}

	function save() {
		$src = WS_ROOT . $_POST['filename'];
		if (@file_put_contents($src, $_POST['filecontent'])) {
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Saved contents of file ' . $_POST['filename']);
			echo "<span class='ok'>";
			echo "Sauvegarde effectu&eacute;e.";
			echo "</span>";
		}
		else {
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Could not save contents of file ' . $_POST['filename']);
			echo "<span class='error'>";
			echo "Le fichier n'a pas pu &ecirc;tre sauvegard&eacute;. Consultez votre administrateur.";
			echo "</span>";
		}
	}

	function rotate() {
		$src = WS_ROOT . $_POST['filename'];
		$angle = $_POST['angle'];

		$jpeg_quality = 90;

		$ext = strtolower(file_extension($src));
		$img_r = null;
		switch ($ext) {
			case 'jpg':
			case 'jpeg':
				$img_r = imagecreatefromjpeg($src);
			break;
			case 'gif':
				$img_r = imagecreatefromgif($src);
			break;
			case 'png':
				$img_r = imagecreatefrompng($src);
			break;
		}
		
		$width  = imagesx($img_r);
		$height = imagesy($img_r);
		$img_d = null;

		switch ($angle) {
			case '-90':
				$img_d = imagerotate($img_r, $angle, 0);
				break;
			case '90'://				$img_d   = imagecreatetruecolor($height, $width);
				$img_d = imagerotate($img_r, $angle, 0);
				break;
			case '180':
				$img_d = imagerotate($img_r, $angle, 0);
				break;
			case 'flipver':
				$img_d = imagecreatetruecolor($width, $height);
	            for($i=0;$i<$height;$i++){
	                imagecopy($img_d, $img_r, 0, ($height - $i - 1), 0, $i, $width, 1);
	            }
	            break;
            	case 'fliphor':
				$img_d   = imagecreatetruecolor($width, $height);
				for($i=0;$i<$width;$i++){
					imagecopy($img_d, $img_r, ($width - $i - 1), 0, $i, 0, 1, $height);
				}
            	break;
		}

		switch ($ext) {
			case 'jpg':
			case 'jpeg':
				header('Content-type: image/jpeg');
				imagejpeg($img_d, $src, $jpeg_quality);
			break;
			case 'gif':
				header('Content-type: image/gif');
				imagegif($img_d, $src);
			break;
			case 'png':
				header('Content-type: image/png');
				imagepng($img_d, $src);
			break;
		}
	}
	
	function crop() {
	
		$targ_w = $_POST['destwidth'];
		$targ_h = $_POST['destheight'];
		if (!is_numeric($_POST['destwidth'])) {
			$targ_w = $_POST['width'];
			$targ_h = $_POST['height'];
		}
	
		$jpeg_quality = 90;
		
		$src = WS_ROOT . $_POST['filename'];
		$ext = strtolower(file_extension($src));
		$img_r = null;
		switch ($ext) {
			case 'jpg':
			case 'jpeg':
				$img_r = imagecreatefromjpeg($src);
			break;
			case 'gif':
				$img_r = imagecreatefromgif($src);
			break;
			case 'png':
				$img_r = imagecreatefrompng($src);
				imageAlphaBlending($img_r, true);
				imageSaveAlpha($img_r, true);
			break;
		}
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		$background = imagecolorallocate($dst_r, 0, 0, 0);
		ImageColorTransparent($dst_r, $background); // make the new temp image all transparent
		imageAlphaBlending($dst_r, true);
						
		if ($_POST['crop'] == 'true') {
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['width'],$_POST['height']);
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Cropped file ' . $_POST['filename'] . ' from ' . $_POST['x'] . ', ' . $_POST['y'] . ' to ' . $_POST['width'] . ', ' . $_POST['height']);
		}
		else {
			$white =  imagecolorallocate($dst_r, 255, 255,255 );
			imagefill($dst_r, 0, 0, $white);
			$offset_x = floor( ($_POST['destwidth'] - imagesx($img_r)) / 2 );
			$offset_y = floor( ($_POST['destheight'] - imagesy($img_r)) / 2 ); 
			imagecopy($dst_r,          $img_r,          $offset_x,         $offset_y,         0,         0,         imagesx($img_r), imagesy($img_r));
			WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Padded file ' . $_POST['filename'] . ' from ' . $_POST['x'] . ', ' . $_POST['y'] . ' to ' . $_POST['width'] . ', ' . $_POST['height']);
		}
		
		switch ($ext) {
			case 'jpg':
			case 'jpeg':
				header('Content-type: image/jpeg');
				imagejpeg($dst_r, $src, $jpeg_quality);
			break;
			case 'gif':
				header('Content-type: image/gif');
				imagegif($dst_r, $src);
			break;
			case 'png':
				header('Content-type: image/png');
				imageSaveAlpha($dst_r, true);
				imagepng($dst_r, $src);
			break;
		}
		
	}
	

	function _get_username_from_key($key) {
		$username = null;
		foreach(MyActiveRecord::FindAll('users') as $user) {
			if (md5($user->username) == $key) {
				$username = $user->username;
			}
		}
		if (!$username) {
			return false;
		}
		$this->username = $username;
		return $username;
	}

	function zip() {
		if (count($this->params) < 2) die();
		if ($this->params[0] != 'public') die();
		$path = '/' . implode('/', $this->params);
		$path = str_replace('..', '', $path);
		$zip_name = str_replace('/', '_', $path);
		$zip_name = substr($zip_name, 1, strlen($zip_name));
		$short_path = $path;
		$path = WS_ADMINISTERED_APPLICATION_FOLDER . '..' . $path;
		if (!file_exists($path)) die();
		if (filetype($path) != 'dir') die();

		ini_set('max_execution_time', 300);
		
		if (!class_exists('ZipArchive')) die();

		// create object
		$zip = new ZipArchive();
		
		// open output file for writing
		if ($zip->open(WS_ADMINISTERED_APPLICATION_FOLDER . '../public/' . $zip_name . '.zip', ZIPARCHIVE::CREATE) !== TRUE) {
		    die ("Could not open archive");
		}

		// initialize an iterator
		// pass it the directory to be processed
		$iterator  = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

		// iterate over the directory
		// add each file found to the archive
		$rpath = realpath($path);
		foreach ($iterator as $key=>$value) {
			$index = strstr(realpath($key), $short_path);
		    $zip->addFile(realpath($key), './' . $zip_name . '/' . $index) or die ("ERROR: Could not add file: $key");        
		}

		// close and save archive
		$zip->close();
		
     header("Cache-Control: public");
     header("Content-Description: File Transfer");
     header("Content-Disposition: attachment; filename=$zip_name");
     header("Content-Type: application/zip");
     header("Content-Transfer-Encoding: binary");
    
     // Read the file from disk
     readfile(WS_ADMINISTERED_APPLICATION_FOLDER . "../public/${zip_name}.zip");
     unlink(WS_ADMINISTERED_APPLICATION_FOLDER . "../public/${zip_name}.zip");
	}
	
	function set_filter() {
		set_time_limit(120);

		$src = WS_ROOT . $_POST['filename'];
		$action = $_POST['action'];
		
		$ext = strtolower(file_extension($src));
		$image = null;
		switch ($ext) {
			case 'jpg':
			case 'jpeg':
				$image = imagecreatefromjpeg($src);
			break;
			case 'gif':
				$image = imagecreatefromgif($src);
			break;
			case 'png':
				$image = imagecreatefrompng($src);
			break;
		}

		switch ($action) {
			case 'BlurSelective':
				phpthumb_filters::BlurSelective($image);
			break;
			case 'BlurGaussian':
				phpthumb_filters::BlurGaussian($image);
			break;
			case 'Sharpen':
				phpthumb_filters::UnsharpMask($image, 80, 0.5, 3); 
			break;
			case 'EdgeDetect':
				phpthumb_filters::EdgeDetect($image);
			break;
			case 'Emboss':
				phpthumb_filters::Emboss($image);
			break;
			case 'MeanRemoval':
				phpthumb_filters::MeanRemoval($image);
			break;
			case 'Negative':
				phpthumb_filters::Negative($image);
			break;
			case 'HistogramStretch':
				phpthumb_filters::HistogramStretch($image);
			break;
			case 'HistogramStretch2':
				phpthumb_filters::HistogramStretch($image, 1);
			break;
			case 'BrightnessMore':
				phpthumb_filters::Brightness($image, 20);
			break;
			case 'BrightnessLess':
				phpthumb_filters::Brightness($image, -20);
			break;
			case 'ContrastMore':
				phpthumb_filters::Contrast($image, 20);
			break;
			case 'ContrastLess':
				phpthumb_filters::Contrast($image, -20);
			break;
			case 'GammaMore':
				phpthumb_filters::Contrast($image, 20);
			break;
			case 'GammaLess':
				phpthumb_filters::Contrast($image, -20);
			break;
			case 'SaturationMore':
				phpthumb_filters::Saturation($image, 20);
			break;
			case 'SaturationLess':
				phpthumb_filters::Saturation($image, -20);
			break;
			case 'Sepia':
				phpthumb_filters::Sepia($image, 30);
			break;
			case 'Grayscale':
				phpthumb_filters::Grayscale($image);
			break;
			case 'Smooth':
				phpthumb_filters::Smooth($image, 5);
			break;
		}


		//echo 'ok';
//		$file = WS_ADMINISTERED_APPLICATION_FOLDER . "../public/2-10213-space-schuttle.jpg";
//		$file = WS_ADMINISTERED_APPLICATION_FOLDER . "../public/coming-home.jpg";

//		$image = imagecreatefromjpeg( $file );
//		phpthumb_filters::Blur($image, 10);
//		phpthumb_filters::Bevel($image, 10, '444444', '888888');
//		phpthumb_filters::Colorize($image, 100, 'FF0000');
//		phpthumb_filters::Desaturate($image, 10, '0000FF');
//		phpthumb_filters::DropShadow($image, 10, 10, '0000FF', 45, true);
//		BAD - phpthumb_filters::Elipse($image); 
//		phpthumb_filters::Flip($image, false, true);
//		phpthumb_filters::Frame($image, 10, 10, 'FF0000', '00FF00', '0000FF');
//		d(phpthumb_filters::HistogramAnalysis($image));
//		phpthumb_filters::HistogramOverlay($image);
//		phpthumb_filters::ImprovedImageRotate($image, 10, '00FF00');
//		phpthumb_filters::RoundedImageCorners($image, 10, 10);
//		phpthumb_filters::Smooth($image, 20);
//		phpthumb_filters::Threshold($image, 30);
//		phpthumb_filters::ReduceColorDepth($image, 100);
//		BAD - phpthumb_filters::WhiteBalance($image, '0000FF');

//    function WatermarkText(&$gdimg, $text, $size, $alignment, $hex_color='000000', $ttffont='', $opacity=100, $margin=5, $angle=0, $bg_color=false, $bg_opacity=0, $fillextend='') {

		$jpeg_quality = 90;

		switch ($ext) {
			case 'jpg':
			case 'jpeg':
				header('Content-type: image/jpeg');
				imagejpeg($image, $src, $jpeg_quality);
			break;
			case 'gif':
				header('Content-type: image/gif');
				imagegif($image, $src);
			break;
			case 'png':
				header('Content-type: image/png');
				imageSaveAlpha($image, true);
				imagepng($image, $src);
			break;
		}

	}

	function save_filter_results() {}

	function get_thumb() {
		list($w, $h) = explode('x', array_shift($this->params));
		$file = WS_ROOT . implode('/', $this->params);
		
		if (file_exists($file)) {
			$thumb_file = WS_APPLICATION_FOLDER . 'cache/cache/mt_' . md5($file) . '-' . $w . 'x' . $h . '.png';
			if ( (!file_exists($thumb_file)) || (filemtime($file) != filemtime($thumb_file)) ) {
				$thumb = PhpThumbFactory::create($file);
				$thumb->adaptiveResize($w,$h);
				$thumb->save($thumb_file, 'png');
				touch($thumb_file, filemtime($file));
				$thumb->show();
				die();
			}
      		header('Content-type:image/png');
			readfile($thumb_file);
//			$thumb = PhpThumbFactory::create($thumb_file);
//			$thumb->show();
		}
	}
	
}
