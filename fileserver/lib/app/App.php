<?php

namespace app;

//Represents a http callable mini-application
abstract class App {
	private static $appDir = null;
	private static $appURL = null;
	protected static $current = null;
    protected $result;

    public function __construct($doAuth=false)
    {
		if($doAuth)
		{
			if(\auth\Auth::checkSession()==false)
			{
				$redirect=App::getAppURL().'views/login/login.php';
				header('Location: '.$redirect,true,302);
				die('Sesion invalida');
			}
		}
        $this->result=array();
    }

	abstract function main();

	//Runs JSONApp
	public function run()
	{
		self::$current=$this;
		ob_start();		
		$this->main();
		ob_flush();
		die();
	}

	public function exitError($errNumber=500,$msg='')
	{
		ob_clean();
		http_response_code($errNumber);
		if($msg!='')
			echo $msg;
		ob_flush();
		die();
	}
	
	public static function getAppDir()
	{
		if(self::$appDir===null)
			self::$appDir = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR;
		return self::$appDir;
	}
	
	public static function getAppURL()
	{
		if(self::$appURL === null);
		{
			$tmpRoot=explode(DIRECTORY_SEPARATOR,$_SERVER['CONTEXT_DOCUMENT_ROOT']);
			$tmpApp=explode(DIRECTORY_SEPARATOR,self::getAppDir());

			while(count($tmpRoot) && $tmpRoot[0] == $tmpApp[0])
			{
				array_shift($tmpRoot);
				array_shift($tmpApp);
			}

			self::$appURL= $_SERVER['CONTEXT_PREFIX'].DIRECTORY_SEPARATOR.implode('/',$tmpApp);
		}
		return self::$appURL;
	}
	
	
}
