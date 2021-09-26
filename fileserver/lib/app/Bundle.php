<?php

namespace app;

use \database\Database;
use \database\DBObject;

class Bundle extends DBObject
{
	static $keys=array('bundle');
    static $fields=array('bundle','path','enabled');
    static $table='bundles';
    static $onNotFound='auth\BundleNotFoundException';
    //Mandatory
    static $selectQry = null;
	static $fieldsEnum = null;
	static $insert=null;
	static $update=null;
	static $delete=null;
    
    public $bundle;    
    public $path;
    public $enabled;

	static function init()
	{
		Bundle::$db = Database::getInstance();
	}

    function load($for)
    {
        $path = sprintf('%s/bundles/%s/',App::getAppPath(),$this->bundle);

        if(!file_exists($path.'cfg.json'))
            throw new InvalidBundleException($this->bundle);

        $cfg = json_decode(file_get_contents($path.'cfg.json'));
        if(isset($cfg->$for))
            return $cfg->$for;
        
        return null;
    }

    function equals($obj)
    {
        if(! $obj instanceof Bundle )
            return false;
        
        if($obj->bundle != $this->bundle)
            return false;

        return true;
    }

    static  function selectQry()
    {
        return "SELECT bundle,path,enabled FROM bundles";
    }

    static function getQry()
    {
        return "SELECT bundle,path,enabled FROM bundles WHERE bundle=? LIMIT 1";
    }
    	
    static function insertQry()
    {
        return "INSERT INTO bundles (bundle,path,enabled) VALUES ( :bundle, :path, :enabled)";
    }

    static function updateQry()
    {
        return "UPDATE bundles SET path=:path,enabled=:enabled WHERE bundle=:bundle";
    }
    
    static function deleteQry()
    {
        return null;
    }

    function delete()
    {
        return static::$db->execute("DELETE FROM bundles WHERE bundle=?",array($this->bundle));
    }
}

