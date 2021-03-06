<?php 

 class documents extends MyActiveRecord {
    		
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
	 * $documents = MyActiveRecord::Create('Documents');
	 * print $documents->id;	// NULL
	 * $documents->save();
	 * print $documents->id; // 1
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
	 * $documents = MyActiveRecord::Create('Documents');
	 * print $documents->id;	// NULL
	 * $documents->save();
	 * print $documents->id; // 1
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
	 * $documents = MyActiveRecord::FindById('Documents', 1);
	 * $documents->destroy();
	 * </code>
	 *
	 * @return	boolean	True on success, False on failure
	 */
	function destroy()
	{
		return parent::destroy();
	}
	
};