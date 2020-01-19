<?php

require('../../../lib/Util.php');


class createUser extends JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main($argv)
    {
        Auth::checkSession();
        $currentUsr = Auth::get();   
        if(!$currentUsr->isFromGroup('admin'))
        {
            throw new InvalidRequestException("You are not an admin");
        }

		$name=$this->getParam('name');
		$mail=$this->getParam('mail');
        
        if($name=='' || $mail=='')
        {
            throw new InvalidRequestException('Name and mail cannot be empty');
        }
        
        $users=User::select(array('name'=>$name));
        if(count($users)!=0)
        {
            throw new UserExistsException();
        }

        error_log('Vamos a crear el usuario');
        $usr = new User();
        $usr->name=$name;
        $usr->mail=$mail;
        $usr->insert();
        return $usr;
        
	}
}

$l = new createUser();
$l->run();