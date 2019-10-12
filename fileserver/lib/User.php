<?php

class User extends DBObject
{
    static $db=null;
	static $keys=array('id');
	static $fields=array('id','name','session','auth','mail');
	static $table='users';
	
	// Mandatory
	static $select=null;
	static $insert=null;
	static $update=null;
	static $delete=null;    

    static function init()
    {
        self::$db=Database::getInstance();
	}

    function __construct($src)
    {
        parent::__construct($src);        
    }

    function equals($obj)
    {
        if(! $obj instanceof User )
            return false;
        
        if($obj->id != $this->id)
            return false;

        return true;
    }

    function getGroups()
    {
        $res=array();
        $groups = self::$db->query("SELECT grp FROM user2groups INNER JOIN groups ON user2groups.grp = groups.id WHERE user=?", array($this->id));
        foreach($groups as $grp)
        {
            $res[]=new Group($grp);
        }
        return $res;
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
        $usr->save();

        return $usr;
    }

    function setPw($pw)
    {
        $auth=hash('sha256',$pw);
        $this->auth=$auth;
    }

    function save()
    {
        $res=parent::replace();
        error_log("Usuario Guardado:".$res);
    }

}

User::init();