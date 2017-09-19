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

        return true;
    }

    static function checkPassw($usr,$pw)
    {
        $auth=hash('sha256',$pw);

        $lst = User::select(array('id'=>$usr,'auth'=>$auth));

        if(count($lst)!=1)
            return false;

        $lst[0]->session=session_id();
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
}

Auth::init();