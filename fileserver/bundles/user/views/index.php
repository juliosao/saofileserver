<?php

require_once('../../../lib/Util.php');

class Index extends app\HTMLApp
{
	public function __construct()
	{
		parent::__construct(1);
		
		$this->title='Propiedades del usuario';
		$this->addStyle('../../../styles/main/main.css');
		$this->addScript('../../../js/Remote.js');
		$this->addScript('../../../js/User.js');

		$usr=isset($_REQUEST['id']) ? $_REQUEST['id'] : \auth\Auth::get()->id ;
		$this->addOnload("User.load('".$usr."',userLoaded)");
		
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
				fillForm()
				return true;
			}

			function fillForm()
			{
				document.getElementById('user-name').innerText=usr.name;
				document.getElementById('mail').value=usr.mail;
				document.getElementById('pw').value='';
				document.getElementById('pw2').value='';
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
		<input type="hidden" id="usr" name="usr"  />

		<div class="form-group">
			<label for="mail">Correo:</label>
			<input class="form-control" type="email" name="mail" id="mail"  autocomplete="off" />
		</div>
		<div class="form-group">
			<label for="pw">Contraseña:</label>
			<input class="form-control" type="password" name="pw" id="pw" autocomplete="off" />
		</div>
		<div class="form-group">
			<label for="pw2">Contraseña (Repetir):</label>
			<input class="form-control" type="password" name="pw2" id="pw2" autocomplete="off" />
		</div>
		<div class="form-group">
			<button class="btn btn-primary" onclick="saveUser()">Guardar</button>
			<button class="btn" onclick="saveUser()">Restaurar</button>
		</div>
		<?php
	}
}

$index = new Index();
$index->run();

