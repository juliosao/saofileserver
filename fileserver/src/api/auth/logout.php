<?php

require_once('../../lib/Util.php');

use app\JSONApp;
use auth\Auth;

class myApp extends JSONApp
{
    public function __construct()
    {
        parent::__construct(0);
    }

    public function main() 
    {
        Auth::logout();
        return true; 
    }
}

$app = new myApp();
$app->run();