<?php

namespace filesystem;

define('FSODIR',0);
define('FSOFILE',1);

/**
 * Description of FileSystem
 *
 * @author julio
 */
abstract class FileSystemObject {
    public static $dirSeparator;
    public $path;
    public $error;
    public $type;

    public abstract function exists();
    public abstract function delete();

	/**
		\brief Constructor
		\warning DO NOT use directly
	*/
    protected function __construct($path) {
        $this->path=$path;
        $this->error=null;
        $this->type=is_dir($path) ? FSODIR : FSOFILE;
    }

	/**
		\fn getName();
		\brief Gets the name of a FileSystemObject Object
	*/
    public function getName()
    {
        return basename($this->path);
    }

	/**
		\fn getParent()
		\brief Gets the parent dir of a FileSystemObject Object
		\return A Directory representing the parent dir of a FileSystemObject Object
	*/
    public function getParent()
    {
        return new Directory(dirname($this->path));
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
		\brief Returns the relative path of a FileSystemObject from a base path
		\param $basePath Base path to obtain the relative path of the object
		\return The path of the FileSystemObject, searching from $basePath
	*/
	public function relativePath($basePath,$force=true)
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

    public function moveTo($newPath)
    {
        rename($path,$newPath);
    }

	/**
		\fn fromPath($path)
		\brief gets an FileSystemObject objecto from a filesystem path
		\param $path The path to use
		\return a FileSystemObject Object representing the path
	*/
    public static function fromPath($path)
    {
        error_log("Buscando $path");

        if(is_dir($path))
            return new Directory($path);
        else if(is_file($path))
            return new RegularFile($path);
        else
            return null;
    }


}

FileSystemObject::init();


