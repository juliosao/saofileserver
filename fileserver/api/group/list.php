<?php
require('../../lib/Util.php');

use app\JSONApp;
use auth\Group;

class listGroups extends JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main($args)
    {
        $groups=Group::select();
        return($groups);
    }
}

$l = new listGroups();
$l->run();