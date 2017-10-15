<?php
require_once('../../lib/Util.php');

class play extends app\App
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function run()
    {
        $basedir=Cfg::get()->fso->basedir;
        
        if(isset($_REQUEST['path'])) {
            $filename=str_replace('..','.', urldecode($_REQUEST['path']));
        }
        else{
            die("play what?");
        }

        $file=new fso\FSOFile(fso\FSO::joinPath($basedir,$filename));
        if(!$file->exists()) {
            die($filename." not found in ".$basedir);
        }

        set_time_limit(0);
        header('Content-Description: File Transfer');
        header('Content-Type: '.$file->mime());
        header('Content-Disposition: inline; filename="'.$file->getName().'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: '.$file->getSize());
        readfile($file->path);
        exit;
    }
}

$b= new play();
$b->run();