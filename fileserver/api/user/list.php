<?php
require('../../lib/Util.php');


class listUsers extends app\JSONApp
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

        $users=auth\User::select($filter);

        return($users);
    }
}

$l = new listUsers();
$l->run();