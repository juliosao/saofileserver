<?php

require_once('./lib/Mod.php');

class Mod_fsoExplorer extends Mod{
	public function __construct($argv)
	{
		parent::__construct($argv);
		$this->scripts=array('mod/fsoExplorer/js/fsoExplorer.js');
		$this->styles=array('css/fso.css');
		$this->depends=array('fso');
	}
}
