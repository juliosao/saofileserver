<?php
require('../../../lib/Util.php');

use app\JSONApp;
use auth\User;

class listUsers extends JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main($args)
    {
        $filter=array();
        if(isset($args['id']))
            $filter['id']=$args['id'];

        $users=User::select($filter);
        return($users);
    }
}

$l = new listUsers();
$l->run();