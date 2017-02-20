<?php 

class Mod{
	public static function load($mod){
		try{
			require_once("mod/$mod/main.php");
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
	
	public function __construct($argv)
	{
		$this->argv=$argv;
		$this->scripts=array();
		$this->styles=array();
		$this->apis=array();
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
	
	public function __toString()
    {
		return '{MOD}';
	}
}
