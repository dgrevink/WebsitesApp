<?php

/**

	$table = activerecord2smartyarray($table);
	$date = dbdate2human($date, $format);
	$current_titleseo = generate_titleseo($class_name, $id, $title);

*/


function activerecord2smartyarray($table) {
	$new_table = array();
	foreach ($table as $table_entry) {
		$current_table_record = array();
		$current_table_record_id = 0;
		foreach($table_entry as $key => $value) {
			if ($key == 'date') {
				$value = strtotime($value);
				$value = date('d / m / Y', $value);
			}
			$current_table_record[$key] = $value;
		}
		$new_table[] = $current_table_record;
	}
	
	return $new_table;
}

function dbdate2human($date, $format) {
	return date($format, strtotime($date));
}


// This function generates a unique title seo for the table.
function generate_titleseo($class_name, $id, $title) {
	$out = false;
	$counter = 1;
	
	$current_titleseo_base = normalize_string(html_entity_decode( $title ,ENT_COMPAT,'UTF-8'));
	$current_titleseo = $current_titleseo_base;
	do {
		$record = MyActiveRecord::FindFirst($class_name, "titleseo = '" . $current_titleseo . "'");
		if (!$record) { // Not found the current titleseo is OK !!!
			$out = true;
		}
		else {
			if ($record->id == $id) {
				$out = true;
			}
			else {
				$current_titleseo = $current_titleseo_base . '-' . $counter;
				$counter++;
			}
		}
	} while (!$out);

	return $current_titleseo;
}