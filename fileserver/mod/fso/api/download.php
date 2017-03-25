<?php
    require_once("../lib/FSO.php");
    require_once("../lib/FSODir.php");
    require_once("../lib/FSOFile.php");
    require_once('../../../cfg/fso.cfg');

    if(isset($_REQUEST['path'])) {
        $filename=str_replace('..','.', urldecode($_REQUEST['path']));
    }
    else{
		die("download what?");
	}

    $file=new FSOFile(fso::joinPath($basedir,$filename));
    if(!$file->exists()) {
        die($filename." not found in ".$basedir);
    }

    header('Content-Description: File Transfer');
    header('Content-Type: '.$file->mime());
    header('Content-Disposition: attach; filename="'.$file->getName().'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $file->getSize());
    readfile($file->path);
    exit;
