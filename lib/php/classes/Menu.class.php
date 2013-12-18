<?php

define('WS_MENU_CONTEXT_ALL', 1);
define('WS_MENU_CONTEXT_CURRENT_ONLY', 2);
define('WS_MENU_CONTEXT_TOP_ONLY', 3);

class WSMenu extends WSLoader {

	public $menu = array();
	public $dbmenu = null;
	public $language = null;
	public $valid_paths = array();
	public $default_page_id = null;

	function WSMenu($language) {
		parent::WSLoader();
		$this->load =& $this;

		$this->load('config');
		$this->config->load();

		$this->language = $language;

		// Load menu
		$this->dbmenu = MyActiveRecord::FindAll('contents', "language = '" . $this->language . "'", 'contents_id asc, porder asc');

		// Get default page id
		$this->default_page_id = key($this->dbmenu);

		$this->_parse();
	}

	function _parse() {
		foreach($this->dbmenu as $entry) {
			$access = explode(',', str_replace(' ', '', $entry->access));
			$path = $this->get_path($entry->id);
			$redirect = false;
			for ($i = 1 ; $i<=9; $i++) {
				if ($entry->{'placeholder_' . $i} == 'widget-WRedirector.class.php') $redirect = true;
			}

//			d($entry);
			$this->menu[$entry->id] = array(
				'id'	=> $entry->id,
				'parent' => $entry->contents_id,
				'path' => $path,
				'class' => str_replace('/', '-', $path),
				'spath' => $entry->path,
				'label' => $entry->title,
				'slabel' => $entry->titleshort,
				'hidden' => $entry->hidden,
				'cached' => $entry->cached,
				'access' => $access,
				'sitemap' => $entry->sitemap,
				'menus' => $entry->menus,
				'language' => $this->language,
				'params' => $entry->params,
				'layout' => $entry->layout,
				'hasredirect' => $redirect
			);
			$this->valid_paths[$this->get_path($entry->id)] = $entry->id;
		}
		$this->valid_paths['/'] = $this->default_page_id;

	}

	function get_path($id) {
		if ($id == 0) return null;
		return $this->get_path($this->dbmenu[$id]->contents_id) . '/' . $this->dbmenu[$id]->path;
	}

	function get($id) {
		if ($id == 0) return null;
		return $this->menu[$id];
	}

	function get_id($page, $language) {
		if ( (!$page) || (!$language) ) {
			return null;
		}

		if ($page == '/') {
			$default_page = $this->get($this->default_page_id);
			$page = $default_page['path'];
		}
		if ($page[0] != '/') $page = '/' . $page;
		if (strlen($page) > 1) {
			while(substr($page,-1) == '/') {
				$page = substr($page, 0, strlen($page) -1 );
			}
		}

		return (isset($this->valid_paths[$page])?$this->valid_paths[$page]:null);
	}

	function get_layout_id($id) {
		return $this->dbmenu[$id]->layout;
	}




	function has_children($id, $menu_set_id) {
		foreach ($this->menu as $entry_id => $entry) {
			if ( ($entry['parent'] == $id) && ( strpos($entry['menus'], $menu_set_id) !== false ) ) {
				return true;
			}
		}
		return false;
	}

	// check if $id has a child with id $child_id
	function has_child($id, $child_id) {
		$has_child = false;

		$children = array();

		$child = $this->menu[$child_id];

		if ($child['parent'] == $id) return true;

		// make it recursive one day
		if ($this->menu[$child_id]['parent'] != 0) {
			$child = $this->menu[$this->menu[$child_id]['parent']];
			if ($child['parent'] == $id) return true;
			if ($this->menu[$child_id]['parent'] != 0) {
				$child = $this->menu[$this->menu[$child_id]['parent']];
				if ($child['parent'] == $id) return true;
				if ($this->menu[$child_id]['parent'] != 0) {
					$child = $this->menu[$this->menu[$child_id]['parent']];
					if ($child['parent'] == $id) return true;
				}
			}
		}

		return (in_array($id, $children));
	}

	function get_children($id) {
		$m = array();

		foreach($this->menu as $entry_id => $entry) {
			if ($entry['parent'] == $id) {
				$m[$entry_id] = $entry;
			}
		}

		return $m;
	}



	function get_seo($element, $id) {
		$seo = '';
		$index = 'seo' . $element;

		if (trim($this->dbmenu[$id]->$index) == '') {
			$id = $this->default_page_id;
		}
		return $this->dbmenu[$id]->$index;
	}


