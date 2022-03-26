<?php
require('../../lib/Util.php');

use app\JSONApp;
use auth\Auth;
use auth\User;

class loadUser extends JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main($args)
    {
        error_log("Nos piden usuario:".json_encode($args));
        $user=isset($args['user']) ? User::get(null,$args['user']) : Auth::$current;        

        return $user;
    }
}

$l = new loadUser();
$l->run();