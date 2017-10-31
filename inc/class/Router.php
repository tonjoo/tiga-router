<?php

require TIGA_WORDPRESS_ROUTER_PATH . 'inc/class/RouteParser.php';

/**
 * The Router manages routes using the WordPress rewrite API.
 *
 * @author Carl Alexander <contact@carlalexander.ca>
 */
class Router {

	/**
	 * All registered routes.
	 *
	 * @var Route[]
	 */
	private $routes;

	/**
	 * Query variable used to identify routes.
	 *
	 * @var string
	 */
	private $route_variable;

	/**
	 * Prefix for query var
	 *
	 * @var string
	 */
	private $var_prefix;

	/**
	 * Constructor.
	 *
	 * @param string $route_variable Route name on database.
	 * @param array  $routes Routes.
	 */
	public function __construct( $route_variable = 'route_name', array $routes = array() ) {
		$this->routes = array();
		$this->route_parser = new RouteParser();
		$this->route_variable = $route_variable;
		foreach ( $routes as $name => $route ) {
			$this->add_route( $name, $route );
		}
	}

	/**
	 * Add a route to the router. Overwrites a route if it shares the same name as an already registered one.
	 *
	 * @param string $name Route name.
	 * @param Route  $route Route object.
	 */
	public function add_route( $name, Route $route ) {
		$this->routes[ $name ] = $route;
	}

	/**
	 * Compiles the router into WordPress rewrite rules.
	 */
	public function compile() {
		add_rewrite_tag( '%' . $this->route_variable . '%', '(.+)' );

		foreach ( $this->routes as $name => $route ) {
			$this->add_rule( $name, $route );
		}
	}

	/**
	 * Flushes all WordPress routes.
	 *
	 * @uses flush_rewrite_rules()
	 */
	public function flush() {
		flush_rewrite_rules();
	}

	/**
	 * Tries to find a matching route using the given query variables. Returns the matching route
	 * or a WP_Error.
	 *
	 * @param array $query_variables Query variables.
	 *
	 * @return Route|WP_Error
	 */
	public function match( array $query_variables ) {
		if ( empty( $query_variables[ $this->route_variable ] ) ) {
			return new WP_Error( 'missing_route_variable' );
		}

		$route_name = $query_variables[ $this->route_variable ];

		if ( ! isset( $this->routes[ $route_name ] ) ) {
			return new WP_Error( 'route_not_found' );
		}

		return $this->routes[ $route_name ];
	}

	/**
	 * Convert shortcode of regex in route
	 *
	 * @param string $route Route.
	 * @return type
	 */
	public function convert_route_param( $route ) {

		$patterns = array(
			':any?' => ':[a-zA-Z0-9\.\-_%=]?+',
			':num?' => ':[0-9]?+',
			':all?' => ':.?*',
			':num' => ':[0-9]+',
			':any' => ':[a-zA-Z0-9\.\-_%=]+',
			':all' => ':.*',
		);

		$route = str_replace( array_keys( $patterns ), array_values( $patterns ), $route );

		return $route;
	}

	/**
	 * Adds a new WordPress rewrite rule for the given Route.
	 *
	 * @param string $name ROute name.
	 * @param Route  $route Route object.
	 * @param string $position Position.
	 */
	private function add_rule( $name, Route $route, $position = 'top' ) {
		$route_data = $this->route_parser->parse( trim( $this->convert_route_param( $route->get_path() ) ) );
		add_rewrite_rule( $this->generate_route_regex( $route_data ), 'index.php?' . $this->route_variable . '=' . $name . $this->extract_params( $route_data ), $position );
	}

	/**
	 * Generates the regex for the WordPress rewrite API for the given route.
	 *
	 * @param array $route_data Route data.
	 * @return string
	 */
	private function generate_route_regex( $route_data ) {
		$reg = '';
		foreach ( $route_data as $rd ) {
			if ( is_array( $rd ) && isset( $rd[1] ) ) {
				$reg .= '(' . $rd[1] . ')';
			} else {
				$reg .= $rd;
			}
		}
		$reg = '^' . trim( $reg, '/' ) . '/?';
		return $reg;
	}

	/**
	 * Extract params from route data.
	 *
	 * @param array $route_data Route data.
	 * @return string
	 */
	private function extract_params( $route_data ) {
		$x = 1;
		$params = $this->route_parser->params( $route_data );
		$url_params = '';
		foreach ( $params as $key => $value ) {
			add_rewrite_tag( '%' . TIGA_VAR_PREFIX . $key . '%' , $value );
			$url_params .= '&' . TIGA_VAR_PREFIX . $key . '=$matches[' . $x . ']';
			$x++;
		}
		return $url_params;
	}
}
