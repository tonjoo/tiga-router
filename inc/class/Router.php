<?php

/**
 * The Router manages routes using the WordPress rewrite API.
 *
 * @author Carl Alexander <contact@carlalexander.ca>
 */
class Router
{
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
     * Constructor.
     *
     * @param string  $route_variable
     * @param Route[] $routes
     */
    public function __construct($route_variable = 'route_name', array $routes = array())
    {
        $this->routes = array();
        $this->route_variable = $route_variable;

        foreach ($routes as $name => $route) {
            $this->add_route($name, $route);
        }
    }

    /**
     * Add a route to the router. Overwrites a route if it shares the same name as an already registered one.
     *
     * @param string $name
     * @param Route  $route
     */
    public function add_route($name, Route $route)
    {
        $this->routes[$name] = $route;
    }

    /**
     * Compiles the router into WordPress rewrite rules.
     */
    public function compile()
    {
        add_rewrite_tag('%'.$this->route_variable.'%', '(.+)');

        foreach ($this->routes as $name => $route) {
            $this->add_rule($name, $route);
        }
    }

    /**
     * Flushes all WordPress routes.
     *
     * @uses flush_rewrite_rules()
     */
    public function flush()
    {
        flush_rewrite_rules();
    }

    /**
     * Tries to find a matching route using the given query variables. Returns the matching route
     * or a WP_Error.
     *
     * @param array $query_variables
     *
     * @return Route|WP_Error
     */
    public function match(array $query_variables)
    {
        if (empty($query_variables[$this->route_variable])) {
            return new WP_Error('missing_route_variable');
        }

        $route_name = $query_variables[$this->route_variable];

        if (!isset($this->routes[$route_name])) {
            return new WP_Error('route_not_found');
        }

        return $this->routes[$route_name];
    }

    /**
     * Adds a new WordPress rewrite rule for the given Route.
     *
     * @param string $name
     * @param Route  $route
     * @param string $position
     */
    private function add_rule($name, Route $route, $position = 'top')
    {
        add_rewrite_rule($this->generate_route_regex($route), 'index.php?'.$this->route_variable.'='.$name, $position);
    }

    /**
     * Generates the regex for the WordPress rewrite API for the given route.
     *
     * @param Route $route
     *
     * @return string
     */
    private function generate_route_regex(Route $route)
    {
        return '^'.ltrim(trim($route->get_path()), '/').'$';
    }
}