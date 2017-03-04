<?php
    require_once("../../fso/lib/FSO.php");
    require_once("../../fso/lib/FSODir.php");
    require_once("../../fso/lib/FSOFile.php");
    require_once("../../fso/cfg/fso.cfg");

    if(isset($_REQUEST['path'])) {
        $filename=str_replace('..','.', $_REQUEST['path']);
    }
    else{
		die("download what?");
	}

    $file=new FSOFile(fso::joinPath($basedir,$filename));
    error_log($file->path);

    if(!$file->exists()) {
        die($filename." not found in ".$basedir);
    }

    header('Content-Description: File Transfer');
    header('Content-Type: '.$file->mime());
    header('Content-Disposition: inline; filename="'.$file->getName().'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $file->getSize());
    readfile($file->path);
    exit;
