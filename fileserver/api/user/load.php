<?php
require('../../lib/Util.php');


class loadUser extends app\JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main()
    {

        $filter=array();
        $filter['id']= getParam('id',\auth\Auth::get()->id);

        $users=auth\User::select($filter);

        if(count($users)==0)
        {
            $this->exitApp(false,'user not found');
        }

        unset($users[0]->auth);
        unset($users[0]->session);
        return($users[0]);
    }
}

$l = new loadUser();
$l->run();