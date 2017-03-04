<?php

require_once(__DIR__.'/../../lib/Mod.php');

class Mod_fso extends Mod{
	public function __construct($argv)
	{
		parent::__construct($argv);
		$this->scripts=array('mod/fso/js/fso.js');
		$this->libs=array('mod/fso/lib/FSO.php');
	}
}

