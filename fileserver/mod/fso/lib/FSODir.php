<?php
require_once("FSO.php");
require_once("FSOFile.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Directory
 *
 * @author julio
 */
class FSODir extends FSO {
    //put your code here
    public function __construct($path)
    {
        parent::__construct(realpath($path));
    }
    
    public function make()
    {
        if($this->exists()) {
            return true;
        } else {
            $res= mkdir($this->path,0777,true);
            if($res==false)
            {
                $this->error=error_get_last();
                return false;
            }
            return true;
        }
    }
    
    public function exists()
    {
        return is_dir($this->path);
    }
    

    public function childDirs()
    {
        $result=array();
        //error_log("CHILDIRS:".$this->path);
        
        $r=opendir($this->path);
        if(!$r)
            return $result;
        
        
        while(false !== $nombre=readdir($r))
        {
            if($nombre=='.'||$nombre=='..')
                continue;
            
            $p=fso::joinPath($this->path,$nombre);
            if(is_dir($p)){
                $result[]=new FSODir($p);            
            }
        }
        
        return $result;
    }
    
    public function childFiles()
    {
        $result=array();
        //error_log("CHILFILES:".$this->path);
        
        $r=opendir($this->path);
        if(!$r)
            return $result;
        
        
        while(false !== $nombre=readdir($r))
        {          
            $p=fso::joinPath($this->path,$nombre);
            if(is_file($p)){
                $result[]=new FSOFile($p);            
            }
        }
        
        return $result;
    }
    
    public function delete()
    {
        $ret=true;
        if ($this->exists()) {
            
            $dirs=$this->childDirs();
            foreach($dirs as $d)
            {
                error_log("Entrando a ".$d->getName());
                $ret=$d->delete();
            }
            
            $files=$this->childFiles();
            foreach($files as $f)
            {
                error_log("Entrando a ".$f->getName());
                $ret=$f->delete();
            }
            
            $res= rmdir($this->path);
            if($res==false)
            {
                $this->error=join('\n',error_get_last());
                return false;
            }
            return true;
        } else {            
            return true;
        }
    }
    
}
