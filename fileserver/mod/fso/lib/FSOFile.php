<?php
require_once("FSO.php");
require_once("FSODir.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of File
 *
 * @author julio
 */
class FSOFile extends FSO{
    function __construct($path) {
        parent::__construct(realpath($path));
    }
    
    function exists()
    {
        return is_file($this->path);
    }
    
    function getSize()
    {
        return filesize($this->path);
    }
    
    function icon()
    {
        $p = strrpos($this->path,'.');
        $ext= ($p===false) ? '' : substr($this->path,$p);

        //error_log($ext);

        switch(strtolower($ext))
        {
            case '.avi';
            case '.mov':
            case '.ogv':
            case '.mpg':
            case '.mpeg':
            case '.mp4':
                return 'img/icons/mov.svg';
            
            case '.raw':
            case '.jpg':
            case '.jpeg':
            case '.png':
            case '.svg':
            case '.bmp':
                return 'img/icons/jpg.svg';

            case '.mp3':
            case '.aac':
            case '.flac':
            case '.wav':
            case '.mid':
                return 'img/icons/mp3.svg';

            case '.doc':
            case '.docx':
            case '.odt':
                return 'img/icons/doc.svg';

            case '.pdf':
            case '.epub':
                return 'img/icons/epub.svg';

            default:
                return 'img/icons/fil.svg';
        }
    }
    
    public function delete()
    {
        if ($this->exists()) {
            $res= unlink($this->path);
            if($res==false)
            {
                $e=error_get_last();
                $this->error=json_encode($e);
                return false;
            }
            return true;
        } else {            
            return true;
        }
    }
}
