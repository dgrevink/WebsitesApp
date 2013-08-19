<?php

class Groups extends MyActiveRecord {
	function destroy() {
		if ($this->find_children('users')) {
			return false;
		}
		else {
			return parent::destroy();
		}
	}
};
