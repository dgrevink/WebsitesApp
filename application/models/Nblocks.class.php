<?php 

 class nblocks extends MyActiveRecord {
    		
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
	 * $nblocks = MyActiveRecord::Create('Nblocks');
	 * print $nblocks->id;	// NULL
	 * $nblocks->save();
	 * print $nblocks->id; // 1
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
	 * $nblocks = MyActiveRecord::Create('Nblocks');
	 * print $nblocks->id;	// NULL
	 * $nblocks->save();
	 * print $nblocks->id; // 1
	 * </code>
	 *
	 * NB: if the object has registered errors, save() will return false
	 * without attempting to save the object to the database
	 *
	 * @return	boolean	true on success false on fail
	 */
    function saveadvanced($mode) {
    	return parent::save();
    }

	/**
	 * Deletes the object from the database
	 * eg:
	 * <code>
	 * $nblocks = MyActiveRecord::FindById('Nblocks', 1);
	 * $nblocks->destroy();
	 * </code>
	 *
	 * @return	boolean	True on success, False on failure
	 */
	function destroy()
	{
		return parent::destroy();
	}
	
};