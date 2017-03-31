<?php

class Mod_fsoPlayer extends Mod{
	public function __construct($argv)
	{
		parent::__construct($argv);
		
		$this->depends=array('fso','fsoExplorer');
		$this->scripts=array('mod/fsoPlayer/js/fsoExplorerPlayer.js','mod/fsoPlayer/js/fsoPlayer.js');
		$this->styles=array('mod/fsoPlayer/css/fsoPlayer.css');
	}
}
