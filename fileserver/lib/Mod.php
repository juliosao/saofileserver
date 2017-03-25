<?php

interface ModLoadObsserver{
	public function onModLoaded($mod);
}

// Represents a single mod in a page
class Mod{
	public static $loadedMods = array();

	//Loads a mod by name
	public static function load($mod,$observer=null){
		try{
			if(isset(self::$loadedMods[$mod]))
				return self::$loadedMods[$mod];

			// Load the mod
			require_once(__DIR__."/../mod/$mod/main.php");
			$className='Mod_'.$mod;
			$result = new $className(func_get_args());
			$loadedMods[$mod]=$result;

			// Loads mod dependencies
			foreach($result->getDependencies() as $dep)
			{
				self::load($dep,$observer);
			}

			// Loads mod librarys
			foreach($result->getLibs() as $lib)
			{
				require_once(__DIR__."/../mod/$mod/lib/$lib");
			}

            if($observer!==null)
                $observer->onModLoaded($result);

			return $result;
		}
		catch(Exception $e){
			return null;
		}
	}

	protected $argv;
	protected $scripts;
	protected $libs;
	protected $depends;
	protected $styles;

	//Constructor
	function __construct($argv)
	{
		$this->argv=$argv;
		$this->scripts=array();
		$this->styles=array();
		$this->apis=array();
		$this->depends=array();
		$this->libs=array();
	}

	public function getScripts()
	{
		return $this->scripts;
	}

	public function getApis()
	{
		return $this->apis;
	}

	public function getStyles()
	{
		return $this->styles;
	}

	public function getDependencies()
	{
		return $this->depends;
	}

	public function getLibs()
	{
		return $this->libs;
	}

	public function __toString()
    {
		return '{MOD}';
	}
}
