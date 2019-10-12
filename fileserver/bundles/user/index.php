<?php
require_once('../../lib/Util.php');
Auth::checkSession();

?>
<!DOCTYPE html>
<html>
	<head>
		<?php HTMLApp::putHeaders('Usuario actual'); ?>		
		<script type="text/javascript" src="../users/js/user.js"></script>
        <script rel="stylesheet" href="../../styles/main/main.css"></script>
		<script type="text/javascript">

        let usr = null;

		async function loadUser()
		{
            try
            {
                usr=await User.get("<?=Auth::$current->id?>");
                document.getElementById('name').value=usr.name;
                document.getElementById('mail').value=usr.mail;
                document.getElementById('cpw').value="";
                document.getElementById('pw').value="";
                document.getElementById('pw2').value="";
            }
            catch(ex)
            {
                alert(""+ex);
            }
        }
        
        async function save()
        {
            try
            {
                usr.mail=document.getElementById('mail').value;
                usr.cpw=document.getElementById('cpw').value;
                usr.pw=document.getElementById('pw').value;
                usr.pw2=document.getElementById('pw2').value;
                await usr.save();
                alert("Usuario guardado");
            }
            catch(ex)
            {
                alert(""+ex);
            }
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
                    <input class="input col-md-9" id="name" readonly="true" >
                </div>
                <div class="row">
                    <label class="col-md-3" for="mail">Mail</label>
                    <input class="input col-md-9" id="mail"  type="email">
                </div>

                <div class="row">
                    <label class="col-md-3" for="cpwd">Password (Actual)</label>
                    <input class="input col-md-9" id="cpw" value="" type="password">
                </div>
                <div class="row">
                    <label class="col-md-3" for="cpwd">Password (Nuevo)</label>
                    <input class="input col-md-9" id="pw" value="" type="password">
                </div>
                <div class="row">
                    <label class="col-md-3" for="cpwd">Password (Nuevo,Repetir)</label>
                    <input class="input col-md-9" id="pw2" value="" type="password">
                </div>
                <div class="row">
                    <button class="btn btn-primary" onclick=save()>Guardar</button>
                </div>
            </div>
        </div>
	</body>
</html>