<?php

	class Users extends MyActiveRecord {


    		
	/**
	 * Gets called by the system if present
	 * Used in SELECT lists when the table is used by another
	 *
	 * @return	string the customized title
	 */
	function get_link_title() {
		return $this->title;
		$text = "<img src='http://www.gravatar.com/avatar/" . md5($this->email) . "?s=40&d=http%3A%2F%2F" . $_SERVER['HTTP_HOST'] . "%2Fadmin%2Fapplication%2Flib%2Fimages%2Fdefault-avatar.png' style='vertical-align: middle;'/>" . $this->title;
		return $text;
	}

	/**
	 * Saves the object back to the database
	 * eg: 
	 * <code>
	 * $ratings = MyActiveRecord::Create('Ratings');
	 * print $ratings->id;	// NULL
	 * $ratings->save();
	 * print $ratings->id; // 1
	 * </code>
	 *
	 * NB: if the object has registered errors, save() will return false
	 * without attempting to save the object to the database
	 *
	 * @return	boolean	true on success false on fail
	 */
	function save()
	{
		// Check if user is newly created, if it is newly created, set a membersince field
		$checkuser = MyActiveRecord::FindFirst('users', "username = '{$this->username}'");
		if (!$checkuser) {
			$this->membersince = date('Y-m-d H:i:s');
		}
		
		
		// Create a newsletter user if not present, if present, activate him
		$nuser = MyActiveRecord::FindFirst('nusers', "email = '{$this->username}'");
		if ($nuser) {
			$nuser->active = 1;
			$nuser->save();
		}
		else {
			$nuser = MyActiveRecord::Create('nusers');
			$nuser->language = $this->language;
			$nuser->title = $this->title;
			$nuser->email = $this->username;
			$nuser->active = 1;
			$nuser->ncategories_id = 3;
			$nuser->save();
		}
		return parent::save();
	}

	/**
	 * Saves the object back to the database with a mode parameter (create or edit)
	 * Gets called by the system if present
	 * Can be used to further customize save
	 * eg: 
	 * <code>
	 * $ratings = MyActiveRecord::Create('Ratings');
	 * print $ratings->id;	// NULL
	 * $ratings->save();
	 * print $ratings->id; // 1
	 * </code>
	 *
	 * NB: if the object has registered errors, save() will return false
	 * without attempting to save the object to the database
	 *
	 * @return	boolean	true on success false on fail
	 */
    function saveadvanced($mode) {
		// Check if user is newly created, if it is newly created, set a membersince field
		$checkuser = MyActiveRecord::FindFirst('users', "username = '{$this->username}'");
		if (!$checkuser) {
			$this->membersince = date('Y-m-d H:i:s');
		}
		
    	$this->titleseo = generate_titleseo(MyActiveRecord::Class2Table(get_class($this)), $this->id, $this->title);
    	return parent::save();
    }

	/**
	 * Deletes the object from the database
	 * eg:
	 * <code>
	 * $ratings = MyActiveRecord::FindById('Ratings', 1);
	 * $ratings->destroy();
	 * </code>
	 *
	 * @return	boolean	True on success, False on failure
	 */
	function destroy()
	{
		return parent::destroy();
	}
	

	// Init user for admin security
	function init() {
		$this->group = $this->find_parent('groups');
		$this->modules 	= unserialize($this->group->rights);
		$this->tables 	= unserialize($this->group->datarights);
		$this->init = true;
	}


	// Checks if user has access to given module
	function has_module( $level ) {
		return (isset($this->modules[$level]));
	}

	// Checks if user has access to given table. roles can be view or modify
	function has_table( $table, $role='view' ) {
		if ($role != 'view') $table = $table . '_' . $role;
		return (isset($this->tables[$table]));
	}

	// Checks if user is admin	
	function is_admin() {
		return ($this->group->id == 1);
	}


	
};
