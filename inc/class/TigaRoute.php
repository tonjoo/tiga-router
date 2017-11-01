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
	 * Constructor
	 */
	public function __construct() {
		self::$routes = array();
	}

	/**
	 * Register route
	 *
	 * @param string $path     Route path.
	 * @param string $method   Request method.
	 * @param mixed  $callback Callback function.
	 */
	public static function add( $path, $method = 'get', $callback = false ) {
		if ( ! isset( self::$routes[ $path ] ) ) {
			self::$routes[ $path ] = array();
		}
			self::$routes[ $path ][ $method ] = $callback;
	}

	/**
	 * Register route with GET method
	 *
	 * @param  string $path     Route path.
	 * @param  mixed  $callback Callback function.
	 * @return mixed
	 */
	public static function get( $path, $callback = false ) {
		return self::add( $path, 'get', $callback );
	}

	/**
	 * Register route with POST method
	 *
	 * @param  string $path     Route path.
	 * @param  mixed  $callback Callback function.
	 * @return mixed
	 */
	public static function post( $path, $callback = false ) {
		return self::add( $path, 'post', $callback );
	}

	/**
	 * Register route with PUT method
	 *
	 * @param  string $path     Route path.
	 * @param  mixed  $callback Callback function.
	 * @return mixed
	 */
	public static function put( $path, $callback = false ) {
		return self::add( $path, 'put', $callback );
	}

	/**
	 * Register route with DELETE method
	 *
	 * @param  string $path     Route path.
	 * @param  mixed  $callback Callback function.
	 * @return mixed
	 */
	public static function delete( $path, $callback = false ) {
		return self::add( $path, 'delete', $callback );
	}

	/**
	 * Get routes
	 *
	 * @return mixed
	 */
	public function get_routes() {
		return self::$routes;
	}

}


