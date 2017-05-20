<?php

/**
 * Class to handle Request
 */

class TigaRequest {

	/**
	 * Array holding all request input
	 * @var array 
	 */
	private $input;

	/**
	 * Sanitizer
	 * @var array 
	 */
	private $sanitizer;

	/**
	 * Constructor
	 */
	function __construct() {
		$this->input = false;
		$this->sanitizer = new Sanitizer();
		$this->populateInput();
	}

	/**
	 * Populate $input variable
	 */
	private function populateInput() {
		global $wp_query;
		$get    = $_GET;
		$post   = $_POST;
		$this->input = array_merge($get,$post);
		foreach ($wp_query->query_vars as $key => $value) {
			if (strpos($key,TIGA_VAR_PREFIX) === 0) {
				$this->input[str_replace(TIGA_VAR_PREFIX, '', $key)] = $value;
			}
		}
	}

	/**
	 * Get the variable from request using $key
	 * @param string 	$key 
	 * @param bool 		$sanitize 	
	 * @return mixed
	 */
	public function input($key, $sanitize = false) {
		if (isset($this->input[$key])) {
			if ($sanitize) {
				return $this->sanitizer->sanitize($this->input[$key]);
			}
			return $this->input[$key];
		}
		return false;
	}

	/**
	 * Check if the request has $key
	 * @param string $key 
	 * @return boolean
	 */
	public function has($key) {
		return array_key_exists($key, $this->input);
	}

	/**
	 * Get all variable from request
	 * @param bool 		$sanitize 	
	 * @return array
	 */
	public function all($sanitize = false) {
		if ($sanitize) {
			return $this->sanitizer->sanitizeAll($this->input);
		}
		return $this->input;
	}

}