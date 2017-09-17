<?php

class Auth extends DBObject
{
    static $db=null;
	static $keys=array('id');
	static $fields=array('id','auth','session');
	static $table='users';
	
	// Mandatory
	static $select=null;
	static $insert=null;
	static $update=null;
	static $delete=null;

    static $current=null;

    static function init()
    {
        session_start();
        self::$db=Database::getInstance();
	}

    function __construct($src)
    {
        parent::__construct($src);        
    }

    static function checkSession()
    {
        if(! isset($_SESSION['usr']))
        {
return false;
        }
            

        $lst = Auth::select(array('id'=>$_SESSION['usr']));

        if(count($lst)!=1)
            return false;

        if($lst[0]->session!=session_id())
            return false;

        return true;
    }

    static function checkPassw($usr,$pw)
    {
        $auth=hash('sha256',$pw);

        $lst = Auth::select(array('id'=>$usr,'auth'=>$auth));

        if(count($lst)!=1)
            return false;

        $lst[0]->save();

        return $lst[0];
    }

    static function get()
    {
        return self::$current;
    }
    
    static function set($c)
    {
        $_SESSION['usr']=$c->id;
        self::$current=$c;
    }

    function save()
    {
        $this->session=session_id();
        $res=parent::replace();
        error_log("Sesion Guardada:".$res);
    }
}

Auth::init();