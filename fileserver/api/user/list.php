<?php
require('../../lib/Util.php');


class listUsers extends app\JSONApp
{
    function main()
    {
        $filter=array();
        if(isset($_REQUEST['id']))
            $filter['id']=$_REQUEST['id'];

        $users=auth\User::select($filter);

        $this->setResult('lst',$users);
    }
}

$l = new listUsers();
$l->run();