	function hit($id) {
		$menu = MyActiveRecord::FindById('contents', $id);
		if ($menu) {
			$menu->hits++;
			$menu->save();
		}
	}




	// get_flat_menu()
	// New improved function
	// gets all menu entries from the same level as $current_page_id
	function get_children_flat_menu( $current_page_id, $menu_set_id ) {
		$menu_set_id = (string) $menu_set_id;
		$changed = false;
		$menu = array();

		// If the current page has children
		if ($this->has_children($current_page_id, $menu_set_id)) {
				$top_entry = $this->menu[$current_page_id];
				$menu[] = array_merge($top_entry, array(
	//					'state' => (($current_page_id == $entry['id'])&&!$changed?'selected':''),
						'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $top_entry['language'] . $top_entry['path']
				));
			foreach ($this->menu as $entry_id => $entry) {
				if ($entry['hidden']) continue; // skip hidden pages
				if ($entry['parent'] != $current_page_id) continue; // skip if parent is not same as current page id

				$menu[] = array_merge($entry, array(
					'state' => (($current_page_id == $entry['id'])&&!$changed?'selected':''),
					'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $entry['language'] . $entry['path']
				));
			}
		}
		// If the current page has no children, show siblings, but do not show siblings if parent is 0
		else {
			// add parent to the menu
//			$menu[] = array_merge($entry, array(
//				'state' => (($current_page_id == $entry['id'])&&!$changed?'selected':''),
//				'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $entry['language'] . $entry['path']
//			));
			if ( $this->menu[$current_page_id]['parent'] != 0 ) {
				$top_entry = $this->menu[$this->menu[$current_page_id]['parent']];
				$menu[] = array_merge($top_entry, array(
	//					'state' => (($current_page_id == $entry['id'])&&!$changed?'selected':''),
						'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $top_entry['language'] . $top_entry['path']
				));
			}
			foreach ($this->menu as $entry_id => $entry) {
				if ($entry['hidden']) continue; // skip hidden pages
				if ($entry['parent'] == 0) continue; // skip when parent is root;
				if ($entry['parent'] != $this->menu[$current_page_id]['parent']) continue; // skip if not a sibling ( = same parent as current page id )

				$menu[] = array_merge($entry, array(
					'state' => (($current_page_id == $entry['id'])&&!$changed?'selected':''),
					'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $entry['language'] . $entry['path']
				));
			}
		}

		return $menu;
	}

	// get_menu()
	// New improved version
	function get_menu( $parent, $current_page_id, $menu_set_id ) {
		$menu_set_id = (string) $menu_set_id;
		$menu = array();
		foreach ($this->menu as $entry_id => $entry) {
			if ($entry['hidden']) continue;
			if ( $parent == $entry['parent'] ) {
				if ( ($menu_set_id == -1) || ( ($menu_set_id != -1) && ( strpos($entry['menus'], $menu_set_id) !== false ) ) ) {
					$path = ($entry['path'] == '/')?'':$entry['path'];
					if ($this->menu[$current_page_id]['path'] != '/') $current_page = $this->menu[$current_page_id]['path'];
					$selected = false;
					if ($entry_id == $current_page_id) $selected = true;
					else if ($this->has_child($entry_id, $current_page_id)) {
						$selected = true;
					}
					$access = implode(',',$entry['access']);
					$menuentry = array(
						'id' => $entry_id,
						'label' => $entry['label'],
						'slabel' => $entry['slabel'],
						'access' => $access,
						'class' => str_replace('/', '-', $path),
						'path' => $this->config->get(DEPLOYMENT, 'config', 'html_root') . '/' . $this->menu[$current_page_id]['language'] . $path,
						'shortpath' => $entry['spath'],
//						'state' => (substr($current_page, 0, strlen($path) ) == $path)?'selected':'',
						'state' => $selected?'selected':'',
						'hasredirect' => $entry['hasredirect']
					);
					if ($this->has_children($entry_id, $menu_set_id)) {
//			d('given id: ' . $menu_set_id . ' --- current menu ids: ' . $entry['menus']);
						$menuentry['children'] = $this->get_menu($entry_id, $current_page_id, $menu_set_id);
						//d($menuentry['children']);
					}
					else {
						$menuentry['children'] = array();
					}
					$menuentry['children_total'] = count($menuentry['children']);
					$menu[] = $menuentry;
				}
			}
		}
		return $menu;
	}

}
