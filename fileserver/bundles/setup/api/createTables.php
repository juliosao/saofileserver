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

        $db=new Database(null,$usr,$pwd);

        $res=$db->execute('CREATE TABLE users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(64) NOT NULL UNIQUE,
            auth VARCHAR(256),
            session VARCHAR(256),
            mail VARCHAR(256) )');


        $res=$db->execute('CREATE TABLE groups (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(64) NOT NULL UNIQUE
        )');


        $res=$db->execute('CREATE TABLE `user2groups` (
            `user` int(11) NOT NULL,
            `grp` int(11) NOT NULL,
            PRIMARY KEY (`user`,`grp`),
            KEY `grp` (`grp`),
            CONSTRAINT `user2groups_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
            CONSTRAINT `user2groups_ibfk_2` FOREIGN KEY (`grp`) REFERENCES `grp` (`id`) ON DELETE CASCADE
          )');
                
        return True;
    }
}


$setup=new Setup();
$setup->run();