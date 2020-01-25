<?php

require('../../../lib/Util.php');

use app\JSONApp;
use auth\AUth;
use auth\Group;
use auth\GroupExistsException;

class createGroup extends JSONApp
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
		
        if($name=='')
        {
            throw new InvalidRequestException('Group name cannot be empty');
        }
        
        $grps=Group::select(array('name'=>$name));
        if(count($grps)!=0)
        {
            throw new GroupExistsException($name);
        }

        error_log('Vamos a crear el usuario');
        $usr = new Group();
        $usr->name=$name;
        $usr->insert();
        return $usr;
        
	}
}

$l = new createUser();
$l->run();