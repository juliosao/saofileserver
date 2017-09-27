<?php

require_once('../../lib/Util.php');

class Index extends app\HTMLApp
{
	public function __construct()
	{
		parent::__construct(1);

        $usr=$_REQUEST['usr'];

		$this->title='Usuario';
		$this->addStyle('../../styles/fsoExplorer/fsoExplorer.css');
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

