<?php
namespace Tiga;

class Sanitizer {

	function __construct() {

	}

	public function sanitize( $var ) {
		return sanitize_text_field( $var );
	}

	public function sanitizeAll( $vars ) {
		$sanitized_vars = array();
		foreach ( $vars as $key => $value ) {
			// @TODO: what if $value contains array?
			$sanitized_vars[ $key ] = sanitize_text_field( $value );
		}
		return $sanitized_vars;
	}

}
