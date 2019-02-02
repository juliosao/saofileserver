<?php

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
        if(!is_file($this->path))
            return -1;

        $st= filesize($this->path);
        error_log('ENCONTRADO:'.$st);

        if($st<0)
        {
            $f = fopen($this->path,"rb");            
            
            $len=1048576;
            $r=1;
            $st=PHP_INT_MAX - 1;

            fseek($f,$st);
            while(!feof($f))
            {
                $r=fread($f,$len);
                //error_log('RECALCULANDO:+'.$len);
                $st = bcadd($st, $len);                
            }
            $st = bcsub($st, $len);
            $st = bcadd($st, strlen($r));

            
            fclose($f);
        }
        return $st;
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
        $fi = finfo_open(FILEINFO_MIME);
        if($fi!==false)
        {
            $ret = finfo_file($fi,$this->path);
            finfo_close($fi);
            return $ret;
        }  
        else
        {
            return 'application/octet-stream';
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
