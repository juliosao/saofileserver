<?php

require_once(__DIR__.'/../../lib/Mod.php');


class Mod_main extends Mod{
	public function __construct($argv)
	{
		parent::__construct($argv);
		
		$this->styles=array('mod/main/css/main.css');	
		$this->depends=array('fsoExplorer','fsoPlayer');
	}
}