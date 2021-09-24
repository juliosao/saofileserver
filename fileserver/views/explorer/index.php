<?php
require_once('../../lib/Util.php');
use app\App;
use app\HTMLApp;

?>
<!DOCTYPE html>
<html>
	<head>
		<?php HTMLApp::putHeaders('SAO-Explorer'); ?>
		<script type="text/javascript" src="<?=App::getAppUrl('js/fso.js')?>"></script>
		<script type="text/javascript" src="<?=App::getAppUrl('js/fsoExplorer.js')?>"></script>
		<link rel="stylesheet" href="<?=App::getAppUrl('styles/explorer.css')?>">
	</head>
	<body>
		<div id="explorer" class="fso-explorer" ></div>
	</body>
</html>