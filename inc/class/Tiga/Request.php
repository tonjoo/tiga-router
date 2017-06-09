<?php
namespace Tiga;

/**
 * Class to handle Request
 */

class Request {

	/**
	 * Array holding all request input
	 * @var array 
	 */
	private $input;

	/**
	 * Array holding all request files
	 * @var array 
	 */
	private $file;

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
		$this->file = false;
		$this->populateInput();
	}

	/**
	 * Populate $input variable
	 */
	private function populateInput() {
		global $wp_query;
		$get    = $_GET;
		$post   = $_POST;
		$file	= $_FILES;
		$this->input = array_merge($get,$post);
		foreach ($wp_query->query_vars as $key => $value) {
			if (strpos($key,TIGA_VAR_PREFIX) === 0) {
				$this->input[str_replace(TIGA_VAR_PREFIX, '', $key)] = $value;
			}
		}
		$this->file = $_FILES;
	}

	/**
	 * Get the variable from request using $key
	 * @param string 	$key 
	 * @param mixed 	$default 	
	 * @return mixed
	 */
	public function input($key, $default = false) {
		if (isset($this->input[$key])) {
			return $this->input[$key];
		}

		if($default)
			return $default;

		return false;
	}

	/**
	 * Get file from request using $key
	 * @param string 	$key 
	 * @param mixed 	$default 	
	 * @return mixed
	 */
	public function file($key) {
		if (isset($this->file[$key])) {
			return $this->file[$key];
		}

		return false;
	}

	/**
	 * Check if the request has $key
	 * @param string $key 
	 * @return boolean
	 */
	public function has($key) {
		$keys = explode('|', $key);
		foreach ($keys as $k) {
			if (!array_key_exists($k, $this->input) || empty($this->input[$k])) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Check if the request has file with $key
	 * @param string $key 
	 * @return boolean
	 */
	public function hasFile($key) {
		$keys = explode('|', $key);
		foreach ($keys as $k) {
			if (!array_key_exists($k, $this->file) || empty($this->file[$k])) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Get all variable from request
	 * @param bool 		$sanitize 	
	 * @return array
	 */
	public function all() {
		return $this->input;
	}

	/**
	 * Get all files from request
	 * @param bool 		$sanitize 	
	 * @return array
	 */
	public function allFiles() {
		return $this->file;
	}

}