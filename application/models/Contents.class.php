<?php

	class Contents extends MyActiveRecord {
		function destroy() {
			if ($this->find_children('contents')) {
				return false;
			}
			return parent::destroy();
		}
	};

?>