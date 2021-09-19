<?php

namespace app;

use \SfsException;
use \auth\Auth;
use \auth\UnauthorizedException;
use Cfg;

//Represents a http callable mini-application
abstract class App {
	private static $appDir = null;
	private static $appURL = null;
	protected static $current = null;
	protected $result;
	static $debug = True;

    public function __construct($doAuth=false)
    {
		try
		{
			if($doAuth)
			{
				if(Auth::checkSession()==false)
				{
					throw new UnauthorizedException();
				}
			}
			$this->result=[];
			$this->buffered=False;
		}
		catch(SfsException $fsex)
		{
			$fsex->abort(App::$debug);
			die();
		}

		
    }

	abstract function main($args);

	function setBuffered($mode=true)
	{
		$this->buffered=$mode;
		if($mode==false)
			ob_end_flush();
	}

	//Runs JSONApp
	public function run()
	{
		try
		{
			self::$current=$this;
			ob_start();
			$tmp = json_decode(readfile('php://input'));
			$this->main($tmp);
			if($this->buffered)
				ob_flush();
		}
		catch(SfsException $fsex)
		{
			$fsex->abort(App::$debug);
		}

		die();
	}

	function getParam($param,$default=null)
	{
		return isset($_REQUEST[$param]) ? $_REQUEST[$param] : $default;
	}

	public static function getAppPath($file='')
	{
		if(self::$appDir===null)
			self::$appDir = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR;
		return self::$appDir.$file;
	}
	
	public static function getAppURL($file='')
	{
		if(self::$appURL === null);
		{
			$name = Cfg::get()->app->name;
			$pos = strpos($_SERVER['PHP_SELF'],$name);
			self::$appURL = $pos!==false ? substr($_SERVER['PHP_SELF'],0,$pos).$name.'/' : '/';
		}
		return self::$appURL.$file;
	}
}
