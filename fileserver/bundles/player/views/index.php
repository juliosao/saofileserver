<?php
require_once('../../../lib/Util.php');

use app\Bundle;
use app\HTMLApp;

class MyApp extends HTMLApp
{
	function __construct()
	{
		parent::__construct();
		$this->title = 'SAO-Player';
		$this->scripts[] = 'js/fso.js';
		$this->scripts[] = 'bundles/player/js/player.js';
		$this->styles[] = 'bundles/player/styles/player.css';
		
		$this->file=urldecode($_REQUEST['file']);
		$this->mode=isset($_REQUEST['data-mode']) ? $_REQUEST['data-mode'] : 'audio';
	}

	function body($args)
	{
?>
		<div class="w3-container fso-player" id="player" data-src="<?=htmlentities($this->file)?>" data-mode="<?=$this->mode ?>">
<?php
	}
}

$app = new MyApp();
$app->run();
