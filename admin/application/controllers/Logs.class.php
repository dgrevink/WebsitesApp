<?php

WSLoader::load_helper('file');

class Logs extends WSController {

	function site() {
		$content = @file_get_contents(dirname(__FILE__) . '/../../../application/logs/site.log');

		$this->_display_log('Logs Site', $content, 'truncate_site', false );
	}

	function database() {
		$content = @file_get_contents(dirname(__FILE__) . '/../../../application/logs/sql.log');

		$this->_display_log('Logs Database', $content, 'truncate_database', false );
	}

	function admin() {
		$content = @file_get_contents(dirname(__FILE__) . '/../../../application/logs/admin.log');

		$this->_display_log('Logs Admin', $content, 'truncate_admin' );
	}

	function phpapp() {
		$content = @file_get_contents(dirname(__FILE__) . '/../../../error_log');

		$this->_display_log('error_log php application', $content, 'truncate_phpapp', false );
	}

	function phpcms() {
		$content = @file_get_contents(dirname(__FILE__) . '/../../../admin/error_log');

		$this->_display_log('error_log php cms', $content, 'truncate_phpcms', false );
	}


	function truncate_phpapp() {
		truncate_file(dirname(__FILE__) . '/../../../error_log');
		$this->_display_truncate('Logs du Site', 'Fichier error_log php applicatif mis &agrave; z&eacute;ro.');
	}

	function truncate_phpcms() {
		truncate_file(dirname(__FILE__) . '/../../../admin/error_log');
		$this->_display_truncate('Logs du Site', 'Fichier error_log php cms mis &agrave; z&eacute;ro.');
	}

	function truncate_site() {
		truncate_file(dirname(__FILE__) . '/../../../application/logs/site.log');
		$this->_display_truncate('Logs du Site', 'Fichier log du site mis &agrave; z&eacute;ro.');
	}

	function truncate_database() {
		truncate_file(dirname(__FILE__) . '/../../../application/logs/sql.log');
		$this->_display_truncate('Logs de la Database', 'Fichier log de la base de donn&eacute;es mis &agrave; z&eacute;ro.');
	}

	function truncate_admin() {
		truncate_file(dirname(__FILE__) . '/../../../application/logs/admin.log');
		$this->_display_truncate('Logs Admin', 'Fichier log admin mis &agrave; z&eacute;ro.');
	}
	
	function _display_log($title, $content, $truncate_function, $parse = true) {
		echo "<html><head><title>$title</title><link rel='stylesheet' type='text/css' href='../../application/lib/css/logs.css'/></head><body>";
		echo "<pre>";
		echo ' ' . $title;
		if ($truncate_function) {
			echo "    (<a href='../$truncate_function/'>Effacer</a>)";
		}
		echo "\n";
		echo str_repeat('=', strlen($title)+2) . "\n\n";
		echo '</pre>';
		if ($parse) {
			echo $this->_parse_log($content);
		}
		else {
			echo "<pre>";
			print_r($content);
			echo '</pre>';
		}
		echo '&nbsp;';
		echo '</body></html>';
	}
	
	function _display_truncate($title, $message) {
		echo "<html><head><title>$title</title></head><body style='background-color: #111; padding: 10px;'>";
		echo "<pre style='color: #ccc'>";
		echo $message;
		echo '</pre>';
		echo '</body></html>';
	}
	
	function _parse_log($log) {
		$levels[WS_FATAL] 	= 'FATAL';
		$levels[WS_ERROR] 	= 'ERROR';
		$levels[WS_WARNING] = 'WARNING';
		$levels[WS_INFO] 	= 'INFO';

		$count = 1;
		$log = explode("\n", $log);
		foreach($log as $entry) {
			if (trim($entry) != '') {
				$entry_array = explode("\t", $entry);
				echo "<div class='line'><span class='count'>" 	. $count 			. ".</span>";
				echo "<span class='date'>" 		. $entry_array[0] 	. "</span>";
				echo "<span class='ip'>" 		. @$entry_array[1] 	. "</span>";
				echo "<span class='level'>" 	. @$levels[@$entry_array[2]] 	. "</span>";
				echo "<span class='user'>" 		. @$entry_array[3] 	. "</span>";
				echo "<span class='code'>" 		. @$entry_array[4] 	. "</span>";
				echo "<span class='message'>" 	. @$entry_array[5] 	. "</span>\n";
				echo "</div>";
				$count++;
			}
		}
	}

}


?>

