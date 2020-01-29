<?php

namespace auth;

use \database\Database;
use \database\DBObject;

class User extends DBObject
{
	static $keys=array('id');
    static $fields=array('id','name','session','auth','mail');
    static $table='users';
    static $onNotFound='auth\UserNotFoundException';
    //Mandatory
    static $selectQry = null;
	static $fieldsEnum = null;
	static $insert=null;
	static $update=null;
	static $delete=null;
    
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

    function isFromGroup($groupName)
    {
        $group = Group::select(array('name'=>$groupName));
        if(count($group)==0)
            throw new GroupNotFoundException($groupName);
        
        return User2Group::get(null,$this->id,$group[0]->id) !== null;
    }

    public function getGroups()
	{
		return Group::fromUser($this);
    }
    
    public function addGroup(Group $g)
    {
        if($this == Auth::get())
        {
            throw new CannotModifyYourselfException();
        }

        if(User2Group::get(null,$this->id,$g->id))
            return true;

        $u2g = new User2Group();
        $u2g->user = $this->id;
        $u2g->grp = $g->id;
        $u2g->insert();
        return;
    }

    public function removeGroup(Group $g)
    {
        if($this == Auth::get())
        {
            throw new CannotModifyYourselfException();
        }

        $del = User2Group::get(null,$this->id,$g->id);
        if($del === null)
            return true;
        
        $del->delete();
    }

    static  function selectQry()
    {
        return "SELECT id,name,auth,session,mail FROM users";
    }

    static function getQry()
    {
        return "SELECT id,name,auth,session,mail FROM users WHERE id=? LIMIT 1";
    }
    	
    static function insertQry()
    {
        return "INSERT INTO users (id,name,auth,session,mail) VALUES (:id, :name, :auth, :session, :mail)";
    }

    static function updateQry()
    {
        return "UPDATE users SET name=:name, auth=:auth, session=:session, mail=:mail WHERE id=:id";
    }
    
    static function deleteQry()
    {
        return null;
    }

    function delete()
    {
        return static::$db->execute("DELETE FROM users WHERE id=?",array($this->id));
    }
}

