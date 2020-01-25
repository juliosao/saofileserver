<?php
require_once('../../../lib/Util.php');

use app\JSONApp;
use filesystem\FileSystemObject;
use filesystem\Directory;

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

        $parent=new Directory(FileSystemObject::joinPath($basedir,$p));
        $parent->mkdir($n,false);
        
        return true;
    }
}

$b= new MyApp();
$b->run();
