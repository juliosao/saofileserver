<?php
require_once('../../lib/Util.php');
Auth::checkSession();

?>
<!DOCTYPE html>
<html>
	<head>
		<?php HTMLApp::putHeaders('Usuario actual'); ?>
		<script type="text/javascript" src="../../styles/main/main.css"></script>
		<script type="text/javascript" src="../users/js/user.js"></script>
		<link rel="stylesheet" href="styles/player.css">
		<script type="text/javascript">
        const currentUser = '<?=Auth::$current->id?>';
		function loadUser()
		{
			let usr=User.get(currentUser);
		}
		</script>
	</head>
	<body onload="loadUser()">
		<h1>Configuracion de usuario</h1>
		<div class="container">
            <input type="hidden" id="id" value="<?=Auth::$current->id?>">
            <div class="form">
                <div class="row">
                    <label class="col-md-3" for="name">Nombre</label>
                    <input class="input col-md-9" id="name" value="<?=Auth::$current->name?>" >
                </div>
                <div class="row">
                    <label class="col-md-3" for="mail">Mail</label>
                    <input class="input col-md-9" id="mail" value="<?=Auth::$current->mail?>"  type="email">
                </div>
                <div class="row">
                    <label class="col-md-3" for="cpwd">Password (Actual)</label>
                    <input class="input col-md-9" id="cpwd" value="" type="password">
                </div>
                <div class="row">
                    <label class="col-md-3" for="cpwd">Password (Nuevo)</label>
                    <input class="input col-md-9" id="pwd1" value="" type="password">
                </div>
                <div class="row">
                    <label class="col-md-3" for="cpwd">Password (Nuevo,Repetir)</label>
                    <input class="input col-md-9" id="pwd2" value="" type="password">
                </div>
                <div class="row">
                    <button class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
	</body>
</html>