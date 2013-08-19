<?php 

// This class uses the new raintpl engine for the CMS since 20110305

class Template extends RainTPL {

	public $layouts = null;
	
	public $caching = false;

	function Template() {
		// Class Constructor. 
		// These automatically get set with each new instance. 

//		$this->RainTPL();


		
		raintpl::$tpl_dir = "application/views/layouts/"; 
		raintpl::$cache_dir	= WS_APPLICATION_FOLDER . "/cache/cache/"; 
//		raintpl::$base_url = null;
		
		$this->configure('base_url', '/' );

//		raintpl::$tpl_ext = 'tpl';

		$this->config = new WSConfig();
		$this->config->load();

//		  $this->caching = $this->config->get('caching', 'system');
//		  $this->debugging = SITE_DEBUG;
	}
	
	function getLayouts( $language = DEFAULT_LANGUAGE ) {
		$layouts = array();
		$dir = null;
		if (defined('WS_ADMIN')) {
			$dir = WS_ADMINISTERED_APPLICATION_FOLDER . '/views/layouts/';
		}
		else {
			$dir = WS_APPLICATION_FOLDER . '/views/layouts/';
		}
		$files = glob($dir . '*' . $language . '.html');
		foreach($files as $file) {
			$content = file_get_contents($file);
			$placeholders = array();
			for($i=1;$i<=20;$i++) {
				$pos = strpos($content, '{$placeholder_' . $i . '}');
				if ($pos) {
					$placeholders[$i] = 'placeholder_' . $i;
				}
			}
			$imagename = '/application/views/layouts/images/' . basename($file, '.html') . '.png';
			if (!file_exists($dir . '../../..' . $imagename)) {
				$imagename = '/admin/application/lib/images/empty-template.png';
			}
			$layouts[] = array(
				'id' => basename($file, '.html'),
				'imagename' => $imagename,
				'filename' => basename($file, '.html'),
				'placeholders' => $placeholders
			);
		}
		$this->layouts = $layouts;
		return $this->layouts;
   }
   
	function getLayout($language, $id) {
		if ($this->layouts == null) {
			$this->getLayouts($language);
		}
		foreach($this->layouts as $layout) {
			if ($layout['id'] == $id) {
				return $layout;
			}
		}
	}
	
	function is_cached() {
		return false;
	}
	
	function display($file, $state) {
		return $this->draw($file, $state);
	}

}
