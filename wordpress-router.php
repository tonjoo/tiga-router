<?php
/* 
Plugin Name: Tiga Router
Description: WordPress Routing.
Author: Tonjoo
Author URI: http://tonjoostudio.com/
Plugin URI: http://tonjoostudio.com
Version: 1.0
Text Domain: tiga-router
*/
define( 'WORDPRESS_ROUTER_PATH', plugin_dir_path( __FILE__ ) );

require WORDPRESS_ROUTER_PATH.'inc/class/Processor.php';
require WORDPRESS_ROUTER_PATH.'inc/class/Route.php';
require WORDPRESS_ROUTER_PATH.'inc/class/Router.php';
require WORDPRESS_ROUTER_PATH.'inc/class/TigaRoute.php';

add_action('after_setup_theme', function() {
	$tiga_route = new TigaRoute();
	do_action('tiga_routes');
	$router = new Router('tiga_route');
	$routes = array();
	$route_list = $tiga_route->get_routes();
	foreach ($route_list as $path => $data) {
		$routes[$path] = new Route($path, $data);
	}
	Processor::init($router, $routes);
});