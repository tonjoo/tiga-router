<?php
/* 
Plugin Name: Tiga Router - WP Router
Description: Enable routing in WorDPress
Author: Tonjoo
Author URI: http://tonjoostudio.com/
Plugin URI: http://tonjoostudio.com
Version: 1.0
Text Domain: tiga-router
*/
define( 'TIGA_WORDPRESS_ROUTER_PATH', plugin_dir_path( __FILE__ ) );

require TIGA_WORDPRESS_ROUTER_PATH.'inc/class/Processor.php';
require TIGA_WORDPRESS_ROUTER_PATH.'inc/class/Route.php';
require TIGA_WORDPRESS_ROUTER_PATH.'inc/class/Router.php';
require TIGA_WORDPRESS_ROUTER_PATH.'inc/class/TigaRoute.php';
require TIGA_WORDPRESS_ROUTER_PATH.'inc/class/TigaTemplate.php';

add_action( 'after_setup_theme', function() {
	$tiga_route = new TigaRoute();
	do_action('tiga_route');
	$router = new Router('tiga_route');
	$routes = array();
	$route_list = $tiga_route->get_routes();

	foreach ($route_list as $path => $callback) {
		$routes[$path] = new Route($path, $callback);
	}

	Processor::init($router, $routes);
});

function set_tiga_template( $template, $data) {
	TigaTemplate::init( $template, $data);
}


