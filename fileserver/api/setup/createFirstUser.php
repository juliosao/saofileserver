<?php

require('../../lib/Util.php');

use database\Database;
use app\JSONApp;
use auth\UnauthorizedException;

class Setup extends JSONApp
{
    function main($args)
    {
        $appUsr=isset($args['appUsr']) ? $args['appUsr'] : null;
		$appPwd=isset($args['appPwd']) ? $args['appPwd'] : null;

        $db=Database::getInstance();

        $res=$db->execute('INSERT INTO users (name,auth) VALUES (?,?)',[$appUsr,hash('sha256',$appPwd)]);
        if($res<0)
        {
            throw new SfsException('First user');
        }

        $res=$db->execute('INSERT INTO user2groups (user,grp) VALUES (?,"admin"),(?,"users")',[$appUsr,$appUsr]);
        if($res<0)
        {
            throw new SfsException('First user');
        }

        return True;
    }
}


$setup=new Setup();
$setup->run();