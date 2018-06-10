<?php
require_once('../../lib/Util.php');
    
class delete extends app\JSONApp{
    public function __construct()
    {
        parent::__construct(1);
    }

    public function main() {   
        $basedir=Cfg::get()->fso->basedir;
		$result=array('function'=>'delete');
		
        $mal=0;
        error_log(json_encode($_POST));
        $paths= $_POST["path"];
        $borrado=array();
        
        if(is_null($paths)) {
            $this->exitError(400,"Delete what?");
        }
               
        foreach($paths as $path)
        {   
            $p=urldecode($path);
            $obj=fso\FSO::fromPath(fso\FSO::joinPath($basedir,$p));        

            if($obj==null)
            {
                $this->exitError(404,"Not found: $p");
            }

            if(!$obj->delete())
            {
                $this->exitError(500,"Cannot delete $p: ".$obj->error);
            }
        }
        
    }
}

$b= new delete();
$b->run();
