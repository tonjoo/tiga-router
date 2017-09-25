<?php

class TigaRoute {

	public static $routes;

	function __construct() {
		self::$routes = array();
	}

	public static function add( $path, $method = 'get', $callback = false ) {
		if ( ! isset( self::$routes[ $path ] ) ) {
			self::$routes[ $path ] = array();
		}
			self::$routes[ $path ][ $method ] = $callback;
	}

	public static function get( $path, $callback = false ) {
		return self::add( $path, 'get', $callback );
	}

	public static function post( $path, $callback = false ) {
		return self::add( $path, 'post', $callback );
	}

	public static function put( $path, $callback = false ) {
		return self::add( $path, 'put', $callback );
	}

	public static function delete( $path, $callback = false ) {
		return self::add( $path, 'delete', $callback );
	}

	public function get_routes() {
		return self::$routes;
	}

}


