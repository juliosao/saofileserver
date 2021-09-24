<?php
require_once('../../lib/Util.php');

use app\HTMLApp;

$file=urldecode($_REQUEST['file']);
$mode=isset($_REQUEST['data-mode']) ? $_REQUEST['data-mode'] : 'audio';
?>
<!DOCTYPE html>
<html>
	<head>
		<?php HTMLApp::putHeaders('SAO-Player'); ?>
		<script type="text/javascript" src="js/player.js"></script>
		<link rel="stylesheet" href="styles/player.css">
	</head>
	<body>
		<div class="w3-container fso-player" id="player" data-src="<?=htmlentities($file)?>" data-mode="<?=$mode ?>">
		</div>
	</body>
</html>