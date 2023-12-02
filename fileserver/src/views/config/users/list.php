<?php
require_once('../../../lib/Util.php');

use app\HTMLApp;

class Main extends HTMLApp
{
    function __construct()
	{
		parent::__construct();
		$this->title = 'SAO-Explorer';
		$this->styles[] = 'styles/config.css';
		$this->scripts[]='js/user.js';
	}


	function header($args)
    {
?>
        <script type="text/javascript">
async function createUser()
		{
			try
			{
				let name = document.getElementById('_name').value;
				let mail = document.getElementById('_mail').value;
				let user = new User();
				user.name = name;
				user.mail = mail;
				await user.insert();
			}
			catch(ex)
			{
				alert(ex);
			}

			await loadUsers();
		}

		async function deleteUser(user)
		{
			try
			{
				if(!confirm('Delete user '+user.name+'?'))
					return;
				await user.delete();
			}
			catch(ex)
			{
				alert(ex);
			}			
			
			await loadUsers();

		}

		async function loadUsers()
		{
			let users = await User.list(null);

			let tbl = document.createElement('table');
			tbl.classList.add('w3-table','w3-bordered','w3-striped','w3-border','w3-hoverable',);
			let grp = document.createElement('thead');
			let row = document.createElement('tr');
			
			for(let txt of ['Usuario','Correo','Opciones'])
			{
				cell = document.createElement('th');
				cell.appendChild(document.createTextNode(txt));
				row.appendChild(cell);
			}
			grp.appendChild(row);
			tbl.appendChild(grp);

			grp=document.createElement('tbody');

			row = document.createElement('tr');
			for(let prop of ['name','mail'])
			{
				cell = document.createElement('td');
				let input = document.createElement('input');
				input.id = '_'+prop;
				input.classList.add('w3-input');
				cell.appendChild(input);
				row.appendChild(cell);
			}

			cell = document.createElement('td');
			btn = document.createElement('button');
			btn.onclick=createUser;
			btn.classList.add('sfs-icon', 'sfs-config-button-useradd', 'w3-button')
						
			cell.appendChild(btn);
			row.appendChild(cell);
			grp.appendChild(row);

			for(let user of users)
			{
				row = document.createElement('tr');
				for(let prop of ['name','mail'])
				{
					cell = document.createElement('td');
					cell.appendChild(document.createTextNode(user[prop]));
					row.appendChild(cell);
				}
				cell = document.createElement('td');

				let btn = document.createElement('button');
				btn.classList.add('sfs-icon', 'sfs-config-button-config', 'w3-button')
				btn.onclick = ()=>{window.open('details.php?user='+user.name)};
				cell.appendChild(btn);

				btn = document.createElement('button');
				btn.classList.add('sfs-icon', 'sfs-config-button-userdel', 'w3-button')
				btn.onclick = ()=>{deleteUser(user)};				
				cell.appendChild(btn);
				
				row.appendChild(cell);
				grp.appendChild(row);
			}
			
			tbl.appendChild(grp);

			UI.clear("users").appendChild(tbl);
		}

		window.addEventListener('load',loadUsers);
        </script>
<?php
    }

    function body($args)
    {
?>
		<h1>Usuarios</h1>	
		<div class="w3-container">
			<div id="users" class="w3-responsive">
			</div>
		</div>
<?php
	}
}
$m = new Main();
$m->run();
