<?php
/**
 * TigaPixie class
 *
 */
Class TigaPixie {
	static function get($SingletonName = 'WP_PX') {
		self::pixie_connect_db($SingletonName);
	}

	/**
	 * Pixie connect DB
	 */
	private static function pixie_connect_db($SingletonName) {
		$config = array(
	        'driver'    => 'mysql', // Db driver
	        'host'      => DB_HOST,
	        'database'  => DB_NAME,
	        'username'  => DB_USER,
	        'password'  => DB_PASSWORD,
	        'charset'   => DB_CHARSET, // Optional
	        'collation' => DB_COLLATE, // Optional
	        'prefix'    => '', // Table prefix, optional
	    );

		new \Pixie\Connection('mysql', $config, $SingletonName);
	}
}