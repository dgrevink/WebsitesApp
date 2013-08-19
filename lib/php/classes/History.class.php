<?php

// Provides history undo in database
class WSHistory {

	// saves data for table
	function store( $title, $table_name, $table_record_id, $data ) {
		$record = MyActiveRecord::Create('wsthistory');
		$record->language = $data->language;
		$record->title = $title;
		$record->table_name = $table_name;
		$record->table_record_id = $table_record_id;
		$record->ddate = date ("Y-m-d H:i:s");
		$record->data = '';
//		$record->data = gzcompress(serialize($data));
//		$record->data = base64_encode(gzcompress(base64_encode(serialize($data))));
//		$record->data = serialize($data);
		if (!$record->save()) {
			return false;
		}
		else {
			$query = 'update wsthistory set data = "' . mysql_real_escape_string(gzcompress(serialize($data))) . '" where id = ' . $record->id;
			if (!mysql_query($query)) {
				$record->destroy();
				return false;
			};
			return true;
		};
	}
	
	// returns null or the object stored at $id
	function load( $id ) {
		$record = MyActiveRecord::FindById('wsthistory', $id);


		if (!$record) return null;
		return unserialize(gzuncompress($record->data));
//		return unserialize($record->data);
	}
	
	function restore( $id ) {
		$history_record = MyActiveRecord::FindById('wsthistory', $id);
		
		if (!$history_record) return null;

		$record = MyActiveRecord::FindFirst($history_record->table_name, $history_record->table_record_id);
		
		if (!$record) return null;

//		$data = unserialize(base64_decode(gzuncompress(base64_decode($history_record->data))));
		$data = unserialize(gzuncompress($history_record->data));
		
		foreach($data as $key=>$val) {
			eval( '$record' . "->$key = $"."data->"."$key;");
		}

		d($record, true);
		return $record->save();
	}
	
	// Retrieve list of records stored for that table id
	function retrieve( $table_name, $table_record_id ) {
		return MyActiveRecord::FindAll('wsthistory', "table_name like '" . $table_name . "' and table_record_id = " . $table_record_id, 'ddate desc');
	}
	
	function drop( $table_name, $table_record_id ) {
		foreach (MyActiveRecord::FindAll('wsthistory', "table_name like '" . $table_name . "' and table_record_id = " . $table_record_id, 'ddate desc') as $item) {
			$item->destroy();
		}
	}
	
}

