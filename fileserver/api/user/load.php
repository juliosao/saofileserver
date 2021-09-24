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
        $user=isset($args['name']) ? User::get($args['name']) : Auth::$current;        

        return $user;
    }
}

$l = new loadUser();
$l->run();