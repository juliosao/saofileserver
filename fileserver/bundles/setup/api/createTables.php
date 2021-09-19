<?php

require('../../../lib/Util.php');

use app\JSONApp;
use database\Database;
use auth\UnauthorizedException;

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

        $db=new Database(null,$usr,$pwd);

        $res=$db->execute('CREATE TABLE users (
            name VARCHAR(64) PRIMARY KEY NOT NULL,
            auth VARCHAR(256),
            session VARCHAR(256),
            mail VARCHAR(256) )');


        $res=$db->execute('CREATE TABLE groups (
            name VARCHAR(64) PRIMARY KEY NOT NULL UNIQUE
        )');

        $res=$db->execute('CREATE TABLE `user2groups` (
            `user` VARCHAR(64) NOT NULL,
            `grp` VARCHAR(64) NOT NULL,
            PRIMARY KEY (`user`,`grp`),
            CONSTRAINT `user2groups_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users`(`name`) ON DELETE CASCADE,
            CONSTRAINT `user2groups_ibfk_2` FOREIGN KEY (`grp`) REFERENCES `groups`(`name`) ON DELETE CASCADE
          )');
                
        return True;
    }
}


$setup=new Setup();
$setup->run();
