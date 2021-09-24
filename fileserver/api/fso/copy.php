<?php
require_once('../../lib/Util.php');
    
use app\JSONApp;
use filesystem\FileSystemObject;

class copy extends JSONApp{
    public function __construct()
    {
        parent::__construct(1);
    }

    public function main($args) {   
        $basedir=FileSystemObject::fromPath(Cfg::get()->fso->basedir);
        
        if(!isset($args["from"]) || !isset($args["to"]))
        {
            throw new InvalidArgumentException("Expected from and to");
        }

        $from = urldecode($args["from"]);
        $to = urldecode($args["to"]);

                   
        $objFrom=FileSystemObject::fromPath(FileSystemObject::joinPath($basedir,$from));




        $dest = FileSystemObject::joinPath($basedir,urldecode($to));
        $obj->copyTo($dest);


        return true;
    }
}

$b= new copy();
$b->run();
