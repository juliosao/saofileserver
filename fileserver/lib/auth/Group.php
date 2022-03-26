<?php

namespace auth;

use \database\DBObject;
use \MethodNotAllowedException;

class Group extends DBObject
{
	static $keys=['name'];
	static $fields=['name'];
    static $table='groups';
    static $onNotFound='auth\GroupNotFoundException';
	
    function equals($obj)
    {
        if( $obj instanceof Group && $obj->name == $this->name)
        {
            return true;
        }
        else if ( is_string( $obj ) && $obj == $this->name )
        {
            return true;
        }
        
        return false;
    }

    static function fromUser(User $u)
    {
        $groups = self::$db->query('SELECT grp FROM user2groups d 
                                        WHERE user=?', [$u->name],
                                        static::class);
        return $groups;
    }
    
    static  function selectQry()
    {
        return "SELECT name FROM groups";
    }

    static function getQry()
    {
        return "SELECT name FROM groups WHERE name=? LIMIT 1";
    }
    	
    static function insertQry()
    {
        return "INSERT INTO groups (name) VALUES (:name)";
    }

    static function deleteQry()
    {
        return null;
    }

    function delete()
    {
        return static::$db->execute("DELETE FROM groups WHERE name=?",[$this->name]);
    }

    static function updateQry()
    {
        throw new MethodNotAllowedException();
    }
}

Group::init();