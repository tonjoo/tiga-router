<?php
/**
 * Plugin Name: Tiga Router - WP Router
 * Description: Enable routing in WordPress
 * Author: todiadiyatmo, gamaup
 * Author URI: http://tonjoostudio.com/
 * Plugin URI: http://tonjoostudio.com
 * Version: 2.0
 * Text Domain: tiga-router
 *
 * @package Tiga Router
 */

define( 'TIGA_WORDPRESS_ROUTER_PATH', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'TIGA_VAR_PREFIX' ) ) {
	define( 'TIGA_VAR_PREFIX', 'tj_' );
}

if ( ! defined( 'TIGA_SESSION_TABLE' ) ) {
	global $wpdb;
	define( 'TIGA_SESSION_TABLE', $wpdb->prefix . 'tiga_sessions' );
}

/* composer's vendor */
require TIGA_WORDPRESS_ROUTER_PATH . 'vendor/autoload.php';

/* tiga static class */
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/TigaRoute.php';

require TIGA_WORDPRESS_ROUTER_PATH . 'inc/tiga-helper.php';

/* Tiga Library */
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Processor.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Route.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Router.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/TigaTemplate.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Tiga/Sanitizer.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Tiga/Request.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Tiga/Pagination.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/Tiga/Session.php';
require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/TigaCLI.php';

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
		$routes = $tiga_route->get_routes();

		Processor::init( $router, $routes );
	}
);

register_activation_hook( __FILE__, 'tiga_install_db' );
function tiga_install_db() {
	global $wpdb;
	// create db table.
	$charset_collate = $wpdb->get_charset_collate();

	if ( $wpdb->get_var( "SHOW TABLES LIKE '" . TIGA_SESSION_TABLE . "'" ) != TIGA_SESSION_TABLE ) {

		$sql = 'CREATE TABLE ' . TIGA_SESSION_TABLE . "
			(
			  session_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			  session_key char(32) NOT NULL,
			  session_value longtext NOT NULL,
			  session_expiry BIGINT UNSIGNED NOT NULL,
			  PRIMARY KEY  (session_id),
			  UNIQUE KEY session_key (session_key)
			) $charset_collate;
			";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}