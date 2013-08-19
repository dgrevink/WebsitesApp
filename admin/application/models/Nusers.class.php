<?php 

class nusers extends MyActiveRecord {
	function get_complete_title() {
		return $this->surname . ' ' . $this->title;
	}
};