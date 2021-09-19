<?php

namespace filesystem;

use SfsException;

/**
 * Description of Directory
 *
 * @author julio
 */
class Directory extends FileSystemObject {
    //put your code here
    public function __construct($path)
    {
        parent::__construct($path);
    }
    
    public function mkdir($newdir,$force=false)
    {
        $objDir = $this->getChild($newdir);
        if($objDir instanceof RegularFile)
        {
            throw new FileExistsException($newdir);
        }
        elseif($objDir instanceof Directory)
        {
            return $objDir;
        }

        $res= mkdir($objDir->path,0777,$force);
        if($res==false)
        {
            throw new SfsException($objDir->path);
        }

        return new Directory($objDir);
    }
    
    public function exists()
    {
        return is_dir($this->path);
    }
    

    public function childDirs()
    {
        $result=[];
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
        $result=[];
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
    public function getChild($relativePath)
    {
        return FileSystemObject::fromPath(FileSystemObject::joinPath($this->path,$relativePath));
    }

    public function getFreeSpace()
    {
        return disk_free_space($this->path);
    }
    
    public function getTotalSpace()
    {
        return disk_total_space($this->path);
    }

    public function copyTo($newPath)
    {
        if ($this->exists()) {

            mkdir($newPath);

            $dirs=$this->childDirs();
            foreach($dirs as $d)
            {            
                $d->copyTo( FileSystemObject::joinPath($newPath,$d->getName()) );
            }

            $files=$this->childFiles();
            foreach($files as $f)
            {
                $d->copyTo( FileSystemObject::joinPath($newPath,$d->getName()) );
            }
        }
    }
}
