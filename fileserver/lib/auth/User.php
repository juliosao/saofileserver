<?php

namespace auth;

use \database\Database;
use \database\DBObject;

class User extends DBObject
{
	static $keys=array('name');
    static $fields=array('name','session','auth','mail');
    static $table='users';
    static $onNotFound='auth\UserNotFoundException';
    //Mandatory
    static $selectQry = null;
	static $fieldsEnum = null;
	static $insert=null;
	static $update=null;
	static $delete=null;

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

        if($obj->name != $this->name)
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
        return User2Group::get(null,$this->name,$groupName) !== null;
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

        if(User2Group::get(null,$this->name,$g->name))
            return true;

        $u2g = new User2Group();
        $u2g->user = $this->name;
        $u2g->grp = $g->name;
        $u2g->insert();
        return;
    }

    public function removeGroup(Group $g)
    {
        if($this == Auth::get())
        {
            throw new CannotModifyYourselfException();
        }

        $del = User2Group::get(null,$this->name,$g->name);
        if($del === null)
            return true;

        $del->delete();
    }

    static  function selectQry()
    {
        return "SELECT name,auth,session,mail FROM users";
    }

    static function getQry()
    {
        return "SELECT name,auth,session,mail FROM users WHERE name=? LIMIT 1";
    }

    static function insertQry()
    {
        return "INSERT INTO users (name,auth,session,mail) VALUES ( :name, :auth, :session, :mail)";
    }

    static function updateQry()
    {
        return "UPDATE users SET auth=:auth, session=:session, mail=:mail WHERE name=:name";
    }

    static function deleteQry()
    {
        return null;
    }

    function delete()
    {
        return static::$db->execute("DELETE FROM users WHERE name=?",array($this->name));
    }

    static function created()
    {
        try
        {
            $res = static::$db->query('SELECT name FROM users LIMIT 1');
            return count($res)>0;
        }
        catch(Exception $e)
        {
            error_log($e);
            return false;
        }
    }
}
