<?php

	class ForumComments extends MyActiveRecord {

	function saveadvanced($mode=true) {
		$this->titleseo = generate_titleseo(MyActiveRecord::Class2Table(get_class($this)), $this->id, $this->title);
		return parent::save();
	}
	
	};
