<?php
/* 
Plugin Name: WordPress Router
Description: WordPress Routing.
Author: Mandrill
Author URI: http://tonjoostudio.com/
Plugin URI: http://tonjoostudio.com
Version: 1
Text Domain: wordpress-router
*/
define( 'WORDPRESS_ROUTER_PATH', plugin_dir_path( __FILE__ ) );

require WORDPRESS_ROUTER_PATH.'inc/class/Processor.php';
require WORDPRESS_ROUTER_PATH.'inc/class/Route.php';
require WORDPRESS_ROUTER_PATH.'inc/class/Router.php';

$router = new Router('my_plugin_route_name');
$routes = array(
    'my_plugin_index' => new Route('/my-plugin', 'my_test_action', 'my-plugin-index'),
    'my_plugin_redirect' => new Route('/my-plugin/redirect', 'my_plugin_redirect'),
);

add_action( 'my_test_action', 'my_test_action_do', 10, 3 );

function my_test_action_do() {
	echo "momo fatih";
	
}


Processor::init($router, $routes);

function my_plugin_redirect() {
    $location = '/';

    if (!empty($_GET['location'])) {
        $location = $_GET['location'];
    }

    wp_redirect($location);
}
add_action('my_plugin_redirect', 'my_plugin_redirect');