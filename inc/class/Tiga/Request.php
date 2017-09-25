<?php
namespace Tiga;

/**
 * Class to handle Request
 */

class Request {

	/**
	 * Array holding all request input
	 *
	 * @var array
	 */
	private $input;

	/**
	 * Array holding all request files
	 *
	 * @var array
	 */
	private $file;

	/**
	 * Sanitizer
	 *
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
		$file   = $_FILES;
		$this->input = array_merge( $get,$post );
		foreach ( $wp_query->query_vars as $key => $value ) {
			if ( strpos( $key,TIGA_VAR_PREFIX ) === 0 ) {
				$this->input[ str_replace( TIGA_VAR_PREFIX, '', $key ) ] = $value;
			}
		}
		$this->file = $_FILES;
	}

	/**
	 * Get the variable from request using $key
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function input( $key, $default = false ) {
		if ( ! preg_match( '/(^\w+|(?<!^)\G)\[(.*?)\]/', $key, $matches ) ) {
			if ( isset( $this->input[ $key ] ) ) {
				return $this->input[ $key ];
			}
		} else {
			if ( $matches[0] == $key && isset( $matches[1] ) && isset( $matches[2] ) ) {
				if ( isset( $this->input[ $matches[1] ][ $matches[2] ] ) ) {
					return $this->input[ $matches[1] ][ $matches[2] ];
				}
			}
		}

		if ( $default ) {
			return $default;
		}

		return false;
	}

	/**
	 * Get file from request using $key
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function file( $key, $get = '' ) {
		if ( $this->hasFile( $key ) ) {
			if ( isset( $this->file[ $key ][ $get ] ) ) { // name, type, tmp_name, error, size
				return $this->file[ $key ][ $get ];
			} else {
				if ( $get == 'base64' ) {
					$data = file_get_contents( $this->file[ $key ]['tmp_name'] );
					return 'data:' . $this->file[ $key ]['type'] . ';base64,' . base64_encode( $data );
				}
			}
			return $this->file[ $key ];
		}
		return false;
	}

	/**
	 * Check if the request has $key
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function has( $key ) {
		$keys = explode( '|', $key );
		foreach ( $keys as $k ) {
			if ( ! preg_match( '/(^\w+|(?<!^)\G)\[(.*?)\]/', $k, $matches ) ) {
				if ( ! array_key_exists( $k, $this->input ) || empty( $this->input[ $k ] ) ) {
					return false;
				}
			} else {
				if ( $matches[0] == $k && isset( $matches[1] ) && isset( $matches[2] ) ) {
					if ( ! array_key_exists( $matches[1], $this->input ) || empty( $this->input[ $matches[1] ] ) ) {
						return false;
					} else {
						if ( ! is_array( $this->input[ $matches[1] ] ) || ! array_key_exists( $matches[2], $this->input[ $matches[1] ] ) || empty( $this->input[ $matches[1] ][ $matches[2] ] ) ) {
							return false;
						}
					}
				} else {
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Check if the request has file with $key
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function hasFile( $key ) {
		$keys = explode( '|', $key );
		foreach ( $keys as $k ) {
			if ( ! array_key_exists( $k, $this->file ) || empty( $this->file[ $k ] ) || $this->file[ $k ]['size'] == 0 ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Get all variable from request
	 *
	 * @param bool $sanitize
	 * @return array
	 */
	public function all() {
		return $this->input;
	}

	/**
	 * Get all files from request
	 *
	 * @param bool $sanitize
	 * @return array
	 */
	public function allFiles() {
		return $this->file;
	}

}
