<?php 

class Mod{
	public static function load($mod){
		try{
			require_once("mod/$mod/main.php");
			$result = new $mod(func_get_args());
			return $result;
		}
		catch(Exception $e){
			return null;
		}
	}
	
	private $argv;
	private $scripts;
	private $apis;
	
	public __construct($argv)
	{
		$this->argv=$argv;
		$this->scripts=array();
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
}
