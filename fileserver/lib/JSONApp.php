<?php

abstract class JSONApp {
    var $result=array();
    
    abstract function main();
    
    public function run()
    {
        $this->main();
        $this->exitApp();
    }
    
    public function setResult($key,$value)
    {
        $this->result[$key]=$value;
    }
    
    public function exitApp($ok=true,$error='')
    {
        $this->result['ok']=$ok;
        $this->result['error']=$error;
        
        die(json_encode($this->result));
    }
}

