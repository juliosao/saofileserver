<?php
require('../../../lib/Util.php');


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
        for($i=0; $i<count($users); $i++)
        {
            unset($users[$i]->auth);
            unset($users[$i]->session);
        }

        return($users);
    }
}

$l = new listUsers();
$l->run();