<?php

/**
 * The Processor is in charge of the interaction between the routing system and
 * the rest of WordPress.
 *
 * @author Carl Alexander <contact@carlalexander.ca>
 */
class Processor {

	/**
	 * The matched route found by the router.
	 *
	 * @var Route
	 */
	private $matched_route;

	/**
	 * The router.
	 *
	 * @var Router
	 */
	private $router;

	/**
	 * The routes we want to register with WordPress.
	 *
	 * @var Route[]
	 */
	private $routes;

	/**
	 * Constructor.
	 *
	 * @param Router $router Router object.
	 * @param array  $routes Routes.
	 */
	public function __construct( Router $router, array $routes = array() ) {
		$this->router = $router;
		$this->routes = $routes;
	}

	/**
	 * Initialize processor with WordPress.
	 *
	 * @param Router $router Router object.
	 * @param array  $routes Routes.
	 */
	public static function init( Router $router, array $route_list = array() ) {
		global $tiga_routes;
		$tiga_routes = $route_list;
		$routes      = [];

		foreach ( $route_list as $path => $data ) {
			$routes[ $path ] = new Route( $path, $data );
		}

		$self = new self( $router, $routes );

		add_action( 'init', array( $self, 'register_routes' ) );
		add_action( 'parse_request', array( $self, 'match_request' ) );
		add_action( 'template_redirect', array( $self, 'call_route_hook' ) );
		add_filter( 'pre_get_document_title', array( $self, 'set_document_title' ), 999999 );
		add_filter( 'redirect_canonical', array( $self, 'prevent_redirect_canonical' ), 10, 2 );
		add_filter( 'pll_pre_translation_url', array( $self, 'polylang_translated_url' ), 10, 3 );
	}

	/**
	 * Checks to see if a route was found. If there's one, it calls the route hook.
	 */
	public function call_route_hook() {
		$method = strtolower( $_SERVER['REQUEST_METHOD'] );
		if ( ! $this->matched_route instanceof Route || ! $this->matched_route->has_hook( $method ) ) {
			return;
		}

		$callback = $this->matched_route->get_hook( $method );
		$path     = $this->matched_route->get_path();

		if ( is_callable( $callback ) ) {
			add_filter(
				'body_class', function( $classes ) use ( $path ) {
					array_push( $classes, 'tiga-router' );
					array_push( $classes, 'tiga-path-' . sanitize_title( $path ) );
					return $classes;
				}
			);

			if( is_array( $callback ) ) {
				$callbackClass = new $callback[0];
				call_user_func_array( [ $callbackClass , $callback[1] ] , array( new Tiga\Request() ) );
			}
			else {
				call_user_func( $callback, new Tiga\Request() );
			}

		}
	}


	/**
	 * Attempts to match the current request to a route.
	 *
	 * @param WP $environment WordPress environment object.
	 */
	public function match_request( WP $environment ) {
		$method = strtolower( $_SERVER['REQUEST_METHOD'] );
		$matched_route = $this->router->match( $environment->query_vars );

		if ( $matched_route instanceof Route ) {
			if ( in_array( $method, $matched_route->get_methods(), true ) ) {
				global $current_route;
				$current_route = $matched_route;
				$this->matched_route = $matched_route;
			} else {
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
				get_template_part( 404 );
				exit();
			}
		}

		if ( $matched_route instanceof \WP_Error && in_array( 'route_not_found', $matched_route->get_error_codes(), true ) ) {
			wp_die(
				esc_html( $matched_route ), 'Route Not Found', array(
					'response' => 404,
				)
			);
		}
	}

	/**
	 * Register all our routes into WordPress.
	 */
	public function register_routes() {

		$routes = apply_filters( 'my_plugin_routes', $this->routes );

		$route_list =  array();

		foreach ( $routes as $name => $route ) {
			$this->router->add_route( $name, $route );
			$route_list[] = $name;
		}

		$this->router->compile();

		// skip if admin
		if( is_admin() )
			return;

		$route_list_hash = md5( serialize( $route_list ) );

		if ( strval($route_list_hash) != strval( get_option( 'tiga_route_md5_hash' ) ) ) {
			flush_rewrite_rules();
			update_option( 'tiga_route_md5_hash', $route_list_hash, 'no' );
		}
	}

	/**
	 * Set document title
	 * 
	 * @param string $title Document title.
	 */
	public function set_document_title( $title ) {
		if ( empty( $this->matched_route ) ) {
			return $title;
		}
		$attributes = $this->matched_route->get_attributes();
		if ( isset( $attributes['title'] ) ) {
			$request = new Tiga\Request();
			$title   = $attributes['title'];

			// replace vars.
			preg_match_all( '/\{(.*?)\}/', $title, $matches );
			if ( isset( $matches[1] ) && ! empty( $matches[1] ) ) {
				foreach ( $matches[1] as $var ) {
					$replace = $request->has( $var ) ? $request->input( $var ) : '';
					$title   = str_replace( '{' . $var . '}', $replace, $title );
				}
			}
		}
		return apply_filters( 'tiga_page_title', $title, $this->matched_route->get_path(), $this->matched_route->get_attributes(), $request );
	}

	/**
	 * Prevent canonical redirection
	 * 
	 * @param  boolean $redirect      Redirect (default to true).
	 * @param  string  $requested_url Current URL/
	 * @return boolean                Redirect.
	 */
	public function prevent_redirect_canonical( $redirect, $requested_url ) {
		if ( apply_filters( 'tiga_prevent_canonical_redirect', true ) && ! empty( $this->matched_route ) ) {
			$redirect = false;
		}
		return $redirect;
	}

	/**
	 * Set URL translations on polylang if exists
	 * 
	 * @param  string       $url       Translation url.
	 * @param  PLL_Language $lang      Language object.
	 * @param  integer      $object_id Queried object id.
	 * @return string                  Translation url.
	 */
	public function polylang_translated_url( $url, $lang, $object_id ) {
		if ( ! $this->matched_route instanceof Route ) {
			return $url;
		}
		$current_path = $this->matched_route->get_path();
		$method       = strtolower( $_SERVER['REQUEST_METHOD'] );
		$path         = tiga_get_translation_route( $current_path, $lang->slug, $method );
		$request      = new Tiga\Request();

		if ( preg_match_all( '/{(\w+):(\w+)}/', $path, $matches ) ) {
			if ( isset( $matches[1] ) && is_array( $matches[1] ) ) {
				foreach ( $matches[1] as $k => $param ) {
					if ( $request->has( $param ) && isset( $matches[0][ $k ] ) ) {
						$path = str_replace( $matches[0][ $k ], $request->input( $param ), $path );
					}
				}
			}
		}

		return home_url( $path );
	}
}
