<?php

require_once('../../lib/Util.php');

class Index extends app\HTMLApp
{
	public function __construct()
	{
		parent::__construct(1);
		
		$this->addStyle('../../styles/main/main.css');
		$this->addScript('../../js/auth/User.js');

		$usr=isset($_REQUEST['usr']) ? $_REQUEST['usr'] : \auth\Auth::get()->id ;
		$this->addOnload("loadUser('".$usr."')");
		
	}

	public function putBody()
	{
		?>
		<script type="text/javascript">
			var usr = null;

			function userLoaded(data)
			{
				if(!(data instanceof User))
				{
					var msg="No se pudo cargar el usuario\n"
					
					if(typeof data == 'string')
						msg+=data;
					else if(typeof data.error != 'undefined')
						msg+=data.error;

					alert(msg);
					return false;
				}
				
				usr=data;
				document.getElementById('user-name').innerText=data.id;
				document.getElementById('mail').value=data.mail;
				document.getElementById('pw').value='';
				document.getElementById('pw2').value='';
				return true;
			}

			function userSaved(data)
			{
				if(userLoaded(data))
				{
					alert("Cambios guardados");
				}
			}

			function saveUser()
			{
				usr.mail=document.getElementById('mail').value;
				usr.pw=document.getElementById('pw').value;
				usr.pw2=document.getElementById('pw2').value;
				usr.save(userSaved);
			}

			function loadUser(usr)
			{
				User.load(usr,userLoaded);
			}
		</script>
		<h1>Propiedades de <span id="user-name"/></h1>
		<div>
			<input type="hidden" id="usr" name="usr"  />
			<label for="mail">Correo:</label><input name="mail" id="mail"  /><br/><br>
			<label for="mail">Contraseña:</label><input type="password" name="pw" id="pw"  /><br/>
			<label for="mail">Contraseña (Repetir):</label><input type="password" name="pw2" id="pw2" /><br/>
			<button onclick="saveUser()">Guardar</button>
		</div>


		<?php
	}
}

$index = new Index();
$index->run();

