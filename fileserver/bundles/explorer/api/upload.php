<?php
require_once('../../../lib/Util.php');
    
class Upload extends App
{
    
    public function __construct()
    {
        parent::__construct(1);
    }

    public function main($argv) {
	    $ok=array();
	    $ko=array();
        $basedir=Cfg::get()->fso->basedir;
        error_log("Se quiere subir ficheros");
        error_log(json_encode($_FILES));
        if(count($_POST)==0)
        {
            $this->exitError(413,"File too long");
        }

        $path= urldecode($_POST["path"]);

        $dest= FSO::joinPath($basedir,$path);
        $dir= new FSODir($dest);

        error_log("DEST:".$dest);

        if(!$dir->exists())
        {
            $this->exitErro(404,$path." not found");
        }

        $files=$_FILES['files'];
        foreach($files['error'] as $idx => $err )
        {
            if($err != null)
            {
                //$this->exitApp(false,"Cannot upload ".$files['name'][$idx]);
                throw FsoException("Cannot upload:".$files['name'][$idx]);
            }

            $newPath = FSO::joinPath($dest,$files['name'][$idx]);
            $fsoFile=new FSOFile($newPath);
            if($fsoFile->exists()) 
            {
                throw InvalidRequestException("File exists:".$files['name'][$idx]);
            }

            error_log("TMPNAME:".json_encode($files['tmp_name'][$idx]));
            error_log("NEWNAME:".json_encode($newPath));
            error_log("FILE:".json_encode($fsoFile));
            
            if(!move_uploaded_file($files['tmp_name'][$idx],$newPath))
		    {
                throw FsoException("Cannot move:".$files['name'][$idx]);
		    }
		}            
        
        echo json_encode(true);
	}
}

$b= new Upload();
$b->run();
