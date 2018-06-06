<?php

namespace auth;

class Auth
{
    static $current=null;

    static function init()
    {
        session_start();
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
            
        $lst = User::select(array('id'=>$_SESSION['usr']));

        if(count($lst)!=1)
            return false;

        if($lst[0]->session!=session_id())
            return false;

        self::set($lst[0]);
        return true;
    }

    static function checkPassw($usr,$pw)
    {
        //error_log("usr:$usr, pw:$pw");
        $usr = User::checkPassw($usr,$pw);
        error_log(json_encode($usr));

        if($usr===false)
        {
            return false;
        }

        self::set($usr);

        return $usr;
    }

    static function logout()
    {
        session_unset();
        session_destroy();
        self::$current->session=null;
        self::$current->save();
        self::$current=null;
    }

    static function get()
    {
        return self::$current;
    }
    
    static function set($c)
    {
        $_SESSION['usr']=$c->id;
        self::$current=$c;
        $c->session=session_id();
        $c->save();
    }
}

Auth::init();