<?php
require('../../../lib/Util.php');

use app\JSONApp;
use auth\User;
use auth\UserNotFoundException;

class saveUser extends JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main($argv)
    {
        Auth::checkSession();
        $currentUsr = Auth::get();        

        // Check for user to modify
        $saveUsr=$currentUsr;
        if(isset($argv['id']))
        {
            $users=User::select(array('id'=>$argv['id']));
            if(count($users)==0)
            {
                throw new UserNotFoundException();
            }
            $saveUsr=$users[0];
        }        

        // Check for authorization to modify the user
        if($currentUsr->id == $saveUsr->id )
        {
            if(!User::checkPassw($saveUsr->name,$argv['cpw']))
            {
                throw new InvalidRequestException("Please type current password");
            }
        }
        else if(!$currentUsr->isFromGroup('admin'))
        {
            throw new InvalidRequestException("You are not an admin");
        }

        // Modify mail?
        if(isset($argv['mail']))
        {
            $usr->mail=$argv['mail'];        
        }        

        // Modify Passwords?
        $pw=$this->getParam('pw');
        $pw2=$this->getParam('pw2');
        if($pw !== '' || $pw2 !== '')
        {
            if($pw!==$pw2)
            {
                throw new InvalidRequestException("Passwords don't match");
            }
            error_log('Cambiando pw!');
            $saveUsr->setPw($pw);
        }

        $saveUsr->update();
    
        return $users[0];
    }
}

$l = new saveUser();
$l->run();