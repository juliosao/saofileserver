<?php
require_once('../../lib/Util.php');
    
class Upload extends app\JSONApp
{
    
    public function __construct()
    {
        parent::__construct(1);
    }

    public function main() {
        $basedir=Cfg::get()->fso->basedir;
        error_log("Se quiere subir ficheros");
        error_log(json_encode($_FILES));
        if(count($_POST)==0)
        {
            $this->exitApp(false,"File too long");
        }

        $path= urldecode($_POST["path"]);


        $dest= fso\FSO::joinPath($basedir,$path);
        $dir= new fso\FSODir($dest);

        error_log("DEST:".$dest);

        if(!$dir->exists())
        {
            $this->exitApp(false,$path." not found");
        }

        $files=$_FILES['files'];
        foreach($files['error'] as $idx => $err )
        {
            if($err != null)
            {
                $this->exitApp(false,"Cannot upload ".$files['name'][$idx]);
            }

            $newPath = fso\FSO::joinPath($dest,$files['name'][$idx]);
            $fsoFile=new fso\FSOFile($newPath);
            if($fsoFile->exists()) 
            {
                $this->exitApp(false,$files['name'][$idx]." allready exists in ".$path);
            }

            error_log("TMPNAME:".json_encode($files['tmp_name'][$idx]));
            error_log("NEWNAME:".json_encode($newPath));
            error_log("FILE:".json_encode($fsoFile));
            if(move_uploaded_file($files['tmp_name'][$idx],$newPath))
            {
                $this->setResult('function','upload');
            }
            else
            {
                $this->exitApp(false,"Cannot create ".$files['name'][$idx]);
            }
        }
    }
}

$b= new Upload();
$b->run();