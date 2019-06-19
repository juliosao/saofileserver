<?php

require('../../../lib/Util.php');

class Setup extends JSONApp
{
    function main()
    {
        $usr=getParam('usr','');
        $pwd=getParam('pwd','');

        if($usr==null || $pwd==null)
        {
            throw new UnauthorizedException();
        }

        $db=new Database("mysql:host=localhost;",$usr,$pwd);

        $res=$db->execute('CREATE TABLE users (
            id INT PRIMARY KEY,
            name VARCHAR(64) NOT NULL UNIQUE,
            auth VARCHAR(256),
            session VARCHAR(256),
            mail VARCHAR(256) )');
        if($res<0)
        {
            throw new FsoException("Table users");
        }

        $res=$db->execute('CREATE TABLE groups (
            id INT PRIMARY KEY,
            name VARCHAR(64) NOT NULL UNIQUE
        )');
        if($res<=0)
        {
            throw new FsoException("Table groups");
        }

        $res=$db->execute('CREATE TABLE user2groups (
            user INT REFERENCES users(id),
            grp INT REFERENCES groups(id),
            PRIMARY KEY (user,grp)
        )');
        if($res<0)
        {
            throw new FsoException("Table user2groups");
        }

        return True;
    }
}


$setup=new Setup();
$setup->run();