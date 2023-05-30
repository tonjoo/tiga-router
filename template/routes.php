<?php

use Controllers\WelcomeController;

add_action( 'tiga_route', function() {

	// register your routes here.
	TigaRoute::get( '/welcome', array( WelcomeController::instance(), 'index' ), [ 'title' => 'Welcome to Tiga Router' ] );

} );