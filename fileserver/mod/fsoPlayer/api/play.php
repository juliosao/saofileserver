<?php
require_once('cfg/fso.cfg');

class play extends App
{
    public function __construct()
    {
        parent::__construct();
        Mod::load('fso');
    }

    function run()
    {
        global $basedir;
        
        if(isset($_REQUEST['path'])) {
            $filename=str_replace('..','.', urldecode($_REQUEST['path']));
        }
        else{
            die("download what?");
        }

        $file=new FSOFile(FSO::joinPath($basedir,$filename));
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
