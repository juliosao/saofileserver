<?php

interface ModLoadObsserver{
	public function onModLoaded($mod);
}

// Represents a single mod in a page
class Mod{
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
