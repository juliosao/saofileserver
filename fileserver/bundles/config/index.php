<?php
require_once('../../lib/Util.php');
Auth::checkSession();

?>
<!DOCTYPE html>
<html>
	<head>
		<?php HTMLApp::putHeaders('Usuario actual'); ?>		
        <link rel="stylesheet" href="../../styles/main.css"></script>
        <link rel="stylesheet" href="../explorer/styles/fsoExplorer.css"></script>
        <link rel="stylesheet" href="styles/config.css"></script>		
	</head>
	<body>
		<h1>Configuracion</h1>
		<div class="w3-container">
            <ul class="w3-ul w3-border fsoexplorer-list">
                <li class="w3-padding" onclick="App.goBundle('users');"><div class="fsoexplorer-icon config-users"></div><div class="fsoexplorer-name">Configurar usuarios</div></li>
            </ul>
            
        </div>
	</body>
</html>