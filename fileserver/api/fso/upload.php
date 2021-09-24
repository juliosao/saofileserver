<?php
require_once('../../lib/Util.php');

use SfsException;
use app\App;
use filesystem\FileExistsException;
use filesystem\Directory;
use filesystem\RegularFile;
use filesystem\FileSystemObject;
use NotFoundException;

class Upload extends App
{
    
    public function __construct()
    {
        parent::__construct(1);
    }

    public function main($args) {
	    $ok=[];
	    $ko=[];
        $basedir=Cfg::get()->fso->basedir;
        error_log("Se quiere subir ficheros");
        error_log(json_encode($_FILES));
        if(count($_POST)==0)
        {
            throw new SfsException("File too long");
        }

        $path= urldecode($_POST["path"]);

        $dest= FileSystemObject::joinPath($basedir,$path);
        $dir= new Directory($dest);

        error_log("DEST:".$dest);

        if(!$dir->exists())
        {
            throw new NotFoundException($path);
        }

        $files=$_FILES['files'];
        foreach($files['error'] as $idx => $err )
        {
            if($err != null)
            {
                //$this->exitApp(false,"Cannot upload ".$files['name'][$idx]);
                throw new SfsException("Cannot upload:".$files['name'][$idx]);
            }

            $newPath = FileSystemObject::joinPath($dest,$files['name'][$idx]);
            $fsoFile=new RegularFile($newPath);
            if($fsoFile->exists()) 
            {
                throw new FileExistsException($files['name'][$idx]);
            }

            error_log("TMPNAME:".json_encode($files['tmp_name'][$idx]));
            error_log("NEWNAME:".json_encode($newPath));
            error_log("FILE:".json_encode($fsoFile));
            
            if(!move_uploaded_file($files['tmp_name'][$idx],$newPath))
		    {
                throw new SfsException("Cannot move:".$files['name'][$idx]);
		    }
		}            
        
        echo json_encode(true);
	}
}

$b= new Upload();
$b->run();
