<?php
require_once '../../../lib/JSONApp.php';
require_once '../lib/FSO.php';
require_once("../cfg/fso.cfg");

class delete extends JSONApp{
    public function main() {
		global $basedir;
    
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
            $obj=FSO::fromPath(FSO::joinPath($basedir, $path));        

            if($obj==null)
            {
                $this->exitApp(false,"Ruta no encontrada: $path");
            }

            if(!$obj->delete())
            {
                $this->exitApp(false,"Imposible borrar $path: ".$obj->error);
            }
        }
        
    }
}

$b= new delete();
$b->run();
