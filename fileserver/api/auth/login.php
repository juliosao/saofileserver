<?php

require_once('../../lib/Util.php');

use app\JSONApp;
use auth\Auth;
use auth\UnauthorizedException;

class myApp extends JSONApp
{
    public function __construct()
    {
        parent::__construct(0);
    }

    public function main($args) 
    {
		error_log("Comprobamos auth:".json_encode($args));
		if(!isset($args['usr']) || !isset($args['pwd']))
		{
			throw new InvalidRequestException();
        }

        $usr=Auth::checkPassw($args['usr'],$args['pwd']);

        if(!$usr)
        {
            throw new UnauthorizedException();
        }
        
        return true; 
    }
}

$app = new myApp();
$app->run();