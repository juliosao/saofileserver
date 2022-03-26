<?php

namespace filesystem;

use MethodNotAllowedException;

define('FSODIR',0);
define('FSOFILE',1);

/**
 * Description of FileSystem
 *
 * @author julio
 */
class FileSystemObject {
    public static $dirSeparator;
    public $path;


	/**
	 * \brief Constructor
	 * \warning DO NOT use directly
	*/
    protected function __construct($path)
    {
        if($path instanceof FileSystemObject)
            $this->path=$path->path;
        else
            $this->path=FileSystemObject::realPath($path);
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
     * \fn isChildOF(Directory $fso)
     * \brief Returns true when current FSO is child of given directory
     */
    public function isChildOF(Directory $fso)
	{
        $len=strlen($fso->path);
        return substr($this->path,0,$len)==$fso->path;
    }

    /**
		\fn relativePath($from)
		\brief Returns the relative path of a FileSystemObject from a base path
		\param $basePath Base path to obtain the relative path of the object
		\return The path of the FileSystemObject, searching from $basePath
	*/
	public function getRelativePath($basePath)
	{
        if($basePath instanceof FileSystemObject)
            $basePath = $basePath->path;
        else
            $basePath = FileSystemObject::realPath($basePath);

        $pos = strlen($basePath);
        if(substr($this->path,0,$pos)!==$basePath)
        {
            throw new FileSystemException("Path {$this->path} is not a child of $basePath");
        }
        return substr($this->path,$pos);
    }

    public function moveTo($newPath)
    {
        rename($this->path,$newPath);
    }

    public function exists()
    {
        return file_exists($this->path);
    }

    public function delete()
    {
        throw new MethodNotAllowedException();
    }

    public function copyTo($unused)
    {
        throw new MethodNotAllowedException();
    }

    /**
     * Returns FSO Exception
     */
    public function extension()
    {
        $name = basename($this->path);
        $pos = strrpos($name,'.');
        if($pos === false)
             return '';
        return substr($name,$pos+1);
    }

    /**
     * Returns MIME type from FSO
     */
    public function mime()
    {
        return mime_content_type($this->path);
    }


    public static function realPath($path)
    {
        $tmp=explode(FileSystemObject::$dirSeparator,$path);
        $tmp2=[];

        foreach($tmp as $component)
        {
            if($component=='..')
            {
                if(count($tmp2)==0)
                    throw new FileSystemException($path);

                array_pop($tmp2);
            }
            else if($component!='')
            {
                array_push($tmp2,$component);
            }
        }
        return FileSystemObject::$dirSeparator.implode(FileSystemObject::$dirSeparator,$tmp2);
    }

	/**
		\fn fromPath($path)
		\brief gets an FileSystemObject objecto from a filesystem path
		\param $path The path to use
		\return a FileSystemObject Object representing the path
	*/
    public static function fromPath($path)
    {
        if(is_dir($path))
            return new Directory($path);
        else if(is_file($path))
            return new RegularFile($path);
        else
            return new FileSystemObject($path);
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



}

FileSystemObject::init();
