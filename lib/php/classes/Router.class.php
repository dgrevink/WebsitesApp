<?php

class Router {

	var $controller			= null;
	var $method				= 'index';
	var $uri 				= null;
	var $segments     		= null;
	var $application_folder	= null;

	var $error = null;

	function Router() {
		global $application_folder;

		$this->config = new WSConfig();
		
		$this->config->load();
		
		$this->application_folder = $application_folder;
		

		$this->_set_route_mapping();
		
		$this->_segments_to_class();
		
	}
	
	function _set_route_mapping() {
		// Get and clean up uri
		$this->uri = $this->_get_uri_string();

		if (substr($this->uri, 0, 10) == '/index.php') {
			$this->uri = substr($this->uri, 10, strlen($this->uri));
		}
		if (substr($this->uri, 0, 16) == '/admin/index.php') {
			$this->uri = substr($this->uri, 16, strlen($this->uri));
		}

		if ($this->uri == '/') {
			$this->uri = '';
		}
		
		$this->uri = trim($this->uri, '/');
		
		$this->segments = explode('/', $this->uri );

		$this->_parse_routes();
		
		
		if (count($this->segments) == 1) {
			array_push($this->segments, $this->config->get('default_method', 'routes'));
		}

	}
	
	function _segments_to_class() {
		$requested_class  = ucfirst($this->segments[0]);
		
		$requested_method = $this->segments[1];

		
		if (!file_exists($this->application_folder . 'controllers/' . $requested_class . '.class.php')) {
			$this->error = '404';
		}
		else {
			$this->class = $requested_class;
			require_once($this->application_folder . 'controllers/' . $requested_class . '.class.php');
		}
		
		$this->controller = $requested_class;
		$this->method     = $requested_method;
	}

	function _get_uri_string()
	{
			// Mwahahaha someone is bound to get into this one:
			// If the URL has a question mark then it's simplest to just
			// build the URI string from the zero index of the $_GET array.
			// This avoids having to deal with $_SERVER variables, which
			// can be unreliable in some environments
			// Dont confuse this, since with url rewriting there is already a ?
//			if (is_array($_GET) AND count($_GET) == 1) {
				// Note: Due to a bug in current() that affects some versions
				// of PHP we can not pass function call directly into it
//				$keys = array_keys($_GET);
//				return current($keys);
//			}
		
			// Is there a PATH_INFO variable?
			// Note: some servers seem to have trouble with getenv() so we'll test it two ways		
			$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');			
			if ($path != '' AND $path != "/".WS_APPLICATION_FOLDER) {
				return $path;
			}
					
			// No PATH_INFO?... What about QUERY_STRING?
			$path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');	
			if ($path != '') {
				return $path;
			}
			
			// No QUERY_STRING?... Maybe the ORIG_PATH_INFO variable exists?
			$path = (isset($_SERVER['ORIG_PATH_INFO'])) ? $_SERVER['ORIG_PATH_INFO'] : @getenv('ORIG_PATH_INFO');	
			if ($path != '' AND $path != "/".WS_APPLICATION_FOLDER) {
				return $path;
			}

			// We've exhausted all our options...
			return '';
	}

	function _parse_routes()
	{
		// Turn the segment array into a URI string
		$uri = implode('/', $this->segments);
		$num = count($this->segments);
		
		// Is there a literal match?  If so we're done
		if ($this->config->get($uri, 'routes'))
		{
			if (strlen($uri) > 2) { // Patch only segments with no language
				$this->segments = array();
			}
			$this->_compile_segments(explode('/', trim($this->config->get($uri,'routes') , '/')));
			return;
		}

		// Loop through the route array looking for wild-cards

		foreach ($this->config->getarray('routes') as $key => $val)
		{
			// Convert wild-cards to RegEx
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));
			
			// Does the RegEx match?
			if (preg_match('#^'.$key.'$#', $uri))
			{
				// Do we have a back-reference?
				if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
				{
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}
				
				$this->_compile_segments(explode('/', trim($val, '/')));
				return;
			}
		}

		// If we got this far it means we didn't encounter a
		// matching route so we'll set the site default route
		if ($uri == '') {
			$this->segments = array( $this->config->get('default_controller', 'routes') );
		}
		$this->_compile_segments($this->segments);
	}

	function _compile_segments($segments = array())
	{
		if (count($segments) == 0)
		{
			return;
		}
		
		if (count($segments) >= 1) {
			if ( (isset($this->segments[0])) && ($this->segments[0] == $segments[0]) ) {
				return;
			}
		}

		$this->segments = array_merge( $segments, $this->segments );
		
//		d($this->segments);
		
	}


	function _validate_segments($segments) {

		// Shift down array - easier later on
		$this->segments = array();
		$i = 1;
		foreach($segments as $segment) {
			if (trim($segment) != '') {
				$this->segments[$i] = $segment;
				$i++;
			}
		}
	}
	
	
	function redirect($uri, $param) {
		$this->segments = explode('/', $uri );
		$this->segments[] = $param;
		$this->_segments_to_class();
	}

}

?>
