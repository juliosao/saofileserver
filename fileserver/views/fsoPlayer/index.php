<?php
require_once('../../lib/Util.php');

class index extends app\HTMLApp
{
	function __construct()
	{
		parent::__construct(1);
		$this->title='player';
		$this->file=urldecode($_REQUEST['file']);
		$this->addScript('../../js/fsoPlayer/fsoPlayer.js');
		$this->addStyle('../../styles/fsoPlayer/fsoPlayer.css');
	}

	function putBody()
	{
		?>
		<div class="fso-player" id="player" data-src="<?=htmlentities($this->file)?>">
		</div>
		<?php
	}
}

$b= new index();
$b->run();