<?php

if ( ! function_exists('tiga_set_template') ) {
	/**
	 * Init template view
	 *
	 * @param string $template Template location.
	 * @param mixed  $data     Passed data to template.
	 */
	function tiga_set_template( $template, $data ) {
		TigaTemplate::init( $template, $data );
	}
}

if ( ! function_exists('set_tiga_template') ) {
	/**
	 * An alias for tiga_set_template function
	 *
	 * @param string $template Template location.
	 * @param mixed  $data     Passed data to template.
	 */
	function set_tiga_template( $template, $data = array() ) {
		tiga_set_template( $template, $data );
	}
}

if ( ! function_exists( 'tiga_view' ) ) {
	/**
	 * Set tiga view
	 * 
	 * @param  string $filename View file name.
	 * @param  mixed  $data     Passed data to template.
	 */
	function tiga_view( $filename, $data = array() ) {
		$path = defined( 'TIGA_VIEWS_PATH' ) ? TIGA_VIEWS_PATH : '';
		TigaTemplate::init( $path . $filename . '.php', $data );
	}
}

if ( ! function_exists( 'is_route' ) ) {
	/**
	 * Conditionally check current route.
	 * 
	 * @param  string  $route Registered route.
	 * @return boolean        Is current URL matched the route.
	 */
	function is_route( $route = '' ) {
		global $current_route;
		if ( $route === $current_route ) {
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'tiga_set_404' ) ) {
	/**
	 * Set page to 404
	 */
	function tiga_set_404() {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 );
		exit();
	}
}

if ( ! function_exists( 'tiga_set_401' ) ) {
	/**
	 * Set page to 401
	 */
	function tiga_set_401( $message = "401 Unauthorized" ) {
		status_header( 401 );
		echo $message;
		exit();
	}
}

if ( ! function_exists( 'tiga_set_403' ) ) {
	/**
	 * Set page to 403
	 */
	function tiga_set_403( $message = "403 Forbidden" ) {
		status_header( 403 );
		echo $message;
		exit();
	}
}

if ( ! function_exists('tiga_dd') ) {
	/**
	 * Debugger
	 *
	 * @param mixed $object Object to debug.
	 */
	function tiga_dd( $object ) {
		echo '<pre>';
		var_dump( $object );
		echo '</pre>';
	}
}
