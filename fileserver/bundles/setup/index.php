<?php

require('../../lib/Util.php');

?>
<!DOCTYPE html>
<html>
	<head>
		<?php HTMLApp::putHeaders('SAO-Player'); ?>
		<script type="text/javascript" src="js/player.js"></script>
		<link rel="stylesheet" href="styles/player.css">
	</head>
    <body>
        <div class="jumbotron col-sm-6 mx-auto">        
    		<h1>Setup</h1>    
<?php
if( isset($_REQUEST['error'] ))
{
?>
            <div class="alert alert-warning" role="alert">
                <p>Por favor, rellene todos los parametros</p>
            </div>
<?php
}
?>        
            <form class="form" method="POST" action="views/setup.php">
                <div class="form-group"><label for="usr">Usuario de la BBDD:</label><input class="form-control" name="usr" /></div>
                <div class="form-group"><label for="pwd">Password de la BBDD:</label><input class="form-control" name="pwd" type="password"/></div>
                <div class="form-group"><label for="appUsr">Usuario de la App:</label><input class="form-control" name="appUsr" /></div>
                <div class="form-group"><label for="appPwd">Password de la App:</label><input class="form-control" name="appPwd" type="password"/></div>
                <div class="form-group"><input class="btn btn-primary" type="submit" /><input class="btn" type="reset"/></div>
            </form>
        </div>
    </body>
</html>