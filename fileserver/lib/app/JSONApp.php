<?php

namespace app;

//Represents a http callable mini-application
abstract class JSONApp extends App{
    public function __construct($doAuth=false)
    {
        parent::__construct($doAuth);
    }

	//Runs JSONApp
	public function run()
	{
		parent::$current=$this;
		ob_start();		
		echo json_encode($this->main());
		ob_flush();
		die();
	}
    
}
