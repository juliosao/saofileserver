<?php

namespace auth;

class User extends \database\DBObject
{
    static $db=null;
	static $keys=array('id');
	static $fields=array('id','session','auth','mail');
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
        if(! $obj instanceof \auth\User )
            return false;
        
        if($obj->id != $this->id)
            return false;

        return true;
    }

    static function checkPassw($usr,$pw)
    {
        $auth=hash('sha256',$pw);

        $lst = User::select(array('id'=>$usr,'auth'=>$auth));
        error_log(json_encode($lst));

        if(count($lst)!=1)
            return false;

        $usr=$lst[0];

        if($usr->auth!=$auth && $usr->auth!=null)
            return false;
        
        $usr->session=session_id();
        $usr->save();

        return $usr;
    }

    function savePw($pw)
    {
        $auth=hash('sha256',$pw);
        return self::$db->execute("UPDATE users SET auth=:auth WHERE id=:id",array('auth'=>$auth,'id'=>$this->id))==1;
    }

    function save()
    {
        $res=parent::replace();
        error_log("Usuario Guardado:".$res);
    }
}

User::init();