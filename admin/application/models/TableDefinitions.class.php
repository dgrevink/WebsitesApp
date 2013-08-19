<?php

/**
 * This class does some fancy schmancy table manipulation !!
 * @package admin
 *
 */
class TableDefinitions extends MyActiveRecord {
    function destroy() {
    	foreach($this->find_children('tablefields') as $field) {
    		$field->destroy();
    	}

    	MyActiveRecord::Query("drop table `" . $this->name . "`");

		if ($this->system == 1) {
	    	unlink(WS_APPLICATION_FOLDER . '/models/' . ucwords($this->name) . '.class.php');
		}
		else {
	    	unlink(WS_ADMINISTERED_APPLICATION_FOLDER . '/models/' . ucwords($this->name) . '.class.php');
		}

    	return parent::destroy();
    }
    
    function save() {
    		return parent::save();
    }
    
    function saveadvanced($mode, $override=false) {
    	if ($mode != 'create') return parent::save();
    	
    	if (MyActiveRecord::TableExists($this->name) && !$override) {
    		d($this->name);
    		d($override);
    		echo 'STRANGE';
    		return false;
    	}
    	else {
	    		$sql = array();
				$sql[]= "CREATE TABLE `" . $this->name . "` (";
	    		$sql[] = "  `id` INT NOT NULL AUTO_INCREMENT,";
    			$sql[] = "  `language` varchar(4) DEFAULT '" . $this->language . "',";
    			$sql[] = "  `title` varchar(256) DEFAULT 'Titre vide.',";
    			$sql[] = "  `titleseo` varchar(256) DEFAULT 'Titre SEO vide.',";
    			$sql[] = "  PRIMARY KEY (`id`)";
    			$sql[] = ")";
    			$sql[] = "CHARACTER SET utf8;";
    		
    			$connection = MyActiveRecord::Connection();
    			mysql_query(implode("", $sql), $connection);
    		
    		$class_code = "<?php \n\n class " . $this->name . " extends MyActiveRecord {
    		
	/**
	 * Gets called by the system if present
	 * Used in SELECT lists when the table is used by another
	 *
	 * @return	string the customized title
	 */
	function get_link_title() {
		return \$this->title;
	}

	/**
	 * Saves the object back to the database
	 * eg: 
	 * <code>
	 * $" . $this->name . " = MyActiveRecord::Create('" . ucfirst($this->name) . "');
	 * print $" . $this->name . "->id;	// NULL
	 * $" . $this->name . "->save();
	 * print $" . $this->name . "->id; // 1
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
	 * $" . $this->name . " = MyActiveRecord::Create('" . ucfirst($this->name) . "');
	 * print $" . $this->name . "->id;	// NULL
	 * $" . $this->name . "->save();
	 * print $" . $this->name . "->id; // 1
	 * </code>
	 *
	 * NB: if the object has registered errors, save() will return false
	 * without attempting to save the object to the database
	 *
	 * @return	boolean	true on success false on fail
	 */
    function saveadvanced(\$mode) {
    	\$this->titleseo = generate_titleseo(MyActiveRecord::Class2Table(get_class(\$this)), \$this->id, \$this->title);
    	return parent::save();
    }

	/**
	 * Deletes the object from the database
	 * eg:
	 * <code>
	 * $" . $this->name . " = MyActiveRecord::FindById('" . ucfirst($this->name) . "', 1);
	 * $" . $this->name . "->destroy();
	 * </code>
	 *
	 * @return	boolean	True on success, False on failure
	 */
	function destroy()
	{
		return parent::destroy();
	}
	
};";

    		file_put_contents( WS_ADMINISTERED_APPLICATION_FOLDER . '/models/' . ucwords($this->name) . '.class.php', $class_code);
    		
    		$result = parent::save();

	    		$id = MyActiveRecord::Create('tablefields');
	    		$id->language = $this->language;
	    		$id->title = "id";
	    		$id->name = 'ID';
	    		$id->tabledefinitions_id = $this->id;
	    		$id->porder = 1;
	    		$id->showlist = 0;
	    		$id->listeditable = 0;
	    		$id->showedit = 0;
	    		$id->description = "Cl&eacute; de la table.";
	    		$id->type = 10;
	    		$id->save();
	
	    		$language = MyActiveRecord::Create('tablefields');
	    		$language->language = $this->language;
	    		$language->title = "language";
	    		$language->name = 'Langue';
	    		$language->tabledefinitions_id = $this->id;
	    		$language->porder = 2;
	    		$language->showlist = 0;
	    		$language->listeditable = 0;
	    		$language->showedit = 1;
	    		$language->description = "Langue.";
	    		$language->type = 29;
	    		$language->default = $this->language;
	    		$language->save();
	    		
	    		$title = MyActiveRecord::Create('tablefields');
	    		$title->language = $this->language;
	    		$title->title = "title";
	    		$title->name = 'Titre';
	    		$title->tabledefinitions_id = $this->id;
	    		$title->porder = 3;
	    		$title->showlist = 1;
	    		$title->listeditable = 0;
	    		$title->showedit = 1;
	    		$title->description = "Titre.";
	    		$title->type = 20;
	    		$title->default = 'Titre vide.';
	    		$title->save();

	    		$title = MyActiveRecord::Create('tablefields');
	    		$title->language = $this->language;
	    		$title->title = "titleseo";
	    		$title->name = 'Titre SEO';
	    		$title->tabledefinitions_id = $this->id;
	    		$title->porder = 4;
	    		$title->showlist = 0;
	    		$title->listeditable = 0;
	    		$title->showedit = 0;
	    		$title->description = "Titre SEO (Automatique).";
	    		$title->type = 20;
	    		$title->default = 'Titre SEO.';
	    		$title->save();
    		
    		return $result;

    	}
    }
};
