<?php

require_once('./lib/Mod.php');

class Mod_fsoPlayer extends Mod{
	public function __construct($argv)
	{
		parent::__construct($argv);
		$this->scripts=array('mod/fsoPlayer/js/fsoPlayer.js');
		$this->styles=array('mod/fsoPlayer/css/fsoPlayer.css');
		$this->depends=array('fso');
	}
}
