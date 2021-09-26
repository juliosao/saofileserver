<?php

require('../../lib/Util.php');

use app\JSONApp;
use app\Bundle;

class Setup extends JSONApp
{
    function main($args)
    {
        $b = new Bundle();
        $b->bundle = 'player';
        $b->path = 'bundles/player';
        $b->enabled = true;
        $b->insert();
        return True;
    }
}


$setup=new Setup();
$setup->run();