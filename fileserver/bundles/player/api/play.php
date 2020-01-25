<?php
require_once('../../../lib/Util.php');

class play extends App
{
    private $buffer = 102400;

    public function __construct()
    {
        parent::__construct(0);

        $basedir=Cfg::get()->fso->basedir;
        
        if(isset($_REQUEST['path'])) 
        {
            $filename=str_replace('..','.', urldecode($_REQUEST['path']));
        }
        else
        {
            error_log("Fichero no definido");            
            die("play what?");
        }

        $this->file=new RegularFile(FileSystemObject::joinPath($basedir,$filename));
        if(!$this->file->exists()) 
        {
            error_log("Fichero no encontrado");            
            die($filename." not found in ".$basedir);
        }

        if (!($this->stream = fopen($this->file->path, 'rb'))) 
        {
            error_log("No se pudo abrir fichero");            
            die('Could not open stream for reading');
        }

        $this->start=0;
        $this->size=$this->file->getSize();
        $this->end=$this->size-1;

        if(isset($_SERVER['HTTP_RANGE']))
        {
            error_log("RANGO DEFINIDO:".$_SERVER['HTTP_RANGE']);
            list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            if (strpos($range, ',') !== false) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $this->start-$this->end/$this->size");
                error_log("Rango no posible");                
                die();
            }
            
            if ($range[0] == '-') 
            {
                $this->start = $this->size - substr($range, 1);
            }
            else
            {
                $range = explode('-', $range);
                $this->start = $range[0];
                if(isset($range[1]) && is_numeric($range[1]))
                {
                    $this->end = $range[1];
                }
            }

            if($this->start > $this->end || $this->start > $this->size-1 || $this->end > $this->size-1)
            {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $this->start-$this->end/$this->size");
                error_log("Rango no posible");
                die();
            }

        }

        error_log("Archivo multimedia solicitado");
        error_log("Inicio".$this->start);
        error_log("Fin".$this->end);
        error_log("Tamaño".$this->size);


    }

    function __destruct()
    {
        fclose($this->stream);
    }


    function putHeader()
    {
        if (isset($_SERVER['HTTP_RANGE']))
        {
            $length = $this->end - $this->start + 1;
            header('HTTP/1.1 206 Partial Content');
            header("Content-Length: ".$length);
            header("Content-Range: bytes $this->start-$this->end/".$this->size);
        }
        else
        {
            header('Content-Length: '.$this->size);
        }

        header('Content-Description: File Transfer');
        header('Content-Type: '.$this->file->mime());
        header('Content-Disposition: inline; filename="'.$this->file->getName().'"');
        header('Expires: '.gmdate('D, d M Y H:i:s', time()+60000) . ' GMT'); //2592000
        header("Cache-Control: max-age=60000, public"); //2592000
        header('Pragma: public');
        header("Accept-Ranges: 0-".$this->size-1);

        $this->setBuffered(false);
    }

    function putData()
    {
        $i = $this->start;
        set_time_limit(0);        

        fseek($this->stream,$this->start);
        while(!feof($this->stream) && $i <= $this->end) {            

            $bytesToRead = $this->buffer;
            if(($i+$bytesToRead) > $this->end) {
                $bytesToRead = $this->end - $i + 1;
            }

            $data = fread($this->stream, $bytesToRead);
            echo $data;
            flush();
            $i += $bytesToRead;
        }

    }

    function main()
    {
        $this->putHeader();
        $this->putData();
    }
}

$b= new play();
$b->run();