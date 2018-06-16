<?php
require_once('../../../lib/Util.php');

class index extends app\HTMLApp
{
	function __construct()
	{
		parent::__construct(1);
		$this->title='player';
		$this->file=urldecode($_REQUEST['file']);
		$this->mode=isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
		$this->addScript('../js/player.js');
		$this->addStyle('../styles/player.css');
	}

	function putBody()
	{
		?>
		<div class="fso-player" id="player" data-src="<?=htmlentities($this->file)?>" data-mode="<?=$this->mode ?>">
		</div>
		<?php
	}
}

$b= new index();
$b->run();