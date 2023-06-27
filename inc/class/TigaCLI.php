<?php
/**
 * Tiga CLI class
 *
 * @package Tiga Router
 */

/**
 * TigaCLI Class
 */
class TigaCLI {

	protected $path;

	public function __construct() {
		$this->path = get_stylesheet_directory();
		WP_CLI::add_command( 'tiga init', array( $this, 'init' ) );
		WP_CLI::add_command( 'tiga list', array( $this, 'route_list' ) );
	}

	public function init() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once  ABSPATH . '/wp-admin/includes/file.php' ;
			WP_Filesystem();
		}

		echo "Generating files..." . PHP_EOL;

		wp_mkdir_p( $this->path . '/app' );
		copy_dir( TIGA_WORDPRESS_ROUTER_PATH . 'template', $this->path . '/app' );

		$functions = $wp_filesystem->get_contents( $this->path . '/functions.php' );
		$functions .= PHP_EOL . PHP_EOL . "require_once 'app/loader.php';" . PHP_EOL;
		$wp_filesystem->put_contents( $this->path . '/functions.php', $functions );

		echo "Files successfully generated." . PHP_EOL;
		echo "Visit " . site_url() . '/welcome/ to view your first route.' . PHP_EOL;
	}

	public function route_list() {
		$routes = [];
		$tiga_route = new TigaRoute();
		do_action( 'tiga_route' ); // get routes.
		$mask = "|%7s | %-40s | %-50s |\n";
		printf( $mask, 'Method', 'Path', 'Callback' );
		foreach ( $tiga_route->get_routes() as $path => $methods ) {
			foreach ( $methods as $method => $data ) {
				$callback_name = '';
				if ( is_array( $data['callback'] ) ) {
					if ( is_object( $data['callback'][0] ) ) {
						$callback_name = get_class( $data['callback'][0] ) . '::' . $data['callback'][1];
					} else {
						$callback_name = $data['callback'][0] . '::' . $data['callback'][1];
					}
				} else {
					$callback_name = $data['callback'];
				}
				printf( $mask, strtoupper( $method ), $path, $callback_name );
			}
		}
	}

}

if ( class_exists( 'WP_CLI' ) ) {
	new TigaCLI();
}