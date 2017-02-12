<?php
    require_once("../lib/FSO.php");
    require_once("../lib/FSODir.php");
    require_once("../lib/FSOFile.php");   
    require_once("../cfg/fso.cfg");
    
    $dirname='.';
    
    if(isset($_REQUEST['path'])) {
        $dirname=str_replace('..','.', base64_decode($_REQUEST['path']));
    }
    error_log("Ruta:"+$dirname);
    
    $dir=new FSODir(fso::joinPath($basedir,$dirname));
    if(!$dir->exists()) {
        die(json_encode(false));
    }

    $dirs=array();
    $files=array();
    
    $c=$dir->childDirs();    
    foreach($c as $d)
    {
        $link=FSO::pathFromPath($d->path, $basedir);
        $dirs[$d->getName()]=array('name'=>$d->getName(),'link'=>base64_encode($link));
    }
    
    $c=$dir->childFiles();    
    foreach($c as $f)
    {
        $link=FSO::pathFromPath($f->path, $basedir);
        $files[$f->getName()]=array('name'=>$f->getName(),'link'=>base64_encode($link),'extension'=>$f->extension());
    }

    ksort($dirs);
    ksort($files);
    die(json_encode(array('ok'=>true,'path'=>$dir,'dirs'=>$dirs,'files'=>$files)));
?>
