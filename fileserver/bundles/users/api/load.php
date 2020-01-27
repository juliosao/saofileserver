<?php
require('../../../lib/Util.php');

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
        $id= isset($args['id']) ? $args['id'] : Auth::get()->id;

        $user=User::get(null,$id);        

        return $user;
    }
}

$l = new loadUser();
$l->run();