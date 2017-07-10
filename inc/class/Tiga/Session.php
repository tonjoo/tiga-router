<?php

namespace Tiga;

/**
 * Handle session.
 */
class Session
{
    /**
     * @var session
     */
    private $prefix;

    /**
     * @var session using $wp_session
     */
    private $session;

    /**
     * Class constructor.
     *
     * @param Session $session
     *
     * @return Session
     */
    public function __construct()
    {
        if ( ! class_exists( 'WP_Session' ) ) {
            require_once TIGA_WORDPRESS_ROUTER_PATH . 'inc/lib/class-wp-session.php';
            require_once TIGA_WORDPRESS_ROUTER_PATH . 'inc/lib/wp-session.php';
        }
        if ( ! class_exists( 'WP_Session_Utils' ) ) {
            require_once TIGA_WORDPRESS_ROUTER_PATH . 'inc/lib/class-wp-session-utils.php';
        }
        $this->prefix = 'tiga_';
        $this->session = \wp_session::get_instance();
        return $this;
    }

    /**
     * Set session object to according to key.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $this->session[$this->prefix.$key] = $value;
    }


    /**
     * Get object from session bag.
     *
     * @param string $key
     * @param mixed  $defaultValue
     *
     * @return mixed
     */
    public function get($key, $defaultValue = false)
    {
        if ($this->has($key)) {
            return $this->session[$this->prefix.$key];
        }

        return $defaultValue;
    }

    /**
     * Get object from session bag and delete
     *
     * @param string $key
     * @param mixed  $defaultValue
     *
     * @return mixed
     */
    public function pull($key, $defaultValue = false)
    {
        if ($this->has($key)) {
            $return = $this->session[$this->prefix.$key];

            unset($this->session[$this->prefix.$key]);

            return $return;
        }

        return $defaultValue;
    }

    /**
     * Get all session bag content.
     *
     * @return array
     */
    public function all()
    {

        $return = array();
        
        foreach( $this->keys() as $key ) {
            $return[] = $this->session[$key];
        };

        return $return;
        
    }

    /**
     * Check if session bag has $key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return !is_null($this->session[$this->prefix.$key]);
    }

    /**
     * Return all list of defined keys.
     */
    public function keys()
    {
        $return = array();

        foreach ( array_keys($this->session) as $key ) {
            if (strpos($key, $this->prefix) !== false) 
                $return[] = $key;
        }

        return $return;
    }

    /**
     * Clear the session bag.
     *
     * @return mixed
     */
    public function clear() {

        foreach( $this->keys() as $key ) {
            unset($this->session[$key]);
        }

    }
}
