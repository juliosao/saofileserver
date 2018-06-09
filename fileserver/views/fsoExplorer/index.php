<?php

require_once('../../lib/Util.php');

class Index extends app\HTMLApp
{
	public function __construct()
	{
		parent::__construct(1);

		$this->title='Archivos';
		$this->addScript('../../js/fso/fso.js');
		$this->addScript('../../js/fsoExplorer/fsoExplorer.js');
		$this->addScript('../../js/fsoPlayer/fsoExplorerPlayer.js');
		$this->addScript('../../bundles/user/js/explorer.js');

		$this->addStyle('../../styles/fsoExplorer/fsoExplorer.css');
		$this->addStyle('../../styles/fsoPlayer/fsoExplorerPlayer.css');
		$this->addStyle('../../bundles/user/styles/explorer.css');
		$this->addStyle('../../styles/main/main.css');
	}

	public function putBody()
	{
		?>
		<div id="toolbar" class="app-toolbar" />
		<div id="explorer" class="fso-explorer" />
		<?php
	}
}

$index = new Index();
$index->run();

