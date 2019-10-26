<?php
require_once('../../../lib/Util.php');
    
class MyApp extends JSONApp{
    public function __construct()
    {
        parent::__construct(1);
    }

    public function main($args) {   
        $basedir=Cfg::get()->fso->basedir;
        $path= $args["path"];
        $name= $args["name"];

        
        if(is_null($path)) {
            $this->exitError(400,"Mkdir what?");
        }
            
        $p=urldecode($path);
        $n=urldecode($name);

        $parent=new FSODir(FSO::joinPath($basedir,$p));
        $parent->mkdir($n,false);
        
        return true;
    }
}

$b= new MyApp();
$b->run();
