<?php

namespace app;

//Represents a http callable mini-application
abstract class JSONApp extends App{
    public abstract function main();

    public function __construct($doAuth=false)
    {
         parent::__construct($doAuth);
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
        
        header("content-type: text/json");
        die(json_encode($this->result));
    }
}
