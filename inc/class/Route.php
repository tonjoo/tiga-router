<?php

/**
 * A Route describes a route and its parameters.
 *
 * @author Carl Alexander <contact@carlalexander.ca>
 */
class Route {
	/**
	 * The hook called when this route is matched.
	 *
	 * @var string
	 */
	private $hook;

	/**
	 * The URL path that the route needs to match.
	 *
	 * @var string
	 */
	private $path;

	/**
	 * The URL path that the route needs to match.
	 *
	 * @var string
	 */
	private $methods;

	/**
	 * The template that the route wants to load.
	 *
	 * @var string
	 */
	private $template;

	/**
	 * Constructor.
	 *
	 * @param string $path
	 * @param string $hook
	 * @param string $template
	 */
	public function __construct($path, $data) {
		$this->path = $path;
		foreach ($data as $method => $d) {
			if (in_array($method, array('get','post','put','delete'))) {
				if (!empty($d[0])) { //callback
					$this->hook[$method] = $d[0];
				}
				if (!empty($d[1])) { //template
					$this->template[$method] = $d[1];
				}
				$this->methods[] = $method;
			}
		}
	}

	/**
	 * Get the hook called when this route is matched.
	 *
	 * @return string
	 */
	public function get_hook($method = 'get') {
		return isset($this->hook[$method]) ? $this->hook[$method] : false;
	}

	/**
	 * Get the URL path that the route needs to match.
	 *
	 * @return string
	 */
	public function get_path() {
		return $this->path;
	}

	/**
	 * Get the URL path that the route needs to match.
	 *
	 * @return string
	 */
	public function get_methods() {
		return $this->methods;
	}

	/**
	 * Get the template that the route wants to load.
	 *
	 * @return string
	 */
	public function get_template($method = 'get') {
		return isset($this->template[$method]) ? $this->template[$method] : false;
	}

	/**
	 * Checks if this route want to call a hook when matched.
	 *
	 * @return bool
	 */
	public function has_hook($method = 'get') {
		return !empty($this->hook[$method]);
	}

	/**
	 * Checks if this route want to load a template when matched.
	 *
	 * @return bool
	 */
	public function has_template($method = 'get') {
		return !empty($this->template[$method]);
	}
}