<?php

require_once('../../../lib/Util.php');
    
class myApp extends JSONApp
{
    public function __construct()
    {
        parent::__construct(0);
    }

    public function main() 
    {
		error_log("Comprobamos auth:".json_encode($_REQUEST));
		if(!isset($_REQUEST['usr']) || !isset($_REQUEST['pwd']))
		{
			throw new InvalidRequestException();
        }

        $usr=Auth::checkPassw($_REQUEST['usr'],$_REQUEST['pwd']);

        if(!$usr)
        {
            throw new UnauthorizedException();
        }
        
        return true; 
    }
}

$app = new myApp();
$app->run();