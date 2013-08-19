<?php 

 class photoalbums extends MyActiveRecord {
    		
	/**
	 * Gets called by the system if present
	 * Used in SELECT lists when the table is used by another
	 *
	 * @return	string the customized title
	 */
	function get_link_title() {
		return $this->title;
	}

	/**
	 * Saves the object back to the database
	 * eg: 
	 * <code>
	 * $photoalbums = MyActiveRecord::Create('Photoalbums');
	 * print $photoalbums->id;	// NULL
	 * $photoalbums->save();
	 * print $photoalbums->id; // 1
	 * </code>
	 *
	 * NB: if the object has registered errors, save() will return false
	 * without attempting to save the object to the database
	 *
	 * @return	boolean	true on success false on fail
	 */
	function save()
	{
		return parent::save();
	}

	/**
	 * Saves the object back to the database with a mode parameter (create or edit)
	 * Gets called by the system if present
	 * Can be used to further customize save
	 * eg: 
	 * <code>
	 * $photoalbums = MyActiveRecord::Create('Photoalbums');
	 * print $photoalbums->id;	// NULL
	 * $photoalbums->save();
	 * print $photoalbums->id; // 1
	 * </code>
	 *
	 * NB: if the object has registered errors, save() will return false
	 * without attempting to save the object to the database
	 *
	 * @return	boolean	true on success false on fail
	 */
    function saveadvanced($mode) {
    	$this->titleseo = generate_titleseo(MyActiveRecord::Class2Table(get_class($this)), $this->id, $this->title);
    	return parent::save();
    }

	/**
	 * Deletes the object from the database
	 * eg:
	 * <code>
	 * $photoalbums = MyActiveRecord::FindById('Photoalbums', 1);
	 * $photoalbums->destroy();
	 * </code>
	 *
	 * @return	boolean	True on success, False on failure
	 */
	function destroy()
	{
		return parent::destroy();
	}
	
};