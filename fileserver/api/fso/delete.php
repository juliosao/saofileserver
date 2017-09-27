<?php
require_once('../../lib/Util.php');
    
class delete extends app\JSONApp{
    public function __construct()
    {
        parent::__construct(1);
    }

    public function main() {   
        $basedir=Cfg::get()->fso->basedir;
		$this->setResult('function','delete');
		
        $mal=0;
        error_log(json_encode($_POST));
        $paths= $_POST["path"];
        $borrado=array();
        
        if(is_null($paths)) {
            $this->exitApp(false,'Ruta no encontrada.');
        }
               
        foreach($paths as $path)
        {   
            $p=urldecode($path);
            $obj=fso\FSO::fromPath(fso\FSO::joinPath($basedir,$p));        

            if($obj==null)
            {
                $this->exitApp(false,"Ruta no encontrada: $p");
            }

            if(!$obj->delete())
            {
                $this->exitApp(false,"Imposible borrar $p: ".$obj->error);
            }
        }
        
    }
}

$b= new delete();
$b->run();
