<?php

class TableFields extends MyActiveRecord {
    function destroy() {
    	$result = parent::destroy();

    	$current_table = $this->find_parent('tabledefinitions');

    	$sql = "ALTER TABLE `" . $current_table->name . "` DROP COLUMN `" . $this->title . "`;";
    	$connection = MyActiveRecord::Connection();
    	mysql_query($sql, $connection);
    	return $result;
    }

    function saveadvanced($mode, $override=false) {

    	$current_table = $this->find_parent('tabledefinitions');
    	$this->language = $current_table->language;
    	$result = parent::save();
		
    	$sql_type = '';
    	switch ($this->type) {
    		case WST_INTEGER:
    		case WST_BOOLEAN:
    		case WST_FIELD_TYPE:
    		case WST_TABLE_LINK:
    			$sql_type = 'int';
    		break;

    		case WST_ORDER:
    			$sql_type = 'varchar(10)';
    		break;

    		case WST_STRING:
    		case WST_PASSWORD:
    		case WST_IMAGE:
    		case WST_FILE:
    		case WST_EMAIL:
    		case WST_TIME:
    		case WST_LANGUAGE:
    		case WST_PLACEHOLDER:
    			$sql_type = 'varchar(256)';
    		break;

    		case WST_TEXT:
    		case WST_HTML:
    		case WST_CODE:
    		case WST_TABLE_RIGHTS:
    		case WST_MODULES_RIGHTS:
    			$sql_type = 'longtext';
    		break;

    		case WST_FLOAT:
    			$sql_type = 'float';
    		break;

    		case WST_DATE:
    			$sql_type = 'date';
    		break;

    		default:
    			$sql_type = 'varchar(256)';
    		break;
    	}

		if ($this->title == 'id') {
			$sql_type = "INTEGER NOT NULL AUTO_INCREMENT";
		}
		
		$default = '';
		if ( (trim($this->default) != '') && ( $sql_type != 'longtext' ) ) {
			$default = ' DEFAULT "' . $this->default . '"';
		}

		$columns = array();
		if (!$override) {
	    	$columns = MyActiveRecord::Columns($current_table->name);
		}
    	if (!isset($columns[$this->title]) || $override) {
    		$sql = "ALTER TABLE `" . $current_table->name . "` ADD COLUMN `" . $this->title . "` " . $sql_type . $default . ";";
    		$connection = MyActiveRecord::Connection();
    		mysql_query($sql, $connection);
    	}
    	else {
    		$sql = "ALTER TABLE `" . $current_table->name . "` MODIFY COLUMN `" . $this->title . "` " . $sql_type . $default . ";";
    		$connection = MyActiveRecord::Connection();
    		mysql_query($sql, $connection);
    	}
    	return $result;
    }
    
    function get_link_title() {
    	return $this->name . ' (' . $this->title . ')';
    }
    

};
