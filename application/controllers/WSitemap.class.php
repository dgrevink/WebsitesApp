<?php

/**
 *	RNAME: Plan de site
 *	PATH: 
 *	NOTE: Affiche un plan de site dynamique.
 *	VERSION: 2.0
 *	ACTIVE: YES
 *
 */

class WSitemap extends WSController {
	function index( $params = null ) {
		// $params has infos on the current page:
		// $this->params['url'] is the current page url as a vector for example.
		// other informations:
		// path			= path that can be used uniquely for this widget to store or access data
		// language		= current page language
		// name			= current widget name
		// param		= current widget parameters given by CMS
		// auth			= auth object
		// infos		= current page infos
		// template		= current page template object ( can be used to affect directly the page )
		// url			= current page as a vector
		if (!$params) { die('404'); }
		$this->params = &$params;
		
		$menu = "<ul class='ws-sitemap'>" . $this->_get_menu_items() . '</ul>';
		
		return $menu;
	}

	function _get_menu_items( $sitemap = '', $parent = 0 ) {
		$records = MyActiveRecord::FindAll('contents', "language = '" . $this->params['language'] . "'", 'porder asc');
		$this->menurecords = array();
		foreach($records as $record) {
			$item = null;
			$item->id 			= $record->id;
			$item->contents_id 	= $record->contents_id;
			$item->title 		= $record->title;
			$item->titleshort	= $record->titleshort;
			$item->path			= $record->path;
			$item->hidden		= $record->hidden;
			$item->sitemap		= $record->sitemap;
			$this->menurecords[$record->id] =  $item;
		}
		$items = $this->_get_children($parent);
		
		foreach($items as $item) {
			if ($item->sitemap == 1) {
				$sitemap .= "<li><a href='" . $this->_get_path($item->id, $this->params['language']) ."'>" . $item->title . "</a>";
//				$sitemap .= "<li>" . $item->title . "";
				$children = $this->_get_children($item->id);
				if (count($children) > 0) {
					$sitemap .= "<ul>";
				}
				foreach($children as $child) {
					$sitemap .= "<li><a href='" . $this->_get_path($child->id, $this->params['language']) ."'>" . $child->title . "</a>";
					$sitemap .= '</li>';
				}
				if (count($children) > 0) {
					$sitemap .= "</ul>";
				}
				$sitemap .= '</li>';
			}
		}

		return $sitemap;
	}
	
	function _get_children( $id ) {
		$items = array();
		foreach($this->menurecords as $item) {
			if ( ($item->contents_id == $id) && ($item->hidden == 0) && ($item->sitemap == 1) ){
				$items[] = $item;
			}
		}
		return $items;
	}

	function _get_path($id, $language, $omit_last = false) {
		$out = false;
		$path = array();
		$direct = false;
		while ($out == false) {
			$out = ($this->menurecords[$id]->contents_id == 0);
			$path[]= $this->menurecords[$id]->path;

			$prefix = substr($this->menurecords[$id]->path, 0, 7);
			if ( ($prefix == 'http://') || ($prefix == 'https:/') || ($prefix == 'javascr') ) {
				return $this->menurecords[$id]->path;
			}
			

			$id = $this->menurecords[$id]->contents_id;
		}

		$path[] = $language;
		
		$path = array_reverse($path);
		
		if ($omit_last) {
			array_pop($path);
		}
		
		$path = implode('/', $path);
		
		return '/' . $path . '/';
	}

}
