<?php

class Group extends \database\DBObject
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
        self::$db=\database\Database::getInstance();
	}

    function __construct($src)
    {
        parent::__construct($src);        
    }

    function equals($obj)
    {
        if( $obj instanceof \auth\User && $obj->id == $this->id)
        {
            return true;
        }
        else ( is_string( $obj ) && $obj == $this->id )
        {
            return true;
        }
        
        return false;
    }

    function save()
    {
        $res=parent::replace();
        error_log("Grupo Guardado:".$res);
    }
}

Group::init();