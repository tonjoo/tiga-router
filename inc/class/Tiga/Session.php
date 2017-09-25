<?php

namespace Tiga;

/**
 * Handle session.
 */
class Session {

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
	public function __construct() {
		$this->prefix = 'tiga_';
		/* if using $_SESSION as session bag */
		if ( defined( 'TIGA_SESSION' ) && TIGA_SESSION == '$_SESSION' ) {

			if ( ( version_compare( PHP_VERSION, '5.4.0', '>=' ) && session_status() == PHP_SESSION_NONE ) || ( session_id() == '' ) ) {
				session_start();
			}
			$this->session = $_SESSION;

		} else {

			if ( ! class_exists( 'WP_Session' ) ) {
				require_once TIGA_WORDPRESS_ROUTER_PATH . 'inc/lib/class-wp-session.php';
				require_once TIGA_WORDPRESS_ROUTER_PATH . 'inc/lib/wp-session.php';
			}
			if ( ! class_exists( 'WP_Session_Utils' ) ) {
				require_once TIGA_WORDPRESS_ROUTER_PATH . 'inc/lib/class-wp-session-utils.php';
			}
			$this->session = \wp_session::get_instance();

			add_action( 'do_delete_sessions', array( $this, 'delete_old_sessions' ) );
			$this->register_cron();

		}

		return $this;
	}

	/**
	 * Set session object to according to key.
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function set( $key, $value ) {
		$this->session[ $this->prefix . $key ] = $value;
	}


	/**
	 * Get object from session bag.
	 *
	 * @param string $key
	 * @param mixed  $defaultValue
	 *
	 * @return mixed
	 */
	public function get( $key, $defaultValue = false ) {
		if ( $this->has( $key ) ) {
			return $this->session[ $this->prefix . $key ];
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
	public function pull( $key, $defaultValue = false ) {
		if ( $this->has( $key ) ) {
			$return = $this->session[ $this->prefix . $key ];

			unset( $this->session[ $this->prefix . $key ] );

			return $return;
		}

		return $defaultValue;
	}

	/**
	 * Get all session bag content.
	 *
	 * @return array
	 */
	public function all() {

		$return = array();

		foreach ( $this->keys() as $key ) {
			$return[] = $this->session[ $key ];
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
	public function has( $key ) {
		return isset( $this->session[ $this->prefix . $key ] ) && ! is_null( $this->session[ $this->prefix . $key ] );
	}

	/**
	 * Return all list of defined keys.
	 */
	public function keys() {
		$return = array();

		foreach ( array_keys( $this->session ) as $key ) {
			if ( strpos( $key, $this->prefix ) !== false ) {
				$return[] = $key;
			}
		}

		return $return;
	}

	/**
	 * Clear the session bag.
	 *
	 * @return mixed
	 */
	public function clear() {

		foreach ( $this->keys() as $key ) {
			unset( $this->session[ $key ] );
		}

	}

	/**
	 * Register delete old sessions scheduler
	 *
	 * @return mixed
	 */
	private function register_cron() {
		if ( ! wp_next_scheduled( 'do_delete_sessions' ) ) {
			wp_schedule_event( time(), 'hourly', 'do_delete_sessions' );
		}
	}

	/**
	 * Delete all old sessions on database
	 *
	 * @return mixed
	 */
	public function delete_old_sessions() {
		WP_Session_Utils::delete_old_sessions();
	}
}
