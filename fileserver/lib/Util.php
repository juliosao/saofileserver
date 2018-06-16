<?php

function error_die($err=404,$msg='')
{
    include (__DIR__.'/../views/error'.$err.'.php');     
}

function getParam($param,$default=null)
{
    return isset($_REQUEST[$param]) ? $_REQUEST[$param] : $default;
}

// Class autoloader
spl_autoload_register(function ($class) {

    $parts = explode('\\', $class);
    if(! include(implode('/',$parts).'.php'))
    {
        throw new Exception('Class not found: '.$class);
    }
    
});

// Include path for clases, etc
set_include_path(
	get_include_path().PATH_SEPARATOR.
	__DIR__.PATH_SEPARATOR);

// Important for a lot of functions	
date_default_timezone_set('UTC');


