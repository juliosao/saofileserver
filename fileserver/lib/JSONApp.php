<?php

//Represents a http callable mini-application
abstract class JSONApp extends App{
   
    public function __construct()
    {
         parent::__construct();
    }

    //Runs JSONApp
    public function run()
    {
        $this->main();
        $this->exitApp();
    }
    
    //Sets application result
    public function setResult($key,$value)
    {
        $this->result[$key]=$value;
    }
    
    //Set app result and exit
    public function exitApp($ok=true,$error='')
    {
        $this->result['ok']=$ok;
        $this->result['error']=$error;
        
        die(json_encode($this->result));
    }
}

