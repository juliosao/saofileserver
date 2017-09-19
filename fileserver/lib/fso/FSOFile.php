<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 namespace fso;
 
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

    function extension()
    {
        $p = strrpos($this->path,'.');
        $ext= ($p===false) ? '' : substr($this->path,$p+1);

        return $ext;
    }

    function mime()
    {
	//return mime_content_type($this->path);
	$fi = finfo_open($this->path);
	$ret = finfo_file($fi);
	finfo_close($fi);
	return $ret;
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
