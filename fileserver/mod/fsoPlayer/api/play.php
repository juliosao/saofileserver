<?php
	# Necesary phps
    require_once("../../fso/lib/FSO.php");
    require_once("../../fso/lib/FSODir.php");
    require_once("../../fso/lib/FSOFile.php");
    require_once("../../fso/cfg/fso.cfg");

    # Gets file
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

    # Sends file to player
    header('Content-Description: File Transfer');
    header('Content-Type: '.$file->mime());
    header('Content-Disposition: inline; filename="'.$file->getName().'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $file->getSize());
    readfile($file->path);
    exit;
