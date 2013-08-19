<?php class blocks extends MyActiveRecord {

function saveadvanced($mode) {
	$this->titleseo = generate_titleseo(MyActiveRecord::Class2Table(get_class($this)), $this->id, $this->title);
	return parent::save();
}

function destroy()
{
	if ($this->lck != 0) return false;
	return parent::destroy();
}


};