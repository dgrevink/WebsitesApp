<?php

class Newsletter extends MyActiveRecord {

    function destroy() {
    	foreach($this->find_children('newsletterusers') as $field) {
    		$field->destroy();
    	}
    	return parent::destroy();
	}

};
