<?php

namespace auth;

use \database\Database;
use \database\DBObject;

class Group extends DBObject
{
    static $db=null;
	static $keys=array('id');
	static $fields=array('id','name');
	static $table='users';
	
	// Mandatory
	static $select=null;
	static $insert=null;
	static $update=null;
	static $delete=null;

    static $current=null;

    static function init()
    {
        self::$db=Database::getInstance();
	}

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
        $res=array();
        $groups = self::$db->query('SELECT id,name FROM user2groups 
                                        INNER JOIN groups 
                                            ON user2groups.grp = groups.id 
                                        WHERE user=?', array($u->id),
                                        static::class);
        return $groups;
    }

    function save()
    {
        $res=parent::update();
        error_log("Grupo Guardado:".$res);
    }
}

Group::init();