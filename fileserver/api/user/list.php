<?php
require('../../lib/Util.php');

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
        $filter=[];
        if(isset($args['name']))
            $filter['name']=$args['name'];

        $users=User::select($filter);
        return($users);
    }
}

$l = new listUsers();
$l->run();