<?php
require_once '../lib/JSONApp.php';
require_once '../lib/FSO.php';

class delete extends JSONApp{
    public function main() {
        $mal=0;
        error_log(json_encode($_POST));
        $paths=  filter_input(INPUT_POST, "paths",FILTER_UNSAFE_RAW,FILTER_REQUIRE_ARRAY);
        
        if(is_null($paths)) {
            $this->exitApp(false,'Ruta no encontrada.');
        }
               
        foreach($paths as $path)
        {   
            $real=base64_decode($path);
            $obj=FSO::fromPath(FSO::joinPath('/var/shared-data', $real));        

            if($obj==null)
            {
                $this->exitApp(false,"Ruta no encontrada: $real");
            }

            if(!$obj->delete())
            {
                $this->exitApp(false,"Imposible borrar $real: ".$obj->error);
            }
        }
    }
}

$b= new delete();
$b->run();
