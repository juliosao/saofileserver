<?php
require('../../../lib/Util.php');

use app\JSONApp;
use auth\User;
use auth\UserNotFoundException;

class loadUser extends JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main($args)
    {
        error_log('PETICION:'.json_encode($_REQUEST));

        $filter=array();
        $filter['id']= isset($args['id']) ? $args['id'] : Auth::get()->id;

        $users=User::select($filter);

        if(count($users)==0)
        {
            throw new UserNotFoundException($filter['id']);
        }

        return($users[0]);
    }
}

$l = new loadUser();
$l->run();