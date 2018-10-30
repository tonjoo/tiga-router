<?php

/**
 * Utility class for sesion utilities
 *
 * THIS CLASS SHOULD NEVER BE INSTANTIATED
 */
class WP_Session_Utils {
	/**
	 * Count the total sessions in the database.
	 *
	 * @global wpdb $wpdb
	 *
	 * @return int
	 */
	public static function count_sessions() {
		global $wpdb;

		$query = "SELECT COUNT(*) FROM " . TIGA_SESSION_TABLE;

		/**
		 * Filter the query in case tables are non-standard.
		 *
		 * @param string $query Database count query
		 */
		$query = apply_filters( 'wp_session_count_query', $query );

		$sessions = $wpdb->get_var( $query );

		return absint( $sessions );
	}

	/**
	 * Create a new, random session in the database.
	 *
	 * @param null|string $date
	 */
	public static function create_dummy_session( $date = null ) {
		global $wpdb;

		// Generate our date
		if ( null !== $date ) {
			$time = strtotime( $date );

			if ( false === $time ) {
				$date = null;
			} else {
				$expires = date( 'U', strtotime( $date ) );
			}
		}

		// If null was passed, or if the string parsing failed, fall back on a default
		if ( null === $date ) {
			/**
			 * Filter the expiration of the session in the database
			 *
			 * @param int
			 */
			$expires = time() + (int) apply_filters( 'wp_session_expiration', 30 * 60 );
		}

		$session_id = self::generate_id();

		// Store the session
		$wpdb->insert(
			TIGA_SESSION_TABLE,
			array(
				'session_key' => $session_id,
				'session_value' => maybe_serialize( array() ),
				'session_expiry' => $expires
			),
			array(
				'%s',
				'%s',
				'%d'
			)
		);
	}

	/**
	 * Delete old sessions from the database.
	 *
	 * @param int $limit Maximum number of sessions to delete.
	 *
	 * @global wpdb $wpdb
	 *
	 * @return int Sessions deleted.
	 */
	public static function delete_old_sessions( $limit = 1000 ) {
		global $wpdb;

		$limit = absint( $limit );
		$sessions = $wpdb->get_results( "SELECT * FROM " . TIGA_SESSION_TABLE . " ORDER BY session_id ASC LIMIT 0, {$limit}" );

		$now = time();
		$expired = array();
		$count = 0;

		foreach ( $sessions as $session ) {
			$key = $session->session_key;
			$expires = $session->session_expiry;

			if ( $now > $expires ) {
				$expired[] = $key;
				$count += 1;
			}
		}

		// Delete expired sessions
		if ( ! empty( $expired ) ) {
			$placeholders = array_fill( 0, count( $expired ), '%s' );
			$format = implode( ', ', $placeholders );
			$query = "DELETE FROM " . TIGA_SESSION_TABLE . " WHERE session_key IN ($format)";

			$prepared = $wpdb->prepare( $query, $expired );
			$wpdb->query( $prepared );
		}

		return $count;
	}

	/**
	 * Remove all sessions from the database, regardless of expiration.
	 *
	 * @global wpdb $wpdb
	 *
	 * @return int Sessions deleted
	 */
	public static function delete_all_sessions() {
		global $wpdb;

		$count = $wpdb->query( "DELETE FROM " . TIGA_SESSION_TABLE );

		return (int) $count;
	}

	/**
	 * Generate a new, random session ID.
	 *
	 * @return string
	 */
	public static function generate_id() {
		require_once( ABSPATH . 'wp-includes/class-phpass.php' );
		$hash = new PasswordHash( 8, false );

		return md5( $hash->get_random_bytes( 32 ) );
	}
}
