<?php 

class Template extends Smarty { 

	public $layouts = null;

	static $path_replace = true;
	static $path_replace_list = array( 'a', 'img', 'link', 'script', 'input' );

	public $base_dir = '';

	public $config;

	function Template() {
		// Class Constructor. 
		// These automatically get set with each new instance. 

		parent::__construct();
//		$this->Smarty(); 

		if (!defined('WS_ADMIN')) {
			$this->base_dir = WS_HTML_LAYOUT_FOLDER;
			$this->registerFilter('pre',array($this,'path_replace'));
		}

		$this->setTemplateDir(WS_APPLICATION_FOLDER . "/views/");
		$this->setCompileDir(WS_ROOT . "lib/cache/templates_c/");
		$this->setCacheDir(WS_ROOT . "lib/cache/configs/");
		$this->setConfigDir(WS_ROOT . "lib/cache/cache/");

		// $this->template_dir = WS_APPLICATION_FOLDER . "/views/"; 
		// $this->compile_dir	= WS_APPLICATION_FOLDER . "/cache/templates_c/"; 
		// $this->config_dir	= WS_APPLICATION_FOLDER . "/cache/configs/"; 
		// $this->cache_dir	= WS_APPLICATION_FOLDER . "/cache/cache/"; 
		
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
				'filename' => basename($file),
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
	
	
	/**
	 * replace the path of image src, link href and a href.
	 * url => template_dir/url
	 * url# => url
	 * http://url => http://url
	 *
	 * @param string $html
	 * @return string html sostituito
	 */
	function path_replace( $html, &$smarty ){

		if( self::$path_replace ){

                        // reduce the path
                        $path = preg_replace('/\w+\/\.\.\//', '', $this->base_dir );

			$exp = $sub = array();

			if( in_array( "img", self::$path_replace_list ) ){
				$exp = array( '/<img(.*?)src=(?:")(http|https)\:\/\/([^"]+?)(?:")/i', '/<img(.*?)src=(?:")([^"]+?)#(?:")/i', '/<img(.*?)src="(.*?)"/', '/<img(.*?)src=(?:\@)([^"]+?)(?:\@)/i' );
				$sub = array( '<img$1src=@$2://$3@', '<img$1src=@$2@', '<img$1src="' . $this->base_dir . '$2"', '<img$1src="$2"' );
			}

			if( in_array( "script", self::$path_replace_list ) ){
				$exp = array_merge( $exp , array( '/<script(.*?)src=(?:")(http|https)\:\/\/([^"]+?)(?:")/i', '/<script(.*?)src=(?:")([^"]+?)#(?:")/i', '/<script(.*?)src="(.*?)"/', '/<script(.*?)src=(?:\@)([^"]+?)(?:\@)/i' ) );
				$sub = array_merge( $sub , array( '<script$1src=@$2://$3@', '<script$1src=@$2@', '<script$1src="' . $this->base_dir . '$2"', '<script$1src="$2"' ) );
			}

			if( in_array( "link", self::$path_replace_list ) ){
				$exp = array_merge( $exp , array( '/<link(.*?)href=(?:")(http|https)\:\/\/([^"]+?)(?:")/i', '/<link(.*?)href=(?:")([^"]+?)#(?:")/i', '/<link(.*?)href="(.*?)"/', '/<link(.*?)href=(?:\@)([^"]+?)(?:\@)/i' ) );
				$sub = array_merge( $sub , array( '<link$1href=@$2://$3@', '<link$1href=@$2@' , '<link$1href="' . $this->base_dir . '$2"', '<link$1href="$2"' ) );
			}

			if( in_array( "a", self::$path_replace_list ) ){
				$exp = array_merge( $exp , array( '/<a(.*?)href=(?:")(http|https)\:\/\/([^"]+?)(?:")/i', '/<a(.*?)href="(.*?)"/', '/<a(.*?)href=(?:\@)([^"]+?)(?:\@)/i'  ) );
				$sub = array_merge( $sub , array( '<a$1href=@$2://$3@', '<a$1href="' . /*self::$base_url .*/ '$2"', '<a$1href="$2"' ) );
			}

			if( in_array( "input", self::$path_replace_list ) ){
				$exp = array_merge( $exp , array( '/<input(.*?)src=(?:")(http|https)\:\/\/([^"]+?)(?:")/i', '/<input(.*?)src=(?:")([^"]+?)#(?:")/i', '/<input(.*?)src="(.*?)"/', '/<input(.*?)src=(?:\@)([^"]+?)(?:\@)/i' ) );
				$sub = array_merge( $sub , array( '<input$1src=@$2://$3@', '<input$1src=@$2@', '<input$1src="' . $this->base_dir . '$2"', '<input$1src="$2"' ) );
			}

			return preg_replace( $exp, $sub, $html );

		}
		else
			return $html;

	}

} 
