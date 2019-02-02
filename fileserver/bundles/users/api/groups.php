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
        if(!isset($_REQUEST['id']))
        {
            throw new InvalidRequestException();
        }

        $filter=array();
        $filter['id']=$_REQUEST['id'];

        $users=User::select($filter);

        if(count($users)==0)
        {
           throw new UserNotFoundException($filter['id']);
        }

        $this->setResult('grps',$user->groups());
    }
}

$l = new loadUser();
$l->run();