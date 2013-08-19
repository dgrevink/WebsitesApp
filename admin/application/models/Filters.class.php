<?php

class filters extends MyActiveRecord {

	function getResults() {
		$table = $this->find_parent('tabledefinitions');
		
		$this->condition = html_entity_decode( $this->condition ,ENT_COMPAT,'UTF-8');
		
		return MyActiveRecord::FindAll($table->name, $this->condition, 'email asc');
	}
	
	function getTable() {
		$table = $this->find_parent('tabledefinitions');
		return $table->name;
	}

};