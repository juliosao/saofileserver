<?php
require_once('../../../lib/Util.php');
    
use app\JSONApp;
use filesystem\FileSystemObject;

class delete extends JSONApp{
    public function __construct()
    {
        parent::__construct(1);
    }

    public function main($args) {   
        if(! isset($args["path"]))
        {
            throw new InvalidRequestException("Delete what?");
        }

        $basedir = FileSystemObject::fromPath(Cfg::get()->fso->basedir);
        $path = $args["path"];
        $objDel = $basedir->getChild($path);
        $objDel->delete();
        return true;
    }
}

$b= new delete();
$b->run();
