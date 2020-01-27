<?php

namespace app;

use SfsException;

//Represents a http callable mini-application
abstract class JSONApp extends App{
    public function __construct($doAuth=false)
    {
		error_log("Mirando si tenemos que hacer autenticacion: $doAuth");
		parent::__construct($doAuth);
		$this->params=null;
    }

	function getParam($param,$default=null)
	{
		return isset($this->params[$param]) ? $this->params[$param] : $default;
	}


	//Runs JSONApp
	public function run()
	{
		try
		{
			parent::$current=$this;
			ob_start();
			$this->params=json_decode(file_get_contents('php://input'),true);
			$res = json_encode($this->main($this->params, true));
			error_log("Res:".$res);
			echo $res;
			ob_flush();
		}
		catch(SfsException $fsex)
		{
			$fsex->abort(App::$debug);
		}

		die();
	}
    
}
