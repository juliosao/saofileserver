<?php

require_once('./lib/Mod.php');

class Mod_fso extends Mod{
	public function __construct($argv)
	{
		parent::__construct($argv);
		$this->scripts=array('mod/fso/js/fso.js');
	}
}

