<?php
/**
 * TigaRoute class
 *
 * @package Tiga Router
 */

/**
 * TigaRoute class
 */
class TigaRoute {

	/**
	 * Routes array
	 *
	 * @var array
	 */
	public static $routes;

	/**
	 * Page titles
	 * 
	 * @var array
	 */
	public static $page_titles;

	/**
	 * Constructor
	 */
	public function __construct() {
		self::$routes = array();
		self::$page_titles = array();
	}

	/**
	 * Register route
	 *
	 * @param string $path     Route path.
	 * @param string $method   Request method.
	 * @param mixed  $callback Callback function.
	 * @param array  $args 	   Additional args.
	 */
	public static function add( $path, $method = 'get', $callback = false, $args = array() ) {
		if ( ! isset( self::$routes[ $path ] ) ) {
			self::$routes[ $path ] = array();
		}
		self::$routes[ $path ][ $method ] = $callback;
		if ( isset( $args['title'] ) ) {
			self::$page_titles[ $path ] = $args['title'];
		}
	}

	/**
	 * Register route with GET method
	 *
	 * @param  string $path     Route path.
	 * @param  mixed  $callback Callback function.
	 * @param  array  $args     Additional args.
	 * @return mixed
	 */
	public static function get( $path, $callback = false, $args = array() ) {
		return self::add( $path, 'get', $callback, $args );
	}

	/**
	 * Register route with POST method
	 *
	 * @param  string $path     Route path.
	 * @param  mixed  $callback Callback function.
	 * @param  array  $args     Additional args.
	 * @return mixed
	 */
	public static function post( $path, $callback = false, $args = array() ) {
		return self::add( $path, 'post', $callback, $args );
	}

	/**
	 * Register route with PUT method
	 *
	 * @param  string $path     Route path.
	 * @param  mixed  $callback Callback function.
	 * @param  array  $args     Additional args.
	 * @return mixed
	 */
	public static function put( $path, $callback = false, $args = array() ) {
		return self::add( $path, 'put', $callback, $args );
	}

	/**
	 * Register route with DELETE method
	 *
	 * @param  string $path     Route path.
	 * @param  mixed  $callback Callback function.
	 * @param  array  $args     Additional args.
	 * @return mixed
	 */
	public static function delete( $path, $callback = false, $args = array() ) {
		return self::add( $path, 'delete', $callback, $args );
	}

	/**
	 * Get routes
	 *
	 * @return mixed
	 */
	public function get_routes() {
		return self::$routes;
	}

	/**
	 * Get page titles
	 *
	 * @return mixed
	 */
	public static function get_page_titles() {
		return self::$page_titles;
	}

}


