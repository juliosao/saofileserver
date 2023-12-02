<?php
require('../../lib/Util.php');

use app\JSONApp;
use auth\Group;
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
        if(!isset($args['user']))
        {
            throw new InvalidRequestException();
        }

        $filter=[];
        $filter['user']=$args['user'];

        $users=User::select($filter);

        if(count($users)==0)
        {
           throw new UserNotFoundException($filter['user']);
        }

        return Group::fromUser($users[0]);
    }
}

$l = new loadUser();
$l->run();