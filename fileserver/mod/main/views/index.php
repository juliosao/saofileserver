<?php

class index extends HTMLApp
{
	public function __construct()
	{
		parent::__construct();

		$this->title='Archivos';
		Mod::load('main');
	}

	public function putBody()
	{
		?>
		<div id="explorer" class="fso-explorer" />
		<?php
	}
}
