<?php

namespace filesystem;

/**
 * Description of Directory
 *
 * @author julio
 */
class Directory extends FileSystemObject {
    //put your code here
    public function __construct($path)
    {
        parent::__construct(realpath($path));
    }
    
    public function mkdir($newdir,$force=false)
    {
        $newPath=FileSystemObject::joinPath($this->path,$newdir,true);

        $present=FileSystemObject::fromPath($newPath);
        if($present!==null)
        {
            if($force==false || !$present instanceof Directory)
            {
                throw new SfsException(error_get_last());
            }

            return new Directory($newPath);;
        }

        $res= mkdir($newPath,0777,true);
        if($res==false)
        {
            throw new SfsException($newPath);
        }

        return new Directory($newPath);
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
            
            $p=FileSystemObject::joinPath($this->path,$nombre);
            if(is_dir($p)){
                $result[]=new Directory($p);            
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
            $p=FileSystemObject::joinPath($this->path,$nombre);
            if(is_file($p)){
                $result[]=new RegularFile($p);            
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
                if($ret==false)
                {
                    $this->error=join('\n',error_get_last());
                    return false;
                }
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
		\param fso FileSystemObject Object to check
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

    public function getFreeSpace()
    {
        return disk_free_space($this->path);
    }
    
    public function getTotalSpace()
    {
        return disk_total_space($this->path);
    }

    
}
