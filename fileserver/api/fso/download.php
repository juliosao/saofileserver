<?php
require_once('../../lib/Util.php');

class download extends App
{
    public function __construct()
    {
        parent::__construct();
    }

    function run()
    {
        $basedir=Cfg::get()->fso->basedir;
        
        if(isset($_REQUEST['path'])) {
            $filename=str_replace('..','.', urldecode($_REQUEST['path']));
        }
        else{
            die("download what?");
        }

        $file=new fso\FSOFile(fso\FSO::joinPath($basedir,$filename));
        if(!$file->exists()) {
            die($filename." not found in ".$basedir);
        }

        set_time_limit(0);
        header('Content-Description: File Transfer');
        header('Content-Type: '.$file->mime());
        header('Content-Disposition: attach; filename="'.$file->getName().'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $file->getSize());
        readfile($file->path);
        exit;
    }
}

$b=new download();
$b->run();