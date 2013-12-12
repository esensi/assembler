<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Configuration values for Alba\User\Token module
	|--------------------------------------------------------------------------
	|
	| The following lines contain the default configuration values for the
	| Alba\User\Token module. You can publish these to your project for
	| modification using the following Artisan command:
	|
	| php artisan config:publish emersonmedia/alba
	|
	*/

	/*
	|--------------------------------------------------------------------------
	| TTL configurations
	|--------------------------------------------------------------------------
	|
	| The following configuration options set the Time-to-Live (TTL) for token
	| expiration. Values should be specified in hours.
	|
	*/

	'ttl' => [

		'default' => 24, // Default TTL used by all tokens
		'activation' => 24,
		'password_reset' => 24,
	],

	/*
	|--------------------------------------------------------------------------
	| Routes to be included by this module
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter which routes are included,
	| effectively allowing the user to not use some or all of the default
	| routes available.
	|
	*/

	'routes' => [
		
		'api' 		=> true,
		'backend' 	=> true,
		'public'	=> true,
	],
	
	/*
	|--------------------------------------------------------------------------
	| Views to be used by this module
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter which package handles the
	| views, and which views are used specifically by each function.
	|
	*/

	'package' => 'alba::',

	'views' => [
		'index' 			=> 'tokens.index',
	],

];