<?php

define( 'TIGA_VIEWS_PATH', 'app/views/' );

foreach ( scandir( dirname(__FILE__) . '/models/' ) as $filename ) {
	$path = dirname(__FILE__) . '/models/' . $filename;
	if ( is_file( $path ) ) {
		require_once $path;
	}
}
foreach ( scandir( dirname(__FILE__) . '/controllers/' ) as $filename ) {
	$path = dirname(__FILE__) . '/controllers/' . $filename;
	if ( is_file( $path ) ) {
		require_once $path;
	}
}
require_once 'routes.php';