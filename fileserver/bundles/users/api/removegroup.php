<?php
require('../../../lib/Util.php');

use app\JSONApp;
use auth\User;
use auth\Group;
use auth\GroupNotFoundException;
use auth\UserNotFoundException;

class addGroup extends JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main($argv)
    {
        if(!isset($argv['id']) || !isset($argv['group']))
        {
            throw new InvalidRequestException();
        }

        // Search for user and group
        $user=User::get(null,$argv['id']);
        $group=Group::get(null,$argv['group']);

        $user->removeGroup($group);

        return $user->getGroups();
    }
}

$l = new addGroup();
$l->run();