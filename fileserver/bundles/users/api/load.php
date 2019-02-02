<?php
require('../../lib/Util.php');


class loadUser extends JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main()
    {
        error_log('PETICION:'.json_encode($_REQUEST));

        $filter=array();
        $filter['id']= getParam('id',Auth::get()->id);

        $users=User::select($filter);

        if(count($users)==0)
        {
            throw new UserNotFoundException($filter['id']);
        }

        unset($users[0]->auth);
        unset($users[0]->session);
        return($users[0]);
    }
}

$l = new loadUser();
$l->run();