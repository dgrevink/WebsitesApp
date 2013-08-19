<?php class nusers extends MyActiveRecord {


	function saveadvanced() {
		$record = MyActiveRecord::FindFirst('nusers', "email like '%" . $this->email . "%'");
		if ($record) {
			$this->id = $record->id;
			$this->active = 1;
		}
		return parent::save();
	}

};