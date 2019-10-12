<?php

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
			$this->result=array();
			$this->buffered=False;
		}
		catch(FsoException $fsex)
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
			$this->main();
			if($this->buffered)
				ob_flush();
		}
		catch(FsoException $fsex)
		{
			$fsex->abort(App::$debug);
		}

		die();
	}

	function getParam($param,$default=null)
	{
		return isset($_REQUEST[$param]) ? $_REQUEST[$param] : $default;
	}

	/*
	public function exitError($errNumber=500,$msg='')
	{
		ob_clean();
		http_response_code($errNumber);
		if($msg!='')
			echo $msg;
		ob_flush();
		die();
	}
	*/

	public static function getAppPath($file='')
	{
		if(self::$appDir===null)
			self::$appDir = dirname(__DIR__).DIRECTORY_SEPARATOR;
		return self::$appDir.$file;
	}
	
	public static function getAppURL($file='')
	{
		if(self::$appURL === null);
		{
			$tmpRoot=explode(DIRECTORY_SEPARATOR,$_SERVER['CONTEXT_DOCUMENT_ROOT']);
			$tmpApp=explode(DIRECTORY_SEPARATOR,self::getAppPath());

			while(count($tmpRoot) && $tmpRoot[0] == $tmpApp[0])
			{
				array_shift($tmpRoot);
				array_shift($tmpApp);
			}

			self::$appURL= $_SERVER['CONTEXT_PREFIX'].DIRECTORY_SEPARATOR.implode('/',$tmpApp).DIRECTORY_SEPARATOR;
		}
		return self::$appURL.$file;
	}
}
