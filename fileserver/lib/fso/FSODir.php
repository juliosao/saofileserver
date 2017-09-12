<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 namespace fso;

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
	
	/**
		\fn isChild($fso)
		\briefs Returns if $fso is descendant of current dir
		\param fso FSO Object to check
		\return true fi fso is inside the current dir
	*/
	public function isChild($fso)
	{
		$prep = array();
        $realo= explode(self::$dirSeparator,$this->path);		
        $realp= explode(self::$dirSeparator,$fso->getParent()->path);

		// While paths components are equal unshift them
        while(count($realp) && count($realo) && $realp[0]== $realo[0] )
        {
            array_shift($realp);
            array_shift($realo);
        }
		
		if(count($realo)!=0)
			return False;
		
		return True;
	}
    
}
