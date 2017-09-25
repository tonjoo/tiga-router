<?php
/**
 * Plugin Name: Tiga Router - WP Router
 * Description: Enable routing in WordPress
 * Author: todiadiyatmo
 * Author URI: http://tonjoostudio.com/
 * Plugin URI: http://tonjoostudio.com
 * Version: 1.0
 * Text Domain: tiga-router
 */
define( 'TIGA_WORDPRESS_ROUTER_PATH', plugin_dir_path( __FILE__ ) );
define( 'TIGA_VAR_PREFIX', 'tj_' );

// Static Class
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/TigaRoute.php';

// Tiga Library
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Processor.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Route.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Router.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/TigaTemplate.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Tiga/Sanitizer.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Tiga/Request.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Tiga/Pagination.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Tiga/Session.php';

// let users change the session cookie name
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
		do_action( 'tiga_route' );
		$router = new Router( 'tiga_route' );
		$routes = array();
		$route_list = $tiga_route->get_routes();

		foreach ( $route_list as $path => $callback ) {
			$routes[ $path ] = new Route( $path, $callback );
		}

		Processor::init( $router, $routes );
	}
);

function set_tiga_template( $template, $data ) {
	tiga_set_template( $template, $data );
}

function tiga_set_template( $template, $data ) {
	TigaTemplate::init( $template, $data );
}

if ( ! function_exists( 'is_route' ) ) {
	function is_route( $route = '' ) {
		global $current_route;
		if ( $route == $current_route ) {
			return true;
		}
		return false;
	}
}

function tiga_dd( $object ) {
	echo '<pre>';
	var_dump( $object );
	echo '</pre>';
}
