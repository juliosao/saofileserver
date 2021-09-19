<?php

require('../../../lib/Util.php');

use database\Database;
use app\JSONApp;
use auth\UnauthorizedException;

class Setup extends JSONApp
{
    function main($args)
    {
        $usr=isset($args['usr']) ? $args['usr'] : null;
        $pwd=isset($args['pwd']) ? $args['pwd'] : null;
        $appUsr=isset($args['appUsr']) ? $args['appUsr'] : null;
		$appPwd=isset($args['appPwd']) ? $args['appPwd'] : null;

        if($usr==null || $pwd==null)
        {
            throw new UnauthorizedException();
        }

        $db=new Database(null,$usr,$pwd);

        $res=$db->execute("INSERT INTO groups (name) VALUES (0,'admin')");
        if($res<0)
        {
            throw new SfsException("Admin group");
        }

        $res=$db->execute("INSERT INTO groups (name) VALUES (1,'users')");
        if($res<0)
        {
            throw new SfsException("Users group");
        }
        
        $res=$db->execute("INSERT INTO users (name,auth) VALUES (?,?)",[$appUsr,hash('sha256',$appPwd)]);
        if($res<0)
        {
            throw new SfsException("First user");
        }

        $res=$db->execute("INSERT INTO user2groups (user,grp) VALUES (?,'admin'),(?,'admin')",[$appUsr,$appUsr]);
        if($res<0)
        {
            throw new SfsException("First user");
        }
            
        return True;
    }
}


$setup=new Setup();
$setup->run();