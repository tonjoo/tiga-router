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
	 * Unredirect reoutes
	 * 
	 * @var array
	 */
	public static $unredirect_routes;

	/**
	 * Constructor
	 */
	public function __construct() {
		self::$routes = array();
		self::$unredirect_routes = array();
	}

	/**
	 * Register route
	 *
	 * @param string  $path             Route path.
	 * @param string  $method           Request method.
	 * @param mixed   $callback         Callback function.
	 * @param boolean $prevent_redirect Prevent canonical redirect.
	 */
	public static function add( $path, $method = 'get', $callback = false, $prevent_redirect = false ) {
		if ( ! isset( self::$routes[ $path ] ) ) {
			self::$routes[ $path ] = array();
		}
		self::$routes[ $path ][ $method ] = $callback;
		if ( $prevent_redirect ) {
			self::$unredirect_routes[] = $path;
		}
	}

	/**
	 * Register route with GET method
	 *
	 * @param  string  $path             Route path.
	 * @param  mixed   $callback         Callback function.
	 * @param  boolean $prevent_redirect Prevent canonical redirect.
	 * @return mixed
	 */
	public static function get( $path, $callback = false, $prevent_redirect = false ) {
		return self::add( $path, 'get', $callback, $prevent_redirect );
	}

	/**
	 * Register route with POST method
	 *
	 * @param  string  $path             Route path.
	 * @param  mixed   $callback         Callback function.
	 * @param  boolean $prevent_redirect Prevent canonical redirect.
	 * @return mixed
	 */
	public static function post( $path, $callback = false, $prevent_redirect = false ) {
		return self::add( $path, 'post', $callback, $prevent_redirect );
	}

	/**
	 * Register route with PUT method
	 *
	 * @param  string  $path             Route path.
	 * @param  mixed   $callback         Callback function.
	 * @param  boolean $prevent_redirect Prevent canonical redirect.
	 * @return mixed
	 */
	public static function put( $path, $callback = false, $prevent_redirect = false ) {
		return self::add( $path, 'put', $callback, $prevent_redirect );
	}

	/**
	 * Register route with DELETE method
	 *
	 * @param  string  $path             Route path.
	 * @param  mixed   $callback         Callback function.
	 * @param  boolean $prevent_redirect Prevent canonical redirect.
	 * @return mixed
	 */
	public static function delete( $path, $callback = false, $prevent_redirect = false ) {
		return self::add( $path, 'delete', $callback, $prevent_redirect );
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
	 * Get unredirect routes
	 *
	 * @return mixed
	 */
	public static function get_unredirect_routes() {
		return self::$unredirect_routes;
	}

}


