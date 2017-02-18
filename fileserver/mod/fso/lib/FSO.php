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
    
	/**
		\brief Constructor
		\warning DO NOT use directly
	*/
    protected function __construct($path) {
        $this->path=$path;
        $this->error=null;
    }

	/**
		\fn getName();
		\brief Gets the name of a FSO Object
	*/
    public function getName()
    {
        return basename($this->path);
    }

	/**
		\fn getParent()
		\brief Gets the parent dir of a FSO Object
		\return A FSODir representing the parent dir of a FSO Object
	*/
    public function getParent()
    {
        return new FSODir(dirname($this->path));
    }
    
	/**
		\fn init()
		\brief Initializes lib		
	*/
    static function init()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            self::$dirSeparator='\\';
        } else {
            self::$dirSeparator='/';
        }
    }       
    
	/**
		\fn joinPath($path1,$path2)
		\brief Join two path components
		\param $path1 The "parent" path
		\param $path2 The "child" path
		\return $path1 and $path2, joined with $dirSeparator, if needed
	*/
    public static function joinPath($path1,$path2)
    {
        return $path1.self::$dirSeparator.$path2;
    }
    
	/**
		\fn pathFromPath($path, $ori, $force=true)
		\brief Returns the relative path of path from ori.
		\param $path The path to process
		\param $ori The base path
		\param $force If true, forces the result of the function to be in ori
		\return The relative path of path from ORI
	*/
    public static function pathFromPath($path, $ori, $force=true)
    {
        $prep = array();
        $realp= explode(self::$dirSeparator,realpath($path));
        $realo= explode(self::$dirSeparator,realpath($ori));

		// While paths components are equal unshift them
        while(count($realp) && count($realo) && $realp[0]== $realo[0] )
        {
            array_shift($realp);
            array_shift($realo);
        }
        
        // If not force we need to add the '..' dir any times we need it
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
	
	/**
		\fn relativePath($from)
		\brief Returns the relative path of a FSO from a base path
		\param $basePath Base path to obtain the relative path of the object
		\return The path of the FSO, searching from $basePath
	*/
	public function relativePath($basePath)
	{
		$prep = array();
        $realp= explode(self::$dirSeparator,$this->path);
        $realo= explode(self::$dirSeparator,realpath($basePath));

		// While paths components are equal unshift them
        while(count($realp) && count($realo) && $realp[0]== $realo[0] )
        {
            array_shift($realp);
            array_shift($realo);
        }
        
        // If not force we need to add the '..' dir any times we need it
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
    
	/**
		\fn fromPath($path)
		\brief gets an FSO objecto from a filesystem path
		\param $path The path to use 
		\return a FSO Object representing the path
	*/
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


