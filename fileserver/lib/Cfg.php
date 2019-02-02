<?php

class Cfg
{
    static $default=null;

    function __construct($file)
    {    
        $cfg=file_get_contents($file);
        
        if($cfg)
        {
            //self::$default=json_decode($cfg);
            $data=json_decode($cfg);

            switch (json_last_error()) 
            {
                case JSON_ERROR_NONE:
                    break;
                case JSON_ERROR_DEPTH:
                    error_log('Cannot load cfg - Maximum stack depth exceeded');
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    error_log('Cannot load cfg - Underflow or the modes mismatch');
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    error_log('Cannot load cfg - Unexpected control character found');
                    break;
                case JSON_ERROR_SYNTAX:
                    error_log('Cannot load cfg - Syntax error, malformed JSON');
                    break;
                case JSON_ERROR_UTF8:
                    error_log('Cannot load cfg - Malformed UTF-8 characters, possibly incorrectly encoded');
                    break;
                default:
                    error_log('Cannot load cfg - Unknown error');
                    break;
            }

            foreach($data as $k=>$v)
            {
                $this->$k = $v;
            }
        }
        else
        {
            throw new Exception("Configuration not found");
        }      
    }    

    static function init()
    {
        self::$default = new Cfg(App::getAppPath('cfg/app.json'));
        
    }

    static function get()
    {
        return self::$default;
    }

}

Cfg::init();