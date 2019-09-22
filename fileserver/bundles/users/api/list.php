<?php
require('../../../lib/Util.php');


class listUsers extends JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main()
    {
        $filter=array();
        if(isset($_REQUEST['id']))
            $filter['id']=$_REQUEST['id'];

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