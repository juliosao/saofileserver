<?php

// Represents a single mod in a page
class Mod{
	protected static $loadedMods = array();
	protected static $observers = array();
	protected $argv;
	protected $scripts;
	protected $depends;
	protected $styles;

	//Constructor
	function __construct($argv)
	{
		$this->name=$argv[0];
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
	
	//Loads a mod by name
	public static function load($mod){
			if(isset(self::$loadedMods[$mod]))
				return;
			
			$className='';

			// Adds mod lib path to include_path
			if(!is_dir(__DIR__."/../mod/$mod"))
			{
				throw new Exception("Mod not found: $mod");
			}
			
			// Load the mod
			if(file_exists(__DIR__."/../mod/$mod/main.php"))
			{
				require(__DIR__."/../mod/$mod/main.php");
				$className='Mod_'.$mod;
			    if(!class_exists($className))
			    {
			        throw new Exception("Mod not found: $mod");
			    }				
			    $result = new $className(func_get_args());
		
				// Loads mod dependencies
				foreach($result->getDependencies() as $dep)
				{
					self::load($dep);
				}
			}
			else
			{
				$result = new Mod(func_get_args());
			}
			
			self::$loadedMods[$mod]=$result;

			// Adds mod lib path to include_path
			if(is_dir(__DIR__."/../mod/$mod/lib"))
			{
				set_include_path(get_include_path().PATH_SEPARATOR.__DIR__."/../mod/$mod/lib/");
			}

			// Notifies to observers
            foreach(self::$observers as $observer)
            {
                $observer->onModLoaded($result);
            }

			return $result;
	}

	public static function addObserver(ModLoadObserver $observer)
	{
		self::$observers[]=$observer;
	}
	
}
