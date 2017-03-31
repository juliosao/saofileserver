<?php

class index extends HTMLApp
{
	public function __construct()
	{
		parent::__construct();

		$this->title='Archivos';
		$this->loadMod('fsoExplorer');
		$this->loadMod('fsoPlayer');
	}

	public function putBody()
	{
		?>
		<div id="explorer" class="fso-explorer" />
		<?php
	}
}
