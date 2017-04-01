<?php
require('cfg/fso.cfg');

class index extends HTMLApp
{
	function __construct()
	{
		parent::__construct();
		$this->loadMod('fsoPlayer');
		$this->title='player';
		$this->file=urldecode($_REQUEST['file']);
	}

	function putBody()
	{
		?>
		<div class="fso-player-container">
			<div class="fsoplayer-toolbar">
				<h1><?=htmlentities($this->file)?></h1>
			</div>
			<div class="fso-player" id="player" data-src="<?=htmlentities($this->file)?>"/>	
		</div>
		<?php
	}
}