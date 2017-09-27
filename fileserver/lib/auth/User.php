<?php

namespace auth;

class User extends \database\DBObject
{
    static $db=null;
	static $keys=array('id');
	static $fields=array('id','session');
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

    static function checkPassw($usr,$pw)
    {
        $auth=hash('sha256',$pw);

        $lst = User::select(array('id'=>$usr,'auth'=>$auth));

        if(count($lst)!=1)
            return false;

        $lst[0]->save();

        return $lst[0];
    }

    function save()
    {
        $res=parent::replace();
        error_log("Usuario Guardado:".$res);
    }
}

User::init();