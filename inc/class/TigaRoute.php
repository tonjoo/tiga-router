<?php 

class TigaRoute {

	public static $routes;

	function __construct() {
		self::$routes = array();
	}

	public static function add($path, $method = 'get', $callback = false, $template = false) {
		if (!isset(self::$routes[$path])) self::$routes[$path] = array();
		self::$routes[$path][$method] = array($callback, $template);
	}

	public static function get($path,$callback,$template = false) {
		return self::add($path, 'get', $callback, $template);
	}

	public static function post($path,$callback,$template = false) {
		return self::add($path, 'post', $callback, $template);
	}

	public static function put($path,$callback,$template = false) {
		return self::add($path, 'put', $callback, $template);
	}

	public static function delete($path,$callback,$template = false) {
		return self::add($path, 'delete', $callback, $template);
	}

	public function get_routes() {
		return self::$routes;
	}

}

?>