<?php

namespace auth;

use \database\DBObject;
use \MethodNotAllowedException;

class User2Group extends DBObject
{
	static function onNotFound($unused)
	{
		return null; // Do not throw exception on not foun user2group
	}

    static function selectQry()
    {
        return "SELECT user,grp FROM user2groups";
    }

    static function getQry()
    {
        return "SELECT user,grp FROM user2groups WHERE user=? AND grp=? LIMIT 1";
    }
    	
    static function insertQry()
    {
        return "INSERT INTO user2groups (user,grp) VALUES (:user, :grp)";
    }

    static function deleteQry()
    {
        return "DELETE FROM user2groups WHERE user=:user AND grp=:grp";
	}
	
	static function updateQry()
    {
        throw new MethodNotAllowedException();
    }

}
