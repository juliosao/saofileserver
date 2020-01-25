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
            
        $lst = User::select(array('id'=>$_SESSION['usr'],'session'=>session_id()));
        
        if(count($lst)!=1)
        {
            return false;
        }

        self::set($lst[0]);
        return true;
    }

    static function checkPassw($usr,$pw)
    {
        $usr = User::checkPassw($usr,$pw);

        if($usr===false)
        {
            return false;
        }

        self::set($usr);
        $usr->setSession(session_id());
        $usr->update();

        return $usr;
    }

    static function logout()
    {
        session_unset();
        session_destroy();
        self::$current->session=null;
        self::$current->update();
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
    }
}

Auth::init();