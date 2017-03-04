<?php

// Represents a single mod in a page
class Mod{
	//Loads a mod by name
	public static function load($mod){
		try{
			require_once(__DIR__."/../mod/$mod/main.php");
			$className='Mod_'.$mod;
			$result = new $className(func_get_args());
			return $result;
		}
		catch(Exception $e){
			return null;
		}
	}

	protected $argv;
	protected $scripts;
	protected $apis;
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
