<?php

require('../../../lib/Util.php');

class Setup extends JSONApp
{
    function main($args)
    {
        $usr=isset($args['usr']) ? $args['usr'] : null;
        $pwd=isset($args['pwd']) ? $args['pwd'] : null;

        if($usr==null || $pwd==null)
        {
            throw new UnauthorizedException();
        }

        $db=new Database("mysql:host=localhost;",$usr,$pwd);

        $res=$db->execute('DROP DATABASE IF EXISTS saofileserver');
        if($res<0)
        {
            throw new FsoException("Clean database");
        }

        $res=$db->execute("CREATE DATABASE saofileserver CHARSET=latin1; USE saofileserver");
        if($res<=0)
        {
            throw new FsoException("Create database");
        }

        $res=$db->execute("GRANT DELETE,UPDATE,INSERT,SELECT ON saofileserver.* TO saofileserver@localhost IDENTIFIED BY 'saofileserver' WITH GRANT OPTION");
        if($res<0)
        {
            throw new FsoException("Create system user");
        }

        return True;
    }
}


$setup=new Setup();
$setup->run();