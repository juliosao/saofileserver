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
        $basedir=Cfg::get()->fso->basedir;
		$result=array('function'=>'delete');
		
        $mal=0;
        error_log(json_encode($args));
        $paths= $args["path"];
        $ok=array();
        $ko=array();
        $err=null;
        $res=array();

        
        if(is_null($paths)) {
            $this->exitError(400,"Delete what?");
        }

        // We support a object list or a single object
        if(gettype($paths)=='string')
        {
            $paths=array($paths);
        }
               
        foreach($paths as $path)
        {   
            $p=urldecode($path);
            $obj=FileSystemObject::fromPath(FileSystemObject::joinPath($basedir,$p));        

            if($obj==null)
            {
                throw new NotFoundException($p);
            }
            else if(!$obj->delete())
            {
                throw new MethodNotAllowedException($p,'delete');
            }
        }        

        return true;
    }
}

$b= new delete();
$b->run();
