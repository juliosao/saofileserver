<?php
require_once('../../../lib/Util.php');
use auth\Auth;
use app\HTMLApp;

Auth::checkSession();
$id=isset($_GET['id']) ? $_GET['id'] : Auth::$current->id;
?>
<!DOCTYPE html>
<html>
	<head>
		<?php HTMLApp::putHeaders('Usuario actual'); ?>		
		<script type="text/javascript" src="../js/user.js"></script>
        <script rel="stylesheet" href="../../../styles/main/main.css"></script>
		<script type="text/javascript">

        let usr = null;

		async function loadUser()
		{
            try
            {
                usr=await User.get("<?=$id?>");
                document.getElementById('name').value=usr.name;
                document.getElementById('mail').value=usr.mail;
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
				let pw = document.getElementById('pw').value;
				let pw2 = document.getElementById('pw2').value;
				
				if(pw!=pw2)
				{
					alert("Passwords doesnt match!")
					return;
				}

				if(pw2!='')
                	usr.pw2=pw2;

				if(pw!='')
                	usr.pw=pw;


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
		<div class="w3-container">
            <input type="hidden" id="id" value="<?=Auth::$current->id?>">
            <div class="form">
                <div class="w3-row w3-margin">
                    <label class="w3-col m3" for="name">Nombre</label>
                    <div class="w3-col m5"><input class="w3-input" id="name" readonly="true" ></div>
                </div>
                <div class="w3-row w3-margin">
                    <label class="w3-col m3" for="mail">Mail</label>
                    <div class="w3-col m5"><input class="w3-input" id="mail"  type="email"></div>
                </div>
                <div class="w3-row w3-margin">
                    <label class="w3-col m3" for="cpwd">Password (Nuevo)</label>
                    <div class="w3-col m5"><input class="w3-input" id="pw" value="" type="password"></div>
                </div>
                <div class="w3-row w3-margin">
                    <label class="w3-col m3" for="cpwd">Password (Nuevo,Repetir)</label>
                    <div class="w3-col m5"><input class="w3-input" id="pw2" value="" type="password"></div>
                </div>
                <div class="w3-row w3-margin">
                    <button class="w3-btn w3-blue" onclick=save()>Guardar</button>
                </div>
            </div>
        </div>
	</body>
</html>