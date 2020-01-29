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
       
        if(!isset($args["path"]) || !isset($args["name"])) {
            throw new InvalidRequestException('mkdir what?');
        }

        $basedir=FileSystemObject::fromPath(Cfg::get()->fso->basedir);
        $path= $args["path"];
        $name= $args["name"];
        
        $p=urldecode($path);
        $n=urldecode($name);

        $parent=$basedir->getChild($p);
        $parent->mkdir($n,false);
        
        return true;
    }
}

$b= new MyApp();
$b->run();
