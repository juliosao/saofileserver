<?php

require(__DIR__.'/lib/Util.php');

class Index extends HTMLApp
{
	public function __construct()
	{
		parent::__construct();

		$this->title='Archivos';
		$this->addScript('js/fso/fso.js');
		$this->addScript('js/fsoExplorer/fsoExplorer.js');
		$this->addScript('js/fsoPlayer/fsoExplorerPlayer.js');
		$this->addStyle('styles/fsoExplorer/fsoExplorer.css');
		$this->addStyle('styles/fsoPlayer/fsoExplorerPlayer.css');
		$this->addStyle('styles/main/main.css');
	}

	public function putBody()
	{
		?>
		<div id="explorer" class="fso-explorer" />
		<?php
	}
}

$index = new Index();
$index->run();

