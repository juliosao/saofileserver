<?php

namespace auth;

use \database\Database;
use \database\DBObject;

class User extends DBObject
{
    static $db=null;
	static $keys=array('id');
    static $fields=array('id','name','session','auth','mail');
	static $table='users';
    
    public $id;
    public $name;    
    public $mail;
    protected $session;
    protected $auth;

	static function init()
	{
		User::$db = Database::getInstance();
	}

    function equals($obj)
    {
        if(! $obj instanceof User )
            return false;
        
        if($obj->id != $this->id)
            return false;

        return true;
    }

    function isFromGroup($groupName)
    {
        $groups = self::$db->query("SELECT grp FROM user2groups INNER JOIN groups ON user2groups.grp = groups.id WHERE user=? AND groups.name=?", array($this->id,$groupName));
        return count($groups)>0;
    }

    static function checkPassw($usr,$pw)
    {
        $auth=hash('sha256',$pw);

        $lst = User::select(array('name'=>$usr,'auth'=>$auth));
        error_log(json_encode($lst));

        if(count($lst)!=1)
            return false;

        $usr=$lst[0];

        if($usr->auth!=$auth && $usr->auth!=null)
            return false;
        
        $usr->session=session_id();
        $usr->update();

        return $usr;
    }

    function checkSession($id)
    {
        return $this->session===$id;
    }

    function setSession($id)
    {
        $this->session=$id;
    }

    function setPw($pw)
    {
        $auth=hash('sha256',$pw);
        $this->auth=$auth;
    }
}

User::init();