<?php
require('../../../lib/Util.php');

use app\JSONApp;
use auth\Auth;
use auth\User;
use auth\UserNotFoundException;
use auth\CannotModifyYourselfException;
use database\DatabaseException;

class deleteUser extends JSONApp
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

        // Check for user to modify
        if(!isset($argv['name']))
        {
            throw new InvalidRequestException("Delete who?");
        }

        $users=User::select(['name'=>$argv['name']]);
        if(count($users)==0)
        {
            throw new UserNotFoundException($argv['name']);
        }
        $deleteUsr=$users[0];
                

        // Check for authorization to modify the user
        if($currentUsr->name == $deleteUsr->name )
        {
            throw new CannotModifyYourselfException("Cannot delete yourself!");
        }     

        if($deleteUsr->delete()!=1)
        {
            throw new DatabaseException("Cannot delete user!");
        }
    
        return true;
    }
}

$l = new deleteUser();
$l->run();