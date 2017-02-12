<?php

require_once('FSODir.php');
require_once('FSOFile.php');

/**
 * Description of FileSystem
 *
 * @author julio
 */
abstract class FSO {    
    public static $dirSeparator;
    public $path;
    public $error;
    
    public abstract function exists();    
    public abstract function delete();
    
    public function __construct($path) {
        $this->path=$path;
        $this->error=null;
    }

    public function getName()
    {
        return basename($this->path);
    }
        
    public function getParent()
    {
        return new FSODir(dirname($this->path));
    }
    
    static function init()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            self::$dirSeparator='\\';
        } else {
            self::$dirSeparator='/';
        }
    }       
    
    public static function joinPath($path1,$path2)
    {
        return $path1.self::$dirSeparator.$path2;
    }
    
    public static function pathFromPath($path, $ori, $force=true)
    {
        $prep = array();
        $realp= explode(self::$dirSeparator,realpath($path));
        $realo= explode(self::$dirSeparator,realpath($ori));

        while(count($realp) && count($realo) && $realp[0]== $realo[0] )
        {
            array_shift($realp);
            array_shift($realo);
        }
        
        
        if(count($realo)>0 && $force==false)
        {
            while(count($realo)>0 )
            {
                array_unshift($prep, '..');                
                array_shift($realo);               
            }

            
            foreach($prep as $p)
            {
                array_unshift($realp, $p);
            }            
        }
        
        return implode(self::$dirSeparator,$realp); 
    }
    
    public static function fromPath($path)
    {
        error_log("Buscando $path");
        
        if(is_dir($path))
            return new FSODir($path);
        else if(is_file($path))
            return new FSOFile($path);
        else
            return null;
    }
}

FSO::init();


