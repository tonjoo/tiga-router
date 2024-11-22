<?php

if ( ! function_exists('tiga_set_template') ) {
	/**
	 * Init template view
	 *
	 * @param string $template Template location.
	 * @param mixed  $data     Passed data to template.
	 */
	function tiga_set_template( $template, $data ) {
		TigaTemplate::init( $template, $data );
	}
}

if ( ! function_exists('set_tiga_template') ) {
	/**
	 * An alias for tiga_set_template function
	 *
	 * @param string $template Template location.
	 * @param mixed  $data     Passed data to template.
	 */
	function set_tiga_template( $template, $data = array() ) {
		tiga_set_template( $template, $data );
	}
}

if ( ! function_exists( 'tiga_view' ) ) {
	/**
	 * Set tiga view
	 * 
	 * @param  string $filename View file name.
	 * @param  mixed  $data     Passed data to template.
	 */
	function tiga_view( $filename, $data = array() ) {
		$path = defined( 'TIGA_VIEWS_PATH' ) ? TIGA_VIEWS_PATH : '';
		TigaTemplate::init( $path . $filename . '.php', $data );
	}
}

if ( ! function_exists( 'is_tiga' ) ) {

	/**
	 * Conditionally check current page is a tiga page
	 * 
	 * @return boolean True if current page is tiga.
	 */
	function is_tiga() {
		global $current_route;
		return ! empty( $current_route );
	}
}

if ( ! function_exists( 'is_route' ) ) {
	/**
	 * Conditionally check current route.
	 * 
	 * @param  string  $route Registered route.
	 * @return boolean        Is current URL matched the route.
	 */
	function is_route( $route = '' ) {
		global $current_route;
		if ( ! empty( $current_route ) && $route === $current_route->get_path() ) {
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'tiga_is_route' ) ) {
	/**
	 * Alias to is_route
	 * 
	 * @param  string  $route Registered route.
	 * @return boolean        Is current URL matched the route.
	 */
	function tiga_is_route( $route ) {
		return is_route( $route );
	}
}

if ( ! function_exists( 'tiga_is_group' ) ) {
	/**
	 * Conditionally check current route group
	 * 
	 * @param  string  $path  Group path..
	 * @return boolean        Is current URL matched the route group.
	 */
	function tiga_is_group( $path ) {
		global $current_route;
		if ( ! empty( $current_route ) ) {
			$attributes = $current_route->get_attributes();
			return isset( $attributes['group'] ) && $path === $attributes['group'];
		}
		return false;
	}
}

if ( ! function_exists( 'tiga_get_translation_route' ) ) {
	/**
	 * Get translation route
	 * 
	 * @param  string $route   Original route.
	 * @param  string $lang   Desired language.
	 * @param  string $method Request method.
	 * @return string         Translated route.
	 */
	function tiga_get_translation_route( $route, $lang, $method = 'get' ) {
		global $tiga_routes;
		if ( isset( $tiga_routes[ $route ][ $method ]['attributes'] ) ) {
			$args = $tiga_routes[ $route ][ $method ]['attributes'];
			if ( isset( $args['translation_of'] ) ) {
				foreach ( $tiga_routes as $found_path => $methods ) {
					if ( isset( $methods[ $method ]['attributes']['translation_of'] ) && isset( $methods[ $method ]['attributes']['lang'] ) && $methods[ $method ]['attributes']['translation_of'] === $args['translation_of'] && $methods[ $method ]['attributes']['lang'] == $lang ) {
						return $found_path;
					}
				}
			}
		}
		return $route;
	}
}

if ( ! function_exists('tiga_set_seo') ) {
	/**
	 * SEO args
	 *
	 * @param array $args SEO args.
	 */
	function tiga_set_seo( $args ) {
		$title       = isset( $args['title'] ) && ! empty( $args['title'] ) ? $args['title'] : '';
		$description = isset( $args['description'] ) && ! empty( $args['description'] ) ? $args['description'] : '';
		$image       = isset( $args['image'] ) && ! empty( $args['image'] ) ? $args['image'] : '';
		$url         = isset( $args['url'] ) && ! empty( $args['url'] ) ? $args['url'] : '';
		$canonical   = isset( $args['canonical'] ) && ! empty( $args['canonical'] ) ? $args['canonical'] : '';
		$keywords    = isset( $args['keywords'] ) && ! empty( $args['keywords'] ) ? $args['keywords'] : '';
		$robots      = isset( $args['robots'] ) && ! empty( $args['robots'] ) ? $args['robots'] : '';

		// rankmath integration.
		if ( ! empty( $title ) ) {
			add_filter( 'rank_math/frontend/title', function() use ( $title ) {
				return $title;
			} );
		}
		if ( ! empty( $description ) ) {
			add_filter( 'rank_math/frontend/description', function() use ( $description ) {
				return $description;
			} );
		}
		if ( ! empty( $image ) ) {
			add_filter( 'rank_math/opengraph/facebook/image', function() use ( $image ) {
				return $image;
			} );
			add_filter( 'rank_math/opengraph/twitter/image', function() use ( $image ) {
				return $image;
			} );
		}
		if ( ! empty( $url ) ) {
			add_filter( 'rank_math/opengraph/url', function() use ( $url ) {
				return $url;
			} );
		}
		if ( ! empty( $canonical ) ) {
			add_filter( 'rank_math/frontend/canonical', function() use ( $canonical ) {
				return $canonical;
			} );
		}
		if ( ! empty( $keywords ) ) {
			add_filter( 'rank_math/frontend/keywords', function() use ( $keywords ) {
				return $keywords;
			} );
		}
		if ( ! empty( $robots ) ) {
			add_filter( 'rank_math/frontend/robots', function() use ( $robots ) {
				return $robots;
			} );
		}
	}
}

if ( ! function_exists('tiga_set_title') ) {
	/**
	 * Set page title
	 *
	 * @param string $title Page title.
	 */
	function tiga_set_title( $title ) {
		if ( ! empty( $title ) ) {
			add_filter( 'pre_get_document_title', function () use ( $title ) {
				return $title;
			}, 999999 );
		}
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

if ( ! function_exists( 'tiga_set_401' ) ) {
	/**
	 * Set page to 401
	 */
	function tiga_set_401( $message = "401 Unauthorized" ) {
		status_header( 401 );
		echo $message;
		exit();
	}
}

if ( ! function_exists( 'tiga_set_403' ) ) {
	/**
	 * Set page to 403
	 */
	function tiga_set_403( $message = "403 Forbidden" ) {
		status_header( 403 );
		echo $message;
		exit();
	}
}

if ( ! function_exists('tiga_dd') ) {
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
}
