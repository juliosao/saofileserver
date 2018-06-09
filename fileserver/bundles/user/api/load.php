<?php
require('../../../lib/Util.php');


class loadUser extends app\JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main()
    {
        if(!isset($_REQUEST['id']))
        {
            $this->exitApp(false,'id not provided');
        }

        $filter=array();
        $filter['id']=$_REQUEST['id'];

        $users=auth\User::select($filter);

        if(count($users)==0)
        {
            $this->exitApp(false,'user not found');
        }

        unset($users[0]->auth);
        unset($users[0]->session);
        $this->setResult('usr',$users[0]);
    }
}

$l = new loadUser();
$l->run();