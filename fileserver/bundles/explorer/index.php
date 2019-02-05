<?php
require_once('../../lib/Util.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<?php HTMLApp::putHeaders('SAO-Explorer'); ?>
		<script type="text/javascript" src="js/fso.js"></script>
		<script type="text/javascript" src="js/fsoExplorer.js"></script>
		<script type="text/javascript" src="../player/js/explorer.js"></script>
		<link rel="stylesheet" href="styles/fsoExplorer.css">
		<link rel="stylesheet" href="../player/styles/explorer.css">
	</head>
	<body>
		<div id="toolbar" class="app-toolbar" ></div>
		<div id="explorer" class="fso-explorer" ></div>
	</body>
</html>