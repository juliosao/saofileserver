<?php

require('../../lib/Util.php');


class createUser extends JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main()
    {
        Auth::checkSession();
        $currentUsr = Auth::get();   
        if(!$currentUsr->isFromGroup('admin'))
        {
            throw new InvalidRequestException("You are not an admin");
        }

		$name=getParam('name');
		$mail=getParam('mail');

        $users=User::select(array('name'=>$name));
        if(count($users)!=0)
        {
            throw new UserExistsException();
        }

        
        
	}
}