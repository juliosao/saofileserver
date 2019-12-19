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
		<div class="container">
            <table class="table">
                <thead>
                    <tr><th>Elemento</th><th></th></tr>                    
                </thead>
                <tbody>
                    <tr onclick="App.goBundle('users');"><td><a class="fsoexplorer-icon config-users"></td><td>Configurar usuarios</td></tr>
                </tbody>
            </table>
        </div>
	</body>
</html>