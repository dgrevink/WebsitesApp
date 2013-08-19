<?php

class WSMetadata {

	private $table_md = null;

	function getTableId($table_name) {
		if (!$this->loadTableMD($table_name)) {
			return null;
		}
		else {
			return $this->table_md->id;
		}
		
	}
	
	function loadTableMD($table_name) {
		if (!$this->table_md) {
			$this->table_md = MyActiveRecord::FindFirst('tabledefinitions', "name = '" . $table_name . "'");
			if (!$this->table_md) {
				return null;
			}
			else {
				$this->table_md->fields = $this->table_md->find_children('tablefields');
			}
		}
	}
}

