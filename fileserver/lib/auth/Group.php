<?php

namespace auth;

use \database\DBObject;
use \MethodNotAllowedException;

class Group extends DBObject
{
	static $keys=array('id');
	static $fields=array('id','name');
    static $table='groups';
    static $onNotFound='auth\GroupNotFoundException';
	
    function equals($obj)
    {
        if( $obj instanceof User && $obj->id == $this->id)
        {
            return true;
        }
        else if ( is_string( $obj ) && $obj == $this->id )
        {
            return true;
        }
        
        return false;
    }

    static function fromUser(User $u)
    {
        $groups = self::$db->query('SELECT id,name FROM user2groups 
                                        INNER JOIN groups 
                                            ON user2groups.grp = groups.id 
                                        WHERE user=?', array($u->id),
                                        static::class);
        return $groups;
    }
    
    static  function selectQry()
    {
        return "SELECT id,name FROM groups";
    }

    static function getQry()
    {
        return "SELECT id,name FROM groups WHERE id=? LIMIT 1";
    }
    	
    static function insertQry()
    {
        return "INSERT INTO groups (id,name) VALUES (:id, :name)";
    }

    static function deleteQry()
    {
        return "DELETE FROM groups WHERE id=:id";
    }

    static function updateQry()
    {
        throw new MethodNotAllowedException();
    }
}

Group::init();