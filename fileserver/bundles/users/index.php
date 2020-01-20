<?php
require_once('../../lib/Util.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<?php HTMLApp::putHeaders('SAO-Player'); ?>		
		<script type="text/javascript" src="js/user.js"></script>
		<script type="text/javascript">
		async function createUser()
		{
			try
			{
				let name = document.getElementById('_name').value;
				let mail = document.getElementById('_mail').value;
				let user = new User(null,name,mail);
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
			btn.classList.add('w3-button');
			btn.onclick=createUser;
			img = document.createElement('img');
			img.src = "../../styles/toolbar/user-add.svg"
			btn.appendChild(img);
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
				btn.classList.add('w3-button');
				let img = document.createElement('img');
				img.src = "../../styles/toolbar/config.svg"
				btn.onclick = ()=>{window.open('views/details.php?id='+user.id)};
				btn.appendChild(img);
				cell.appendChild(btn);

				btn = document.createElement('button');
				btn.classList.add('w3-button');
				btn.onclick = ()=>{deleteUser(user)};
				img = document.createElement('img');
				img.src = "../../styles/toolbar/user-del.svg"
				btn.appendChild(img);
				cell.appendChild(btn);
				
				row.appendChild(cell);
				grp.appendChild(row);
			}
			
			tbl.appendChild(grp);

			UI.clear("users").appendChild(tbl);
		}
		</script>
	</head>
	<body onload="loadUsers()">
		<h1>Usuarios</h1>	
		<div class="w3-container">
			<div id="users" class="w3-responsive">
			</div>
		</div>
	</body>
</html>
