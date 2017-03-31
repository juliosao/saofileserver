<?php

//Represents a http callable mini-application
abstract class App {
    protected $result;
    protected $loadedMods;

    public function __construct()
    {
        $this->result=array();
        $this->loadedMods=array();
    }

    //Runs JSONApp
    public abstract function run();

    	//Loads a mod by name
	public function loadMod($mod,$observer=null){
		try{
			if(isset($this->loadedMods[$mod]))
				return $this->loadedMods[$mod];

			// Load the mod
			require_once(__DIR__."/../mod/$mod/main.php");
			$className='Mod_'.$mod;
            if(!class_exists($className))
            {
                throw new Exception("Mod not found: $mod");
            }

			$result = new $className(func_get_args());
			$this->loadedMods[$mod]=$result;

			// Loads mod dependencies
			foreach($result->getDependencies() as $dep)
			{
				$this->loadMod($dep,$observer);
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

	public function exitApp($msg)
	{
		die($msg);
	}
}