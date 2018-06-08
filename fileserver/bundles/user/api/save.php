<?php
require('../../lib/Util.php');


class saveUser extends app\JSONApp
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

        $usr=$users[0];
        if(isset($_REQUEST['mail']))
        {
            $usr->mail=$_REQUEST['mail'];        
        }
        $usr->save();

        $pw=getParam('pw');
        $pw2=getParam('pw2');

        if($pw!=$pw2)
        {
            $this->exitApp(false,'passwords dont match');
        }
        else
        {
            if($pw!==null)
            {
                error_log("Cambiamos pw:$pw");
                $res=$usr->savePw($pw);
                error_log("$res");
            }
        }

    
        $this->setResult('usr',$users[0]);
    }
}

$l = new saveUser();
$l->run();