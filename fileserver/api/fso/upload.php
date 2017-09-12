<?php
require_once('../../lib/Util.php');
require_once(\App::getAppDir().'cfg/fso.cfg');
    
class Upload extends JSONApp
{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function main() {
        global $basedir;
        error_log("Se quiere subir ficheros");
        $path= urldecode($_POST["path"]);
        $dest= fso\FSO::joinPath($basedir,$path);
        $dir= new fso\FSODir($dest);

        error_log("DEST:".$dest);

        if(!$dir->exists())
        {
            $this->exitApp(false,$path." not found");
        }

        foreach($_FILES as $file)
        {
            error_log(json_encode($file));
            $newPath = fso\FSO::joinPath($dest,$file['name']);
            $fsoFile=new fso\FSOFile(newPath);
            if($fsoFile->exists()) 
            {
                $this->exitApp(false,$file['name'][0]." allready exists in ".$path);
            }

            error_log("TMPNAME:".json_encode($file['tmp_name'][0]));
            error_log("NEWNAME:".json_encode($newPath));
            error_log("FILE:".json_encode($fsoFile));
            move_uploaded_file($file['tmp_name'][0],$newPath);
        }
    }
}

$b= new Upload();
$b->run();