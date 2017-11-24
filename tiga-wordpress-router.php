<?php
/**
 * Plugin Name: Tiga Router - WP Router
 * Description: Enable routing in WordPress
 * Author: todiadiyatmo
 * Author URI: http://tonjoostudio.com/
 * Plugin URI: http://tonjoostudio.com
 * Version: 1.0
 * Text Domain: tiga-router
 *
 * @package Tiga Router
 */

define( 'TIGA_WORDPRESS_ROUTER_PATH', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'TIGA_VAR_PREFIX' ) ) {
	define( 'TIGA_VAR_PREFIX', 'tj_' );
}

/* composer's vendor */
require TIGA_WORDPRESS_ROUTER_PATH . 'vendor/autoload.php';

/* tiga static class */
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/TigaRoute.php';

/* Tiga Library */
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Processor.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Route.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Router.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/TigaTemplate.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Tiga/Sanitizer.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Tiga/Request.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Tiga/Pagination.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Tiga/Session.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/TigaPixie.php';

/* let users change the session cookie name */
if ( ! defined( 'WP_SESSION_COOKIE' ) ) {
	define( 'WP_SESSION_COOKIE', '_wp_session' );
}
if ( ! class_exists( 'Recursive_ArrayAccess' ) ) {
	require_once TIGA_WORDPRESS_ROUTER_PATH . 'inc/lib/class-recursive-arrayaccess.php';
}

add_action(
	'after_setup_theme',
	function () {
		$tiga_route = new TigaRoute();
		do_action( 'tiga_route' ); // get routes.
		$router = new Router( 'tiga_route' );
		$routes = array();
		$route_list = $tiga_route->get_routes();

		foreach ( $route_list as $path => $callback ) {
			$routes[ $path ] = new Route( $path, $callback );
		}

		Processor::init( $router, $routes );
	}
);

/**
 * An alias for tiga_set_template function
 *
 * @param string $template Template location.
 * @param mixed  $data     Passed data to template.
 */
function set_tiga_template( $template, $data ) {
	tiga_set_template( $template, $data );
}

/**
 * Init template view
 *
 * @param string $template Template location.
 * @param mixed  $data     Passed data to template.
 */
function tiga_set_template( $template, $data ) {
	TigaTemplate::init( $template, $data );
}

if ( ! function_exists( 'is_route' ) ) {
	/**
	 * Check if route
	 *
	 * @param string $route Registered route.
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
