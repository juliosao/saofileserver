<?php

//Represents a http callable mini-application
abstract class JSONApp extends App{
    public function __construct($doAuth=false)
    {
		error_log("Mirando si tenemos que hacer autenticacion: $doAuth");
        parent::__construct($doAuth);
    }

	//Runs JSONApp
	public function run()
	{
		try
		{
			parent::$current=$this;
			ob_start();
			$res = json_encode($this->main());
			echo $res;
			ob_flush();
		}
		catch(FsoException $fsex)
		{
			$fsex->abort(App::$debug);
		}

		die();
	}
    
}
