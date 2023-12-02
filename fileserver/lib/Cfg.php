<?php

use app\App;

class Cfg
{
    static $default=null;

    function __construct($file=null)
    { 
        $data=[];

        if($file === null)
        {
            $data = ["app"=>[
                "name"=>"sfs",
                "main"=>"views/explorer/index.php",
                "setup"=>"views/setup/index.php"
            ],
            "bbdd"=>[
                "database"=>"mysql:host=localhost;dbname=saofileserver;charset=utf8",
                "user"=>"saofileserver",
                "pass"=>"saofileserver"
            ],
            "fso"=>[
                "basedir"=>"/var/lib/sfs"
            ]];
        }
        else
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
            }
            else
            {
                throw new Exception("Configuration not found");
            }
        }
        
        foreach($data as $k=>$v)
        {
            $this->$k = $v;
        }
    }    

    static function init()
    {
        if(file_exists(App::getAppPath('cfg/app.json')))
            self::$default = new Cfg(App::getAppPath('cfg/app.json'));
        else if(file_exists('/etc/opt/sfs/app.json'))
            self::$default = new Cfg('/etc/opt/sfs/app.json');
        else
            self::$default = new Cfg();
        
    }

    static function get()
    {
        return self::$default;
    }

}

Cfg::init();