<?php
require_once('../../lib/Util.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<?php HTMLApp::putHeaders('SAO-Player'); ?>
		<script type="text/javascript" src="../../styles/main/main.css"></script>
		<script type="text/javascript" src="js/user.js"></script>
		<link rel="stylesheet" href="styles/player.css">
		<script type="text/javascript">
		function loadUsers()
		{
			User.list(null);
		}
		</script>
	</head>
	<body onload="loadUsers()">
		<h1>Usuarios</h1>
		
	</body>
</html>
