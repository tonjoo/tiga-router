<?php
/**
 * Sanitizer class
 *
 * @package Tiga Router
 */

namespace Tiga;

/**
 * Sanitizer Class
 */
class Sanitizer {

	/**
	 * Sanitize single text input
	 *
	 * @param mixed $var Input var.
	 */
	public function sanitize( $var ) {
		return sanitize_text_field( wp_unslash( $var ) );
	}

	/**
	 * Sanitize all text input
	 *
	 * @param mixed $vars Input vars.
	 */
	public function sanitizeAll( $vars ) {
		$sanitized_vars = array();
		if ( is_array( $vars ) ) {
			foreach ( $vars as $key => $value ) {
				if ( is_array( $value ) ) {
					$sanitized_vars[ $key ] = $this->sanitizeAll( $value );
				} else {
					$sanitized_vars[ $key ] = sanitize_text_field( wp_unslash( $value ) );
				}
			}
		} else {
			$sanitized_vars = sanitize_text_field( wp_unslash( $vars ) );
		}

		return $sanitized_vars;
	}

}
