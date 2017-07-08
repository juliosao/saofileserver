<?php

//Represents a http callable mini-application
abstract class App {
	private static $appDir = null;
	private static $appURL = null;
    protected $result;

    public function __construct()
    {
        $this->result=array();
    }

    //Runs JSONApp
    public abstract function run();


	public function exitApp($msg)
	{
		die($msg);
	}
	
	public static function getAppDir()
	{
		if(self::$appDir===null)
			self::$appDir = dirname(__DIR__).DIRECTORY_SEPARATOR;
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
