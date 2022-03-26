<?php
require_once('../../lib/Util.php');

use app\Bundle;
use app\HTMLApp;

class Explorer extends HTMLApp
{
	function __construct()
	{
		parent::__construct();
		$this->title = 'SAO-Explorer';
		$this->scripts[] = 'js/fso.js';
		$this->scripts[] = 'js/fsoExplorer.js';
		$this->styles[] = 'styles/explorer.css';
	}

	function body($args)
	{
?>
		<div id="explorer" class="fso-explorer" ></div>
<?php
	}
}

$app = new Explorer();
$app->run();