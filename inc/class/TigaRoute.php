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
	 * Route attributes
	 * 
	 * @var array
	 */
	public static $attributes;

	/**
	 * Route group
	 * 
	 * @var string
	 */
	public static $group;

	/**
	 * Constructor
	 */
	public function __construct() {
		self::$routes     = array();
		self::$attributes = array();
		self::$group      = '';
	}

	/**
	 * Set group path
	 * 
	 * @param  string   $path     Group path.
	 * @param  callable $callback Group routes.yttt bhyyu 
	 * @return [type]           [description]
	 */
	public static function group( $path, $callback ) {
		self::$group = $path;
		call_user_func( $callback );
		self::$group = '';
	}

	/**
	 * Register route
	 *
	 * @param string $path     Route path.
	 * @param string $method   Request method.
	 * @param mixed  $callback Callback function.
	 * @param array  $attributes 	   Additional attributes.
	 */
	public static function add( $path, $method = 'get', $callback = false, $attributes = array() ) {

		if ( ! empty( self::$group ) ) {
			$attributes['group']  = self::$group;
			if ( ! isset( $attributes['prefix'] ) ) {
				$attributes['prefix'] = self::$group;
			}
		}

		if ( isset( $attributes['polylang'] ) && true === $attributes['polylang'] && defined('POLYLANG') ) {
			$default = pll_default_language();
			$langs   = pll_languages_list();
			$options = get_option( 'polylang', array() );
			
			foreach ( $langs as $lang ) {
				$prefix = '/' . $lang;
				if ( $lang === $default && true === boolval( $options['hide_default'] ) ) {
					$prefix = '';
				}
				if ( false === boolval( $options['rewrite'] ) ) {
					$prefix = '/language' . $prefix;
				}

				if ( ! isset( $attributes['prefix'] ) ) {
					$attributes['prefix'] = '';
				}

				unset( $attributes['polylang'] );
				$attributes['lang']           = $lang;
				$attributes['translation_of'] = $attributes['prefix'] . $path;
				$attributes['prefix']         = $prefix . $attributes['prefix'];

				self::add( $path, $method, $callback, $attributes );
			}
			return;
		}

		if ( isset( $attributes['prefix'] ) ) {
			$path = $attributes['prefix'] . $path;
			unset( $attributes['prefix'] );
		}

		if ( ! isset( self::$routes[ $path ] ) ) {
			self::$routes[ $path ] = array();
		}

		self::$routes[ $path ][ $method ] = array(
			'callback'   => $callback,
			'attributes' => $attributes
		);
		// self::$attributes[ $path ][ $method ] = $attributes;
	}

	/**
	 * Register route with GET method
	 *
	 * @param  string $path     Route path.
	 * @param  mixed  $callback Callback function.
	 * @param  array  $attributes     Additional attributes.
	 * @return mixed
	 */
	public static function get( $path, $callback = false, $attributes = array() ) {
		return self::add( $path, 'get', $callback, $attributes );
	}

	/**
	 * Register route with POST method
	 *
	 * @param  string $path     Route path.
	 * @param  mixed  $callback Callback function.
	 * @param  array  $attributes     Additional attributes.
	 * @return mixed
	 */
	public static function post( $path, $callback = false, $attributes = array() ) {
		return self::add( $path, 'post', $callback, $attributes );
	}

	/**
	 * Register route with PUT method
	 *
	 * @param  string $path     Route path.
	 * @param  mixed  $callback Callback function.
	 * @param  array  $attributes     Additional attributes.
	 * @return mixed
	 */
	public static function put( $path, $callback = false, $attributes = array() ) {
		return self::add( $path, 'put', $callback, $attributes );
	}

	/**
	 * Register route with DELETE method
	 *
	 * @param  string $path     Route path.
	 * @param  mixed  $callback Callback function.
	 * @param  array  $attributes     Additional attributes.
	 * @return mixed
	 */
	public static function delete( $path, $callback = false, $attributes = array() ) {
		return self::add( $path, 'delete', $callback, $attributes );
	}

	/**
	 * Get routes
	 *
	 * @return mixed
	 */
	public function get_routes() {
		return self::$routes;
	}

	public function get_route_list() {
		$routes = [];
		foreach ( self::$routes as $path => $methods ) {
			foreach ( $methods as $method => $callback ) {
				$callback_name = '';
				if ( is_array( $callback ) ) {
					if ( is_object( $callback[0] ) ) {
						$callback_name = get_class( $callback[0] ) . '::' . $callback[1];
					} else {
						$callback_name = $callback[0] . '::' . $callback[1];
					}
				} else {
					$callback_name = $callback;
				}
				$routes[ $path ][ $method ] = $callback_name;
			}
		}
		return $routes;
	}

	/**
	 * Get addional attributes
	 *
	 * @return mixed
	 */
	public static function get_attributes() {
		return self::$attributes;
	}

}


