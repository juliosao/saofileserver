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
        $ok=array();
        $ko=array();
        $err=null;
        $res=array();

        
        if(is_null($paths)) {
            $this->exitError(400,"Delete what?");
        }
               
        foreach($paths as $path)
        {   
            $p=urldecode($path);
            $obj=fso\FSO::fromPath(fso\FSO::joinPath($basedir,$p));        

            if($obj==null)
            {
                $err="Not found:";
                $ko[]=$obj->relativePath($basedir);
            }
            else if(!$obj->delete())
            {
                $err="Cannot delete";
                $ko[]=$obj->relativePath($basedir);
            }
            else
            {
                $ok[]=$obj->relativePath($basedir);
            }
        }
        
        $res=array('deleted'=>$ok, 'failed'=>$ko,'function'=>'delete');
        if($err!=null)
        {
            $res['error']=$err;
        }

        return $res;
    }
}

$b= new delete();
$b->run();
