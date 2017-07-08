<?php

function error_die($err=404,$msg='')
{
    include (__DIR__.'/../views/error'.$err.'.php');     
}

// Class autoloader
spl_autoload_register(function ($class) {
	error_log("Cargando ".$class);
    require($class.'.php');
});

// Include path for clases, etc
set_include_path(
	get_include_path().PATH_SEPARATOR.
	__DIR__.PATH_SEPARATOR.
	__DIR__.'/exceptions'.PATH_SEPARATOR.
	'./lib'.PATH_SEPARATOR.
	'./lib/exceptions'.PATH_SEPARATOR.
	'../lib'.PATH_SEPARATOR.
	'../lib/exceptions'.PATH_SEPARATOR.
	'.');

// Important for a lot of functions	
date_default_timezone_set('UTC');